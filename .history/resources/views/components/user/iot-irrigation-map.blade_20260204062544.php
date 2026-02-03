{{-- 
    IoT Irrigation Map Component - Read-Only Display
    Menampilkan polygon dari database coordinates
    HANYA untuk display, tidak ada edit functionality
    
    Features:
    - Satellite / Standard map toggle
    - Hide/Show polygon toggle
    - High zoom support (up to zoom 22)
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
        'zoom' => 18,
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
    
    /* Map Controls Container */
    .map-controls {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        gap: 8px;
    }
    
    /* Map Type Toggle Button */
    .map-type-toggle {
        background: white;
        border-radius: 8px;
        padding: 8px 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        display: flex;
        gap: 4px;
        border: none;
    }
    
    .map-type-btn {
        padding: 6px 12px;
        border: none;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s ease;
        background: transparent;
        color: #6b7280;
    }
    
    .map-type-btn:hover {
        background: #f3f4f6;
    }
    
    .map-type-btn.active {
        background: #3b82f6;
        color: white;
    }
    
    /* Polygon Toggle Button */
    .polygon-toggle {
        background: white;
        border-radius: 8px;
        padding: 8px 12px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.15);
        display: flex;
        align-items: center;
        gap: 8px;
        cursor: pointer;
        border: none;
        font-size: 12px;
        font-weight: 500;
        color: #374151;
        transition: all 0.2s ease;
    }
    
    .polygon-toggle:hover {
        background: #f9fafb;
    }
    
    .polygon-toggle .toggle-icon {
        width: 18px;
        height: 18px;
        display: flex;
        align-items: center;
        justify-content: center;
    }
    
    .polygon-toggle.hidden-polygons {
        color: #9ca3af;
    }
    
    .polygon-toggle.hidden-polygons .toggle-icon {
        opacity: 0.5;
    }
</style>

<!-- Map Container -->
<div class="irrigation-map-container">
    <div id="iot-map-readonly"></div>
    
    <!-- Map Controls (Top Right) -->
    <div class="map-controls">
        <!-- Map Type Toggle: Satellite / Standard -->
        <div class="map-type-toggle">
            <button type="button" class="map-type-btn active" data-type="satellite" id="btn-satellite">
                Satelit
            </button>
            <button type="button" class="map-type-btn" data-type="standard" id="btn-standard">
                Standar
            </button>
        </div>
        
        <!-- Polygon Visibility Toggle -->
        <button type="button" class="polygon-toggle" id="btn-polygon-toggle">
            <span class="toggle-icon">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                    <polygon points="12 2 22 8.5 22 15.5 12 22 2 15.5 2 8.5 12 2"></polygon>
                </svg>
            </span>
            <span id="polygon-toggle-text">Sembunyikan Polygon</span>
        </button>
    </div>
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
        const zoom = mapConfig.zoom || 18;
        
        // ========================================
        // 1. TILE LAYERS - Multiple providers for reliability
        // ========================================
        
        // Satellite Layer - Using Google Satellite (most reliable for high zoom)
        const satelliteLayer = L.tileLayer('https://mt1.google.com/vt/lyrs=s&x={x}&y={y}&z={z}', {
            attribution: '&copy; Google Maps',
            maxZoom: 22,
            maxNativeZoom: 21
        });
        
        // Standard Layer - OpenStreetMap (reliable and free)
        const standardLayer = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a>',
            maxZoom: 22,
            maxNativeZoom: 19
        });
        
        // Hybrid Layer - Google Hybrid (satellite + labels) as fallback
        const hybridLayer = L.tileLayer('https://mt1.google.com/vt/lyrs=y&x={x}&y={y}&z={z}', {
            attribution: '&copy; Google Maps',
            maxZoom: 22,
            maxNativeZoom: 21
        });
        
        // ========================================
        // 2. MAP INITIALIZATION
        // ========================================
        const map = L.map('iot-map-readonly', {
            center: center,
            zoom: zoom,
            minZoom: 10,
            maxZoom: 22,
            zoomControl: true,
            layers: [satelliteLayer] // Default to satellite
        });
        
        // Track current layer type
        let currentLayerType = 'satellite';
        let currentTileLayer = satelliteLayer;
        
        // ========================================
        // 3. POLYGON LAYER GROUP
        // ========================================
        const polygonLayerGroup = L.layerGroup().addTo(map);
        let polygonsVisible = true;
        
        // Get blocks data
        const blocks = mapConfig.blocks || [];
        
        // Render each block polygon
        blocks.forEach(function(block) {
            // Check if block has enough points for polygon
            if (!block.points || block.points.length < 3) {
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
                fillOpacity: 0.4
            });
            
            // Bind simple popup (only block name - clean UI)
            polygon.bindPopup(createBlockPopupContent(block));
            
            // Add to layer group
            polygonLayerGroup.addLayer(polygon);
        });
        
        // Create popup content for block (simplified - no color/marker info)
        function createBlockPopupContent(block) {
            return `
                <div style="min-width: 120px; text-align: center;">
                    <div style="font-weight: 700; font-size: 14px; color: #1f2937;">
                        ${block.name}
                    </div>
                </div>
            `;
        }
        
        // ========================================
        // 4. MAP TYPE TOGGLE (Satellite / Standard)
        // ========================================
        const btnSatellite = document.getElementById('btn-satellite');
        const btnStandard = document.getElementById('btn-standard');
        
        function switchMapType(type) {
            // Remove current layer
            map.removeLayer(currentTileLayer);
            
            // Update buttons UI
            btnSatellite.classList.remove('active');
            btnStandard.classList.remove('active');
            
            if (type === 'satellite') {
                currentTileLayer = satelliteLayer;
                btnSatellite.classList.add('active');
                currentLayerType = 'satellite';
            } else {
                currentTileLayer = standardLayer;
                btnStandard.classList.add('active');
                currentLayerType = 'standard';
            }
            
            // Add new layer (at bottom, below polygons)
            currentTileLayer.addTo(map);
            currentTileLayer.bringToBack();
        }
        
        btnSatellite.addEventListener('click', function() {
            switchMapType('satellite');
        });
        
        btnStandard.addEventListener('click', function() {
            switchMapType('standard');
        });
        
        // ========================================
        // 5. POLYGON VISIBILITY TOGGLE
        // ========================================
        const btnPolygonToggle = document.getElementById('btn-polygon-toggle');
        const polygonToggleText = document.getElementById('polygon-toggle-text');
        
        btnPolygonToggle.addEventListener('click', function() {
            polygonsVisible = !polygonsVisible;
            
            if (polygonsVisible) {
                map.addLayer(polygonLayerGroup);
                polygonToggleText.textContent = 'Sembunyikan Polygon';
                btnPolygonToggle.classList.remove('hidden-polygons');
            } else {
                map.removeLayer(polygonLayerGroup);
                polygonToggleText.textContent = 'Tampilkan Polygon';
                btnPolygonToggle.classList.add('hidden-polygons');
            }
        });
        
        // ========================================
        // 6. FIT BOUNDS TO POLYGONS (if available)
        // ========================================
        if (blocks.length > 0 && polygonLayerGroup.getLayers().length > 0) {
            try {
                const bounds = polygonLayerGroup.getBounds();
                if (bounds.isValid()) {
                    map.fitBounds(bounds, { padding: [30, 30], maxZoom: 18 });
                }
            } catch (e) {
                // Fallback to center if bounds fail
                map.setView(center, zoom);
            }
        }
        
        // ========================================
        // 7. TILE ERROR HANDLING
        // ========================================
        currentTileLayer.on('tileerror', function(e) {
            // Silent fallback - Google tiles are generally reliable
            // If persistent errors, could switch to backup tile provider
        });
    }
})();
</script>
