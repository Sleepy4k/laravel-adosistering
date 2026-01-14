{{-- 
    IoT Irrigation Map Component - Read-Only Display
    Menampilkan polygon dari database coordinates
    HANYA untuk display, tidak ada edit functionality
--}}

@php
    use App\Models\Coordinate;
    use App\Models\Block;
    
    // Ambil data coordinates dengan relasi block dari database
    $coordinates = Coordinate::with('block')->get();
    
    // Format data untuk map
    $blocks = $coordinates->map(function ($coordinate) {
        return [
            'id' => $coordinate->id,
            'name' => $coordinate->block->name ?? 'Block ' . $coordinate->marker,
            'marker' => $coordinate->marker,
            'color' => $coordinate->color,
            'points' => is_array($coordinate->points) ? $coordinate->points : json_decode($coordinate->points, true),
        ];
    })->toArray();
    
    // Calculate center dari semua points
    $allPoints = collect($blocks)->flatMap(fn($b) => $b['points'] ?? []);
    $centerLat = $allPoints->avg('lat') ?? -7.6114;
    $centerLng = $allPoints->avg('lng') ?? 109.1773;
    
    $mapConfig = [
        'center' => ['lat' => $centerLat, 'lng' => $centerLng],
        'zoom' => 19,
        'blocks' => $blocks,
    ];
@endphp

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
      crossorigin=""/>

<style>
    .irrigation-map-container {
        width: 100%;
        height: 100%;
        border-radius: 1rem;
        overflow: hidden;
        position: relative;
    }
    
    #iot-map-readonly {
        width: 100%;
        height: 100%;
        min-height: 400px;
        border-radius: 1rem;
    }
    
    /* Leaflet popup customization */
    .leaflet-popup-content-wrapper {
        border-radius: 12px;
        padding: 4px;
    }
    
    .leaflet-popup-content {
        margin: 12px;
        font-size: 13px;
        line-height: 1.6;
    }
    
    .leaflet-container {
        border-radius: 1rem;
    }
    
    /* Legend */
    .map-legend {
        position: absolute;
        bottom: 20px;
        right: 20px;
        z-index: 1000;
        background: white;
        border-radius: 8px;
        padding: 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        font-size: 12px;
    }
    
    .legend-title {
        font-weight: 600;
        color: #374151;
        margin-bottom: 8px;
    }
    
    .legend-item {
        display: flex;
        align-items: center;
        gap: 8px;
        margin-bottom: 4px;
    }
    
    .legend-item:last-child {
        margin-bottom: 0;
    }
    
    .legend-color {
        width: 16px;
        height: 16px;
        border-radius: 4px;
        border: 1px solid rgba(0, 0, 0, 0.1);
    }
</style>

<!-- Map Container -->
<div class="irrigation-map-container">
    <div id="iot-map-readonly"></div>
    
    <!-- Legend -->
    @if(isset($mapConfig['blocks']) && count($mapConfig['blocks']) > 0)
    <div class="map-legend">
        <div class="legend-title">Blok Area</div>
        @foreach($mapConfig['blocks'] as $block)
        <div class="legend-item">
            <div class="legend-color" style="background-color: {{ $block['color'] }};"></div>
            <span>{{ $block['name'] }}</span>
        </div>
        @endforeach
    </div>
    @endif
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
        crossorigin=""></script>

<script>
(function() {
    // Wait for DOM ready
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initIotMapReadonly);
    } else {
        initIotMapReadonly();
    }
    
    function initIotMapReadonly() {
        // Check Leaflet loaded
        if (typeof L === 'undefined') {
            console.error('Leaflet JS not loaded');
            return;
        }
        
        const mapElement = document.getElementById('iot-map-readonly');
        if (!mapElement) {
            console.error('Map container #iot-map-readonly not found');
            return;
        }
        
        // Map config from Laravel
        const mapConfig = @json($mapConfig);
        
        // Get center and zoom from config
        const center = mapConfig.center 
            ? [mapConfig.center.lat, mapConfig.center.lng] 
            : [-7.6114, 109.1773]; // Default Maos
        const zoom = mapConfig.zoom || 19;
        
        // Initialize map (read-only)
        const map = L.map('iot-map-readonly', {
            center: center,
            zoom: zoom,
            minZoom: 10,
            maxZoom: 22,
            zoomControl: true
        });
        
        // Add satellite tile layer
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri',
            maxZoom: 22
        }).addTo(map);
        
        // Get blocks data
        const blocks = mapConfig.blocks || [];
        
        // Render each block polygon
        blocks.forEach(function(block) {
            // Check if block has enough points for polygon
            if (!block.points || block.points.length < 3) {
                // Skip blocks with insufficient points
                return;
            }
            
            const latLngs = block.points.map(point => [point.lat, point.lng]);
            const color = block.color || '#3b82f6';
            
            // Create polygon
            const polygon = L.polygon(latLngs, {
                color: color,
                weight: 2,
                opacity: 1,
                fillColor: color,
                fillOpacity: 0.5
            }).addTo(map);
            
            // Bind popup to polygon
            polygon.bindPopup(createBlockPopupContent(block));
        });
        
        // Create popup content for block
        function createBlockPopupContent(block) {
            const pointsCount = block.points ? block.points.length : 0;
            
            return `
                <div style="min-width: 180px;">
                    <div style="font-weight: 700; font-size: 15px; margin-bottom: 8px; color: #1f2937;">
                        ${block.name}
                    </div>
                    <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">
                        <strong>Marker:</strong> ${block.marker || '-'}
                    </div>
                    <div style="font-size: 12px; color: #6b7280; margin-bottom: 4px;">
                        <strong>Warna:</strong> ${block.color || '-'}
                    </div>
                    <div style="font-size: 11px; color: #9ca3af; margin-top: 8px; padding-top: 8px; border-top: 1px solid #e5e7eb;">
                        <strong>Titik Koordinat:</strong> ${pointsCount} titik
                    </div>
                </div>
            `;
        }
    }
})();
</script>
