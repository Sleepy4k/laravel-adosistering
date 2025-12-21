{{-- 
    IoT Irrigation Map Component - Data-Driven Implementation
    Frontend-only Leaflet.js with VERIFIED center coordinate
    Center: -7.611510, 109.177325 (EXACT, DO NOT SHIFT!)
    Max offset: ±0.0004° (~44m)
    NO pipelines - ONLY polygons, sprayers, sensors, tanks
--}}

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" 
      integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" 
      crossorigin=""/>

<!-- Leaflet Draw CSS -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.css" 
      integrity="sha512-gc3xjCmIy673V6MyOAZhIW93xhM9ei1I+gLbmFjUHIjocENRsLX/QUE1htk5q1XV2D/iie/VQ8DXI6Vu8bexvQ==" 
      crossorigin=""/>

<style>
    .irrigation-map-wrapper {
        width: 100%;
        height: 100%;
        border-radius: 1rem;
        overflow: visible; /* Changed from hidden to visible for absolute positioned controls */
        position: relative; /* REQUIRED for absolute positioned children */
    }
    
    #iot-irrigation-map {
        width: 100%;
        height: 100%;
        min-height: 400px;
        border-radius: 1rem;
        overflow: hidden; /* Move overflow hidden to map element */
    }
    
    /* Icon Styles */
    .map-icon { background: none; border: none; }
    .pin-label {
        background: rgba(255, 255, 255, 0.95);
        padding: 4px 10px;
        border-radius: 6px;
        font-weight: 700;
        font-size: 13px;
        color: #1f2937;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        white-space: nowrap;
        border: 2px solid #fbbf24;
    }
    
    /* Leaflet Customization */
    .leaflet-popup-content-wrapper { border-radius: 12px; padding: 4px; }
    .leaflet-popup-content { margin: 12px; font-size: 13px; line-height: 1.6; }
    .leaflet-control-layers { border-radius: 12px; border: 2px solid #e5e7eb; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); }
    .leaflet-control-layers-expanded { padding: 12px; background: white; }
    .leaflet-container { border-radius: 1rem; }
    
    /* Edit Control Panel */
    .edit-control-panel {
        position: absolute;
        top: 10px;
        left: 60px;
        z-index: 1000;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        padding: 8px;
        display: flex;
        gap: 4px;
    }
    
    .edit-btn {
        padding: 8px 12px;
        border: none;
        border-radius: 6px;
        background: #f3f4f6;
        color: #374151;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        gap: 4px;
    }
    
    .edit-btn:hover {
        background: #e5e7eb;
        transform: translateY(-1px);
    }
    
    .edit-btn.active {
        background: #3b82f6;
        color: white;
    }
    
    .edit-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }
    
    /* Marker Type Selector */
    .marker-type-selector {
        position: absolute;
        top: 60px;
        left: 60px;
        z-index: 1000;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        padding: 12px;
        display: none;
    }
    
    .marker-type-selector.show {
        display: block;
    }
    
    .marker-type-btn {
        display: block;
        width: 100%;
        padding: 8px 12px;
        margin-bottom: 4px;
        border: 2px solid #e5e7eb;
        border-radius: 6px;
        background: white;
        font-size: 12px;
        font-weight: 600;
        cursor: pointer;
        text-align: left;
        transition: all 0.2s;
    }
    
    .marker-type-btn:last-child {
        margin-bottom: 0;
    }
    
    .marker-type-btn:hover {
        border-color: #3b82f6;
        background: #eff6ff;
    }
    
    .marker-type-btn.selected {
        border-color: #3b82f6;
        background: #3b82f6;
        color: white;
    }
    
    /* Block Configuration Panel */
    .block-config-panel {
        position: absolute;
        top: 60px;
        left: 60px;
        z-index: 1000;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.2);
        padding: 12px;
        display: none;
        min-width: 220px;
    }
    
    .block-config-panel.show {
        display: block;
    }
    
    .config-group {
        margin-bottom: 12px;
    }
    
    .config-group:last-child {
        margin-bottom: 0;
    }
    
    .config-label {
        display: block;
        font-size: 12px;
        font-weight: 600;
        color: #374151;
        margin-bottom: 6px;
    }
    
    .config-input {
        width: 100%;
        padding: 8px;
        border: 2px solid #e5e7eb;
        border-radius: 6px;
        font-size: 12px;
        font-family: inherit;
    }
    
    .config-input:focus {
        outline: none;
        border-color: #3b82f6;
    }
    
    .color-picker-wrapper {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
    }
    
    .color-option {
        width: 32px;
        height: 32px;
        border-radius: 6px;
        border: 3px solid transparent;
        cursor: pointer;
        transition: all 0.2s;
    }
    
    .color-option:hover {
        transform: scale(1.1);
    }
    
    .color-option.selected {
        border-color: #1f2937;
        box-shadow: 0 0 0 2px white, 0 0 0 4px #1f2937;
    }
    
    /* Label Configuration Panel */
    .label-config-panel {
        position: absolute;
        top: 60px;
        left: 60px;
        z-index: 1000;
        background: white;
        padding: 16px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
        min-width: 280px;
        display: none;
    }
    
    .label-config-panel.show {
        display: block;
    }
</style>

<!-- Map Container -->
<div class="irrigation-map-wrapper">
    <!-- Edit Control Panel -->
    <div class="edit-control-panel">
        <button id="drawModeBtn" class="edit-btn" title="Draw Mode">
            <span>✏️</span> Draw
        </button>
        <button id="editModeBtn" class="edit-btn" title="Edit Mode">
            <span>📝</span> Edit
        </button>
        <button id="deleteModeBtn" class="edit-btn" title="Delete Mode">
            <span>🗑️</span> Delete
        </button>
        <button id="clearAllBtn" class="edit-btn" title="Clear All Layers" style="background: #ef4444; color: white;">
            <span>🧹</span> Clear All
        </button>
        <button id="saveModeBtn" class="edit-btn" title="Save Changes" style="margin-left: 8px; background: #10b981; color: white;">
            <span>💾</span> Save
        </button>
    </div>
    
    <!-- Marker Type Selector -->
    <div id="markerTypeSelector" class="marker-type-selector">
        <div style="margin-bottom: 8px; font-weight: 600; color: #374151;">Select Marker Type:</div>
        <button class="marker-type-btn" data-type="block_label">📍 Block Label</button>
        <button class="marker-type-btn" data-type="sprayer">💧 Sprayer</button>
        <button class="marker-type-btn" data-type="sensor">📡 Sensor</button>
        <button class="marker-type-btn" data-type="tank">🪣 Tank</button>
    </div>
    
    <!-- Block Configuration Panel -->
    <div id="blockConfigPanel" class="block-config-panel">
        <div style="margin-bottom: 12px; font-weight: 600; color: #374151;">Configure New Block:</div>
        
        <div class="config-group">
            <label class="config-label">Block Name:</label>
            <input type="text" id="blockNameInput" class="config-input" placeholder="e.g., BLOK F" maxlength="20">
        </div>
        
        <div class="config-group">
            <label class="config-label">Block Color:</label>
            <div class="color-picker-wrapper">
                <div class="color-option selected" data-color="#3b82f6" style="background: #3b82f6;" title="Blue"></div>
                <div class="color-option" data-color="#84cc16" style="background: #84cc16;" title="Lime"></div>
                <div class="color-option" data-color="#ec4899" style="background: #ec4899;" title="Pink"></div>
                <div class="color-option" data-color="#8b5cf6" style="background: #8b5cf6;" title="Purple"></div>
                <div class="color-option" data-color="#ef4444" style="background: #ef4444;" title="Red"></div>
                <div class="color-option" data-color="#f59e0b" style="background: #f59e0b;" title="Orange"></div>
                <div class="color-option" data-color="#10b981" style="background: #10b981;" title="Green"></div>
                <div class="color-option" data-color="#06b6d4" style="background: #06b6d4;" title="Cyan"></div>
            </div>
        </div>
        
        <div style="font-size: 11px; color: #6b7280; margin-top: 8px;">
            💡 Configure before drawing polygon
        </div>
    </div>
    
    <!-- Block Label Configuration Panel -->
    <div id="labelConfigPanel" class="label-config-panel">
        <div style="margin-bottom: 12px; font-weight: 600; color: #374151;">Configure Block Label:</div>
        
        <div class="config-group">
            <label class="config-label">Label Name:</label>
            <input type="text" id="labelNameInput" class="config-input" placeholder="e.g., BLOK F" maxlength="20" required>
        </div>
        
        <div style="font-size: 11px; color: #6b7280; margin-top: 8px;">
            💡 Enter label name before placing marker
        </div>
    </div>
    
    <!-- Edit Property Panel -->
    <div id="editPropertyPanel" class="block-config-panel">
        <div style="margin-bottom: 12px; font-weight: 600; color: #374151;">Edit Properties:</div>
        
        <div id="editNameGroup" class="config-group">
            <label class="config-label">Name:</label>
            <input type="text" id="editNameInput" class="config-input" placeholder="Enter name" maxlength="30">
        </div>
        
        <div id="editColorGroup" class="config-group" style="display: none;">
            <label class="config-label">Color:</label>
            <div class="color-picker-wrapper">
                <div class="color-option edit-color-option" data-color="#3b82f6" style="background: #3b82f6;" title="Blue"></div>
                <div class="color-option edit-color-option" data-color="#84cc16" style="background: #84cc16;" title="Lime"></div>
                <div class="color-option edit-color-option" data-color="#ec4899" style="background: #ec4899;" title="Pink"></div>
                <div class="color-option edit-color-option" data-color="#8b5cf6" style="background: #8b5cf6;" title="Purple"></div>
                <div class="color-option edit-color-option" data-color="#ef4444" style="background: #ef4444;" title="Red"></div>
                <div class="color-option edit-color-option" data-color="#f59e0b" style="background: #f59e0b;" title="Orange"></div>
                <div class="color-option edit-color-option" data-color="#10b981" style="background: #10b981;" title="Green"></div>
                <div class="color-option edit-color-option" data-color="#06b6d4" style="background: #06b6d4;" title="Cyan"></div>
            </div>
        </div>
        
        <div style="display: flex; gap: 8px; margin-top: 12px;">
            <button id="editSaveBtn" class="edit-btn" style="flex: 1; background: #10b981; color: white;">
                <span>✓</span> Save
            </button>
            <button id="editCancelBtn" class="edit-btn" style="flex: 1; background: #ef4444; color: white;">
                <span>✕</span> Cancel
            </button>
        </div>
        
        <div style="font-size: 11px; color: #6b7280; margin-top: 8px;">
            💡 Click layer to edit, then modify properties
        </div>
    </div>
    
    <div id="iot-irrigation-map"></div>
</div>

<!-- Leaflet JS -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" 
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" 
        crossorigin=""></script>

<!-- Leaflet Draw JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/leaflet.draw/1.0.4/leaflet.draw.js" 
        integrity="sha512-ozq8xQKq6urvuU6jNgkfqAmT7jKN2XumbrX1JiB3TnF7tI48DPI4Gy1GXKD/V3EExgAs1V+pRO7vwtS1LHg0Gw==" 
        crossorigin=""></script>

<script>
(function() {
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initMap);
    } else {
        initMap();
    }
    
    function initMap() {
        if (typeof L === 'undefined') {
            console.error('Leaflet JS not loaded');
            return;
        }
        
        const mapElement = document.getElementById('iot-irrigation-map');
        if (!mapElement) {
            console.error('Map container not found');
            return;
        }
        
        // ============================================
        // CENTER COORDINATE (VERIFIED - DO NOT CHANGE!)
        // ============================================
        const CENTER = [-7.611092481122535, 109.17738365591937];
        
        // ============================================
        // MAP INITIALIZATION
        // ============================================
        const map = L.map('iot-irrigation-map', {
            minZoom: 15,
            maxZoom: 20,
            zoomControl: true
        });
        
        // Satellite tiles
        L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}', {
            attribution: 'Tiles &copy; Esri',
            maxZoom: 20
        }).addTo(map);
        
        // ============================================
        // LAYER GROUPS
        // ============================================
        const layers = {
            areas: L.layerGroup().addTo(map),      // Ditampilkan secara default
            labels: L.layerGroup().addTo(map),     // Ditampilkan secara default
            sprayers: L.layerGroup().addTo(map),   // Ditampilkan secara default
            sensors: L.layerGroup().addTo(map),    // Ditampilkan secara default
            tanks: L.layerGroup().addTo(map)       // Ditampilkan secara default
        };
        
        // ============================================
        // EDITABLE LAYERS & DRAW CONTROLS
        // ============================================
        const editableItems = new L.FeatureGroup().addTo(map);
        
        // State management for editing
        let currentMode = null; // 'draw', 'edit', 'delete', null
        let selectedMarkerType = 'block_label'; // Default marker type
        let drawControl = null;
        let editableLayers = []; // Track all editable layers
        let currentEditLayer = null; // Track currently editing layer
        let selectedEditColor = '#3b82f6'; // Default color for editing
        
        // Block configuration state
        let selectedBlockColor = '#3b82f6'; // Default blue
        let blockNameInput = null;
        let labelNameInput = null; // For block label names
        
        // mapState - Backend-ready data structure
        const mapState = {
            blocks: [],   // Array of {id, name, color, coordinates: [[lat, lng]]}
            markers: []   // Array of {id, type, name, position: [lat, lng], blockId, blockName, humidity, flow, status}
        };
        
        // Initialize Draw Control (disabled by default)
        function initDrawControl() {
            if (drawControl) {
                map.removeControl(drawControl);
            }
            
            drawControl = new L.Control.Draw({
                position: 'topleft',
                draw: {
                    polygon: {
                        allowIntersection: false,
                        showArea: true,
                        shapeOptions: {
                            color: selectedBlockColor, // Use selected color
                            weight: 3,
                            fillOpacity: 0.2
                        }
                    },
                    marker: {
                        icon: L.divIcon({
                            className: 'custom-marker-icon',
                            html: '<div style="background: #3b82f6; width: 24px; height: 24px; border-radius: 50%; border: 3px solid white;"></div>',
                            iconSize: [24, 24]
                        })
                    },
                    polyline: false,
                    circle: false,
                    rectangle: false,
                    circlemarker: false
                },
                edit: {
                    featureGroup: editableItems,
                    remove: false // We'll handle delete with custom button
                }
            });
        }
        
        initDrawControl();
        
        // ============================================
        // UTILITY FUNCTIONS
        // ============================================
        
        // Point in Polygon detection using ray-casting algorithm
        function isPointInPolygon(point, polygon) {
            const x = point[0], y = point[1];
            let inside = false;
            
            for (let i = 0, j = polygon.length - 1; i < polygon.length; j = i++) {
                const xi = polygon[i][0], yi = polygon[i][1];
                const xj = polygon[j][0], yj = polygon[j][1];
                
                const intersect = ((yi > y) !== (yj > y)) &&
                    (x < (xj - xi) * (y - yi) / (yj - yi) + xi);
                    
                if (intersect) inside = !inside;
            }
            
            return inside;
        }
        
        // Detect which block a point (lat, lng) is in
        function detectBlock(lat, lng) {
            const point = [lat, lng];
            
            // Check existing blocks from mapData
            for (const area of mapData.areas) {
                if (isPointInPolygon(point, area.coordinates)) {
                    return {
                        id: area.id,
                        name: area.name
                    };
                }
            }
            
            // Check newly created blocks from mapState
            for (const block of mapState.blocks) {
                if (isPointInPolygon(point, block.coordinates)) {
                    return {
                        id: block.id,
                        name: block.name
                    };
                }
            }
            
            return null; // Not in any block
        }
        
        // ============================================
        // MAP DATA (EMPTY - WILL BE POPULATED VIA LEAFLET DRAW)
        // ============================================
        const mapData = {
            // Polygon areas (blocks) - Will be created using Leaflet Draw
            areas: [],
            
            // Area labels (block label markers) - Will be created using Leaflet Draw
            labels: [],
            
            // Sprayers (perangkat penyiraman - green circular icons) - Will be created using Leaflet Draw
            sprayers: [],
            
            // Sensors (sensor IoT - red square icons) - Will be created using Leaflet Draw
            sensors: [],
            
            // Tanks (tangki air - red rectangle icons) - Will be created using Leaflet Draw
            tanks: []
        };
        
        // ============================================
        // ICON GENERATORS (SVG)
        // ============================================
        
        const icons = {
            // 📍 Block Label Icon: Yellow location marker untuk menandai nama blok (e.g., BLOK A, BLOK F)
            blockLabel: (label) => L.divIcon({
                html: `
                    <div style="display: flex; flex-direction: column; align-items: center;">
                        <svg width="32" height="32" viewBox="0 0 32 32" style="filter: drop-shadow(0 2px 6px rgba(0,0,0,0.3));">
                            <circle cx="16" cy="16" r="15" fill="#fbbf24" stroke="white" stroke-width="2"/>
                            <path d="M16 8 L16 18 M16 18 L12 14 M16 18 L20 14" stroke="white" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
                            <circle cx="16" cy="22" r="1.5" fill="white"/>
                        </svg>
                        <div class="pin-label">${label}</div>
                    </div>
                `,
                className: 'map-icon',
                iconSize: [80, 50],
                iconAnchor: [40, 50]
            }),
            
            // 💧 Sprayer Icon: Green circle untuk perangkat penyiram/sprayer
            // Digunakan untuk: Sistem penyiraman otomatis di area irigasi
            sprayer: () => L.divIcon({
                html: `
                    <svg width="32" height="32" viewBox="0 0 32 32" style="filter: drop-shadow(0 3px 6px rgba(0,0,0,0.3));">
                        <circle cx="16" cy="16" r="14" fill="#22c55e" stroke="white" stroke-width="3"/>
                        <path d="M16 8 L16 24 M10 12 L22 12 M12 16 L20 16 M14 20 L18 20" stroke="white" stroke-width="2" stroke-linecap="round"/>
                    </svg>
                `,
                className: 'map-icon',
                iconSize: [32, 32],
                iconAnchor: [16, 16],
                popupAnchor: [0, -20]
            }),
            
            // 📡 Sensor Icon: Red square untuk sensor IoT
            // Digunakan untuk: Sensor kelembaban, suhu, atau monitoring lainnya
            sensor: (label) => L.divIcon({
                html: `
                    <div style="display: flex; align-items: center; justify-content: center; width: 24px; height: 24px; background: #ef4444; border: 2px solid white; border-radius: 4px; box-shadow: 0 2px 6px rgba(0,0,0,0.3);">
                        <span style="font-size: 10px; font-weight: 700; color: white;">${label}</span>
                    </div>
                `,
                className: 'map-icon',
                iconSize: [24, 24],
                iconAnchor: [12, 12],
                popupAnchor: [0, -15]
            }),
            
            // 🛢️ Tank Icon: Red rectangle untuk tangki air
            // Digunakan untuk: Tangki penyimpanan air untuk sistem irigasi
            tank: (label) => L.divIcon({
                html: `
                    <div style="display: flex; align-items: center; justify-content: center; width: 28px; height: 20px; background: #ef4444; border: 2px solid white; border-radius: 3px; box-shadow: 0 2px 6px rgba(0,0,0,0.3);">
                        <span style="font-size: 9px; font-weight: 700; color: white;">${label}</span>
                    </div>
                `,
                className: 'map-icon',
                iconSize: [28, 20],
                iconAnchor: [14, 10],
                popupAnchor: [0, -12]
            })
        };
        
        // ============================================
        // RENDER AREAS (DATA-DRIVEN)
        // ============================================
        const allCoords = [];
        
        mapData.areas.forEach(area => {
            const polygon = L.polygon(area.coordinates, {
                color: area.color,
                fillColor: area.color,
                fillOpacity: area.fillOpacity,
                weight: 3
            }).addTo(layers.areas);
            
            // Collect coordinates for bounds
            area.coordinates.forEach(coord => allCoords.push(coord));
            
            polygon.bindPopup(`
                <div style="min-width: 180px;">
                    <strong style="font-size: 16px; color: #1f2937; display: block; margin-bottom: 8px;">${area.name}</strong>
                    <div style="font-size: 13px; color: #6b7280;">
                        <div>📍 <strong>Area Irigasi</strong></div>
                        <div>📏 <strong>Status:</strong> <span style="color: ${area.color}; font-weight: 600;">Aktif</span></div>
                    </div>
                </div>
            `);
        });
        
        // ============================================
        // RENDER LABELS (DATA-DRIVEN)
        // ============================================
        mapData.labels.forEach(label => {
            L.marker([label.lat, label.lng], {
                icon: icons.blockLabel(label.area)
            }).addTo(layers.labels);
        });
        
        // ============================================
        // RENDER SPRAYERS (DATA-DRIVEN)
        // ============================================
        mapData.sprayers.forEach(sprayer => {
            L.marker([sprayer.lat, sprayer.lng], {
                icon: icons.sprayer()
            }).addTo(layers.sprayers).bindPopup(`
                <div style="min-width: 200px;">
                    <strong style="font-size: 16px; color: #1f2937; display: block; margin-bottom: 8px;">${sprayer.name}</strong>
                    <div style="font-size: 13px; color: #6b7280; line-height: 1.6;">
                        <div>📍 <strong>Blok:</strong> ${sprayer.block || '-'}</div>
                        <div>💧 <strong>Kelembaban:</strong> ${Math.floor(Math.random() * 30 + 40)}%</div>
                        <div>🚰 <strong>Debit:</strong> ${Math.floor(Math.random() * 10 + 25)} L/Menit</div>
                        <div>📊 <strong>Status:</strong> <span style="color: #22c55e; font-weight: 600;">Aktif</span></div>
                    </div>
                </div>
            `);
        });
        
        // ============================================
        // RENDER SENSORS (DATA-DRIVEN)
        // ============================================
        mapData.sensors.forEach(sensor => {
            L.marker([sensor.lat, sensor.lng], {
                icon: icons.sensor(sensor.name)
            }).addTo(layers.sensors).bindPopup(`
                <div style="min-width: 180px;">
                    <strong style="font-size: 16px; color: #1f2937; display: block; margin-bottom: 8px;">Sensor ${sensor.name}</strong>
                    <div style="font-size: 13px; color: #6b7280;">
                        <div>📡 <strong>Tipe:</strong> IoT Sensor</div>
                        <div>🔋 <strong>Battery:</strong> 85%</div>
                        <div>📊 <strong>Status:</strong> <span style="color: #22c55e; font-weight: 600;">Online</span></div>
                    </div>
                </div>
            `);
        });
        
        // ============================================
        // RENDER TANKS (DATA-DRIVEN)
        // ============================================
        mapData.tanks.forEach(tank => {
            L.marker([tank.lat, tank.lng], {
                icon: icons.tank(tank.name)
            }).addTo(layers.tanks).bindPopup(`
                <div style="min-width: 180px;">
                    <strong style="font-size: 16px; color: #1f2937; display: block; margin-bottom: 8px;">Tangki ${tank.name}</strong>
                    <div style="font-size: 13px; color: #6b7280;">
                        <div>🛢️ <strong>Kapasitas:</strong> 5000 L</div>
                        <div>💧 <strong>Isi:</strong> ${Math.floor(Math.random() * 30 + 60)}%</div>
                        <div>📊 <strong>Status:</strong> <span style="color: #22c55e; font-weight: 600;">Normal</span></div>
                    </div>
                </div>
            `);
        });
    
        
        // ============================================
        // LAYER CONTROL
        // ============================================
        L.control.layers(null, {
            "📦 Blok Area": layers.areas,
            "📍 Area Labels": layers.labels,
            "🚿 Sprayer": layers.sprayers,
            "📡 Sensor": layers.sensors,
            "🛢️ Tank": layers.tanks
        }, {
            position: 'topright',
            collapsed: false
        }).addTo(map);
        
        // ============================================
        // SCALE CONTROL
        // ============================================
        L.control.scale({
            position: 'bottomleft',
            imperial: false,
            metric: true
        }).addTo(map);
        
        // ============================================
        // FIT BOUNDS & SET VIEW
        // ============================================
        if (allCoords.length > 0) {
            // If there are coordinates from existing data, fit bounds
            const bounds = L.latLngBounds(allCoords);
            map.fitBounds(bounds, {
                padding: [40, 40],
                maxZoom: 18
            });
        } else {
            // No existing data, set view to center with default zoom
            map.setView(CENTER, 18);
        }
        
        // ============================================
        // DRAW EVENT HANDLERS
        // ============================================
        
        // When a new shape is created
        map.on('draw:created', function(e) {
            const layer = e.layer;
            const type = e.layerType;
            
            if (type === 'polygon') {
                // Get block name from input or generate default
                const blockName = blockNameInput && blockNameInput.value.trim() 
                    ? blockNameInput.value.trim() 
                    : 'New Block ' + (mapState.blocks.length + 1);
                
                // Apply selected color to the polygon
                layer.setStyle({
                    color: selectedBlockColor,
                    fillColor: selectedBlockColor,
                    fillOpacity: 0.2,
                    weight: 3
                });
                
                // Add to editable layer
                editableItems.addLayer(layer);
                
                // Get coordinates
                const latlngs = layer.getLatLngs()[0];
                const coordinates = latlngs.map(ll => [ll.lat, ll.lng]);
                
                // Generate ID
                const blockId = 'block-' + Date.now();
                
                // Store data ID in layer for tracking
                layer._dataId = blockId;
                layer._dataType = 'block';
                
                // Add to mapState
                const blockData = {
                    id: blockId,
                    name: blockName,
                    color: selectedBlockColor,
                    coordinates: coordinates
                };
                mapState.blocks.push(blockData);
                
                // Bind popup with styled content
                layer.bindPopup(`
                    <div style="min-width: 180px;">
                        <strong style="font-size: 16px; color: #1f2937; display: block; margin-bottom: 8px;">${blockData.name}</strong>
                        <div style="font-size: 13px; color: #6b7280;">
                            <div>📍 <strong>Area Irigasi</strong></div>
                            <div>🎨 <strong>Color:</strong> <span style="display: inline-block; width: 16px; height: 16px; background: ${selectedBlockColor}; border: 1px solid #ddd; border-radius: 3px; vertical-align: middle;"></span></div>
                            <div>📏 <strong>Status:</strong> <span style="color: ${selectedBlockColor}; font-weight: 600;">Baru Dibuat</span></div>
                            <div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid #e5e7eb;">
                                <small style="color: #9ca3af;">ID: ${blockId}</small>
                            </div>
                        </div>
                    </div>
                `);
                
                console.log('✅ Block created:', blockData);
                
                // Reset input after creation
                if (blockNameInput) {
                    blockNameInput.value = '';
                }
                
                // Hide block config panel
                document.getElementById('blockConfigPanel').classList.remove('show');
                
            } else if (type === 'marker') {
                // Add to editable layer
                editableItems.addLayer(layer);
                
                // Get position
                const latlng = layer.getLatLng();
                
                // Detect which block this marker is in
                const detectedBlock = detectBlock(latlng.lat, latlng.lng);
                
                // For block labels, validate name is provided
                let markerName = getMarkerName(selectedMarkerType);
                if (selectedMarkerType === 'block_label') {
                    if (!labelNameInput || !labelNameInput.value.trim()) {
                        alert('⚠️ Please enter a label name first!');
                        editableItems.removeLayer(layer);
                        return;
                    }
                    markerName = labelNameInput.value.trim();
                }
                
                // Generate ID
                const markerId = selectedMarkerType + '-' + Date.now();
                
                // Store data ID in layer for tracking
                layer._dataId = markerId;
                layer._dataType = selectedMarkerType;
                
                // Create backend-ready marker data
                const markerData = {
                    id: markerId,
                    type: selectedMarkerType,
                    name: markerName,
                    position: [latlng.lat, latlng.lng],
                    blockId: detectedBlock ? detectedBlock.id : null,
                    blockName: detectedBlock ? detectedBlock.name : null,
                    humidity: null,  // Backend will populate
                    flow: null,      // Backend will populate
                    status: null     // Backend will populate
                };
                mapState.markers.push(markerData);
                
                // Update icon based on type
                const newIcon = getIconForType(selectedMarkerType, markerData.name);
                layer.setIcon(newIcon);
                
                // Build popup content with block detection info
                let popupContent = `<div style="min-width: 200px;">
                    <strong style="font-size: 16px; color: #1f2937; display: block; margin-bottom: 8px;">${markerData.name}</strong>
                    <div style="font-size: 13px; color: #6b7280; line-height: 1.6;">`;
                
                // Show block detection for all markers
                if (detectedBlock) {
                    popupContent += `<div>📍 <strong>Blok:</strong> <span style="color: #22c55e; font-weight: 600;">${detectedBlock.name}</span></div>`;
                } else {
                    popupContent += `<div>📍 <strong>Blok:</strong> <span style="color: #ef4444;">Tidak terdeteksi</span></div>`;
                }
                
                // For sprayers, show backend-ready fields
                if (selectedMarkerType === 'sprayer') {
                    popupContent += `
                        <div>💧 <strong>Kelembaban:</strong> ${markerData.humidity !== null ? markerData.humidity + '%' : '-'}</div>
                        <div>🚰 <strong>Debit:</strong> ${markerData.flow !== null ? markerData.flow + ' L/Menit' : '-'}</div>
                        <div>📊 <strong>Status:</strong> ${markerData.status || '-'}</div>`;
                }
                
                // For sensors, show sensor info
                if (selectedMarkerType === 'sensor') {
                    popupContent += `
                        <div>📡 <strong>Tipe:</strong> IoT Sensor</div>
                        <div>🔋 <strong>Battery:</strong> -</div>
                        <div>📊 <strong>Status:</strong> -</div>`;
                }
                
                // For tanks, show tank info
                if (selectedMarkerType === 'tank') {
                    popupContent += `
                        <div>🛢️ <strong>Kapasitas:</strong> -</div>
                        <div>💧 <strong>Isi:</strong> -</div>
                        <div>📊 <strong>Status:</strong> -</div>`;
                }
                
                popupContent += `
                        <div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid #e5e7eb;">
                            <small style="color: #9ca3af;">Type: ${selectedMarkerType}</small><br>
                            <small style="color: #9ca3af;">ID: ${markerId}</small>
                        </div>
                    </div>
                </div>`;
                
                // Bind popup
                layer.bindPopup(popupContent);
                
                console.log('✅ Marker created:', markerData);
                
                // Clear label name input after creation
                if (selectedMarkerType === 'block_label' && labelNameInput) {
                    labelNameInput.value = '';
                }
            }
            
            // Hide panels after creation
            document.getElementById('markerTypeSelector').classList.remove('show');
            document.getElementById('labelConfigPanel').classList.remove('show');
        });
        
        // When shapes are edited
        map.on('draw:edited', function(e) {
            const layers = e.layers;
            
            layers.eachLayer(function(layer) {
                if (layer instanceof L.Polygon) {
                    // Update polygon coordinates in mapState
                    const latlngs = layer.getLatLngs()[0];
                    const coordinates = latlngs.map(ll => [ll.lat, ll.lng]);
                    
                    // Find and update in mapState (you'd need to track layer IDs)
                    console.log('✅ Polygon edited:', coordinates);
                    
                } else if (layer instanceof L.Marker) {
                    // Update marker position in mapState
                    const latlng = layer.getLatLng();
                    console.log('✅ Marker moved:', [latlng.lat, latlng.lng]);
                }
            });
        });
        
        // When shapes are deleted
        map.on('draw:deleted', function(e) {
            const layers = e.layers;
            
            layers.eachLayer(function(layer) {
                console.log('�️ Layer deleted');
                // Remove from mapState (you'd need to track layer IDs)
            });
        });
        
        // When draw mode starts (show appropriate panel)
        map.on('draw:drawstart', function(e) {
            if (e.layerType === 'marker') {
                // ALWAYS show marker type selector first
                document.getElementById('markerTypeSelector').classList.add('show');
                document.getElementById('labelConfigPanel').classList.remove('show');
                document.getElementById('blockConfigPanel').classList.remove('show');
            } else if (e.layerType === 'polygon') {
                document.getElementById('blockConfigPanel').classList.add('show');
                document.getElementById('markerTypeSelector').classList.remove('show');
                document.getElementById('labelConfigPanel').classList.remove('show');
            }
        });
        
        // When draw mode stops
        map.on('draw:drawstop', function() {
            document.getElementById('markerTypeSelector').classList.remove('show');
            document.getElementById('blockConfigPanel').classList.remove('show');
            document.getElementById('labelConfigPanel').classList.remove('show');
        });
        
        // ============================================
        // EDIT MODE CLICK HANDLERS
        // ============================================
        
        // Handle click on editable layers
        editableItems.on('click', function(e) {
            const layer = e.layer;
            
            // Prevent map click event
            L.DomEvent.stopPropagation(e);
            
            if (currentMode === 'edit') {
                // Show edit panel for this layer
                showEditPanel(layer);
            } else if (currentMode === 'delete') {
                // Delete layer immediately with confirmation
                deleteLayer(layer);
            }
        });
        
        // Show edit panel for a layer
        function showEditPanel(layer) {
            currentEditLayer = layer;
            const editPanel = document.getElementById('editPropertyPanel');
            const editNameInput = document.getElementById('editNameInput');
            const editColorGroup = document.getElementById('editColorGroup');
            
            // Remove highlight from all layers
            editableItems.eachLayer(function(l) {
                if (l instanceof L.Polygon) {
                    const blockData = mapState.blocks.find(b => b.id === l._dataId);
                    if (blockData) {
                        l.setStyle({ 
                            weight: 3,
                            color: blockData.color,
                            fillColor: blockData.color
                        });
                    }
                }
            });
            
            if (layer instanceof L.Polygon && layer._dataId) {
                // Editing polygon
                const blockData = mapState.blocks.find(b => b.id === layer._dataId);
                if (blockData) {
                    editNameInput.value = blockData.name;
                    selectedEditColor = blockData.color;
                    
                    // Highlight selected polygon
                    layer.setStyle({ 
                        weight: 5,
                        color: '#ff6b6b',
                        fillColor: blockData.color
                    });
                    
                    // Show color picker for polygons
                    editColorGroup.style.display = 'block';
                    
                    // Update selected color
                    document.querySelectorAll('.edit-color-option').forEach(btn => {
                        btn.classList.remove('selected');
                        if (btn.getAttribute('data-color') === blockData.color) {
                            btn.classList.add('selected');
                        }
                    });
                    
                    editPanel.classList.add('show');
                    console.log('📝 Editing block:', blockData.name);
                }
            } else if (layer instanceof L.Marker && layer._dataId) {
                // Editing marker
                const markerData = mapState.markers.find(m => m.id === layer._dataId);
                if (markerData) {
                    editNameInput.value = markerData.name;
                    
                    // Hide color picker for markers
                    editColorGroup.style.display = 'none';
                    
                    editPanel.classList.add('show');
                    console.log('📝 Editing marker:', markerData.name);
                }
            }
        }
        
        // Delete a single layer
        function deleteLayer(layer) {
            if (!layer._dataId) return;
            
            let itemName = 'Item';
            let itemType = 'layer';
            
            if (layer instanceof L.Polygon) {
                const blockData = mapState.blocks.find(b => b.id === layer._dataId);
                if (blockData) {
                    itemName = blockData.name;
                    itemType = 'Block';
                }
            } else if (layer instanceof L.Marker) {
                const markerData = mapState.markers.find(m => m.id === layer._dataId);
                if (markerData) {
                    itemName = markerData.name;
                    itemType = markerData.type;
                }
            }
            
            // Confirm deletion
            if (confirm(`🗑️ Delete ${itemType}: "${itemName}"?`)) {
                // Remove from map
                editableItems.removeLayer(layer);
                
                // Remove from mapState
                if (layer instanceof L.Polygon) {
                    const index = mapState.blocks.findIndex(b => b.id === layer._dataId);
                    if (index !== -1) {
                        mapState.blocks.splice(index, 1);
                        console.log('✅ Block deleted:', itemName);
                    }
                } else if (layer instanceof L.Marker) {
                    const index = mapState.markers.findIndex(m => m.id === layer._dataId);
                    if (index !== -1) {
                        mapState.markers.splice(index, 1);
                        console.log('✅ Marker deleted:', itemName);
                    }
                }
            }
        }
        
        // Clear all layers
        function clearAllLayers() {
            const blockCount = mapState.blocks.length;
            const markerCount = mapState.markers.length;
            const totalCount = blockCount + markerCount;
            
            if (totalCount === 0) {
                alert('ℹ️ No layers to delete.');
                return;
            }
            
            // Confirm deletion
            if (confirm(`🧹 Clear All Layers?\n\n${blockCount} Block(s)\n${markerCount} Marker(s)\n\nTotal: ${totalCount} items\n\nThis action cannot be undone!`)) {
                // Clear all layers from map
                editableItems.clearLayers();
                
                // Clear mapState
                mapState.blocks = [];
                mapState.markers = [];
                
                console.log('🧹 All layers cleared');
                alert('✅ All layers have been deleted!');
            }
        }
        
        // ============================================
        // UI CONTROL HANDLERS
        // ============================================
        
        const drawModeBtn = document.getElementById('drawModeBtn');
        const editModeBtn = document.getElementById('editModeBtn');
        const deleteModeBtn = document.getElementById('deleteModeBtn');
        const saveModeBtn = document.getElementById('saveModeBtn');
        
        // Draw Mode
        drawModeBtn.addEventListener('click', function() {
            if (currentMode === 'draw') {
                // Disable draw mode
                map.removeControl(drawControl);
                currentMode = null;
                drawModeBtn.classList.remove('active');
            } else {
                // Enable draw mode
                map.removeControl(drawControl);
                initDrawControl();
                map.addControl(drawControl);
                currentMode = 'draw';
                
                // Update button states
                drawModeBtn.classList.add('active');
                editModeBtn.classList.remove('active');
                deleteModeBtn.classList.remove('active');
            }
        });
        
        // Edit Mode
        editModeBtn.addEventListener('click', function() {
            if (currentMode === 'edit') {
                // Disable edit mode
                if (drawControl) map.removeControl(drawControl);
                currentMode = null;
                editModeBtn.classList.remove('active');
                
                // Hide edit panel
                document.getElementById('editPropertyPanel').classList.remove('show');
                currentEditLayer = null;
                
                // Remove highlight from all layers
                editableItems.eachLayer(function(layer) {
                    if (layer instanceof L.Polygon) {
                        layer.setStyle({ weight: 3 });
                    }
                });
            } else {
                // Enable edit mode WITHOUT Leaflet Draw controls
                if (drawControl) map.removeControl(drawControl);
                currentMode = 'edit';
                
                // Update button states
                drawModeBtn.classList.remove('active');
                editModeBtn.classList.add('active');
                deleteModeBtn.classList.remove('active');
                
                // Show instruction
                console.log('📝 Edit mode: Click on any polygon or marker to edit');
            }
        });
        
        // Delete Mode
        deleteModeBtn.addEventListener('click', function() {
            if (currentMode === 'delete') {
                // Disable delete mode
                if (drawControl) map.removeControl(drawControl);
                currentMode = null;
                deleteModeBtn.classList.remove('active');
            } else {
                // Enable delete mode WITHOUT Leaflet Draw controls
                if (drawControl) map.removeControl(drawControl);
                currentMode = 'delete';
                
                // Update button states
                drawModeBtn.classList.remove('active');
                editModeBtn.classList.remove('active');
                deleteModeBtn.classList.add('active');
                
                // Hide edit panel if open
                document.getElementById('editPropertyPanel').classList.remove('show');
                currentEditLayer = null;
                
                // Show instruction
                console.log('🗑️ Delete mode: Click on any polygon or marker to delete');
            }
        });
        
        // Clear All Button
        const clearAllBtn = document.getElementById('clearAllBtn');
        clearAllBtn.addEventListener('click', function() {
            clearAllLayers();
        });
        
        // Save Mode
        saveModeBtn.addEventListener('click', function() {
            console.log('💾 Saving map state...');
            console.log('📦 Blocks:', mapState.blocks);
            console.log('📍 Markers:', mapState.markers);
            
            // Here you would send mapState to backend
            // Example: fetch('/api/map/save', { method: 'POST', body: JSON.stringify(mapState) })
            
            alert('Map state saved to console! Check browser console for data structure.');
        });
        
        // Marker Type Selector
        document.querySelectorAll('.marker-type-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                // Remove selected class from all
                document.querySelectorAll('.marker-type-btn').forEach(b => b.classList.remove('selected'));
                
                // Add selected class to clicked
                this.classList.add('selected');
                
                // Update selected type
                selectedMarkerType = this.getAttribute('data-type');
                console.log('📍 Marker type selected:', selectedMarkerType);
                
                // If block_label is selected, show label config panel
                // Otherwise, just hide the selector and let user place marker
                if (selectedMarkerType === 'block_label') {
                    // Show label config panel for naming
                    document.getElementById('labelConfigPanel').classList.add('show');
                    document.getElementById('markerTypeSelector').classList.remove('show');
                } else {
                    // For other types (sprayer, sensor, tank), just hide selector
                    // User can now place marker directly
                    document.getElementById('markerTypeSelector').classList.remove('show');
                    document.getElementById('labelConfigPanel').classList.remove('show');
                }
            });
        });
        
        // Block Name Input
        blockNameInput = document.getElementById('blockNameInput');
        
        // Label Name Input
        labelNameInput = document.getElementById('labelNameInput');
        
        // Edit Panel Handlers
        const editSaveBtn = document.getElementById('editSaveBtn');
        const editCancelBtn = document.getElementById('editCancelBtn');
        const editNameInput = document.getElementById('editNameInput');
        
        // Edit Color Picker
        document.querySelectorAll('.edit-color-option').forEach(colorBtn => {
            colorBtn.addEventListener('click', function() {
                // Remove selected class from all
                document.querySelectorAll('.edit-color-option').forEach(btn => btn.classList.remove('selected'));
                
                // Add selected class to clicked
                this.classList.add('selected');
                
                // Update selected color
                selectedEditColor = this.getAttribute('data-color');
                console.log('🎨 Edit color selected:', selectedEditColor);
            });
        });
        
        // Edit Save Button
        editSaveBtn.addEventListener('click', function() {
            if (!currentEditLayer) return;
            
            const newName = editNameInput.value.trim();
            if (!newName) {
                alert('⚠️ Name cannot be empty!');
                return;
            }
            
            if (currentEditLayer instanceof L.Polygon && currentEditLayer._dataId) {
                // Update polygon (block)
                const blockData = mapState.blocks.find(b => b.id === currentEditLayer._dataId);
                if (blockData) {
                    // Update data
                    blockData.name = newName;
                    blockData.color = selectedEditColor;
                    
                    // Update layer style
                    currentEditLayer.setStyle({
                        color: selectedEditColor,
                        fillColor: selectedEditColor,
                        fillOpacity: 0.2,
                        weight: 3
                    });
                    
                    // Update popup
                    currentEditLayer.setPopupContent(`
                        <div style="min-width: 180px;">
                            <strong style="font-size: 16px; color: #1f2937; display: block; margin-bottom: 8px;">${blockData.name}</strong>
                            <div style="font-size: 13px; color: #6b7280;">
                                <div>📍 <strong>Area Irigasi</strong></div>
                                <div>🎨 <strong>Color:</strong> <span style="display: inline-block; width: 16px; height: 16px; background: ${selectedEditColor}; border: 1px solid #ddd; border-radius: 3px; vertical-align: middle;"></span></div>
                                <div>📏 <strong>Status:</strong> <span style="color: ${selectedEditColor}; font-weight: 600;">Aktif</span></div>
                                <div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid #e5e7eb;">
                                    <small style="color: #9ca3af;">ID: ${blockData.id}</small>
                                </div>
                            </div>
                        </div>
                    `);
                    
                    console.log('✅ Block updated:', blockData);
                }
            } else if (currentEditLayer instanceof L.Marker && currentEditLayer._dataId) {
                // Update marker
                const markerData = mapState.markers.find(m => m.id === currentEditLayer._dataId);
                if (markerData) {
                    // Update data
                    markerData.name = newName;
                    
                    // Update icon
                    const newIcon = getIconForType(markerData.type, newName);
                    currentEditLayer.setIcon(newIcon);
                    
                    // Rebuild popup content
                    let popupContent = `<div style="min-width: 200px;">
                        <strong style="font-size: 16px; color: #1f2937; display: block; margin-bottom: 8px;">${markerData.name}</strong>
                        <div style="font-size: 13px; color: #6b7280; line-height: 1.6;">`;
                    
                    // Show block detection for all markers
                    if (markerData.blockName) {
                        popupContent += `<div>📍 <strong>Blok:</strong> <span style="color: #22c55e; font-weight: 600;">${markerData.blockName}</span></div>`;
                    } else {
                        popupContent += `<div>📍 <strong>Blok:</strong> <span style="color: #ef4444;">Tidak terdeteksi</span></div>`;
                    }
                    
                    // For sprayers, show backend-ready fields
                    if (markerData.type === 'sprayer') {
                        popupContent += `
                            <div>💧 <strong>Kelembaban:</strong> ${markerData.humidity !== null ? markerData.humidity + '%' : '-'}</div>
                            <div>🚰 <strong>Debit:</strong> ${markerData.flow !== null ? markerData.flow + ' L/Menit' : '-'}</div>
                            <div>📊 <strong>Status:</strong> ${markerData.status || '-'}</div>`;
                    }
                    
                    // For sensors, show sensor info
                    if (markerData.type === 'sensor') {
                        popupContent += `
                            <div>📡 <strong>Tipe:</strong> IoT Sensor</div>
                            <div>🔋 <strong>Battery:</strong> -</div>
                            <div>📊 <strong>Status:</strong> -</div>`;
                    }
                    
                    // For tanks, show tank info
                    if (markerData.type === 'tank') {
                        popupContent += `
                            <div>🛢️ <strong>Kapasitas:</strong> -</div>
                            <div>💧 <strong>Isi:</strong> -</div>
                            <div>📊 <strong>Status:</strong> -</div>`;
                    }
                    
                    popupContent += `
                            <div style="margin-top: 8px; padding-top: 8px; border-top: 1px solid #e5e7eb;">
                                <small style="color: #9ca3af;">Type: ${markerData.type}</small><br>
                                <small style="color: #9ca3af;">ID: ${markerData.id}</small>
                            </div>
                        </div>
                    </div>`;
                    
                    currentEditLayer.setPopupContent(popupContent);
                    
                    console.log('✅ Marker updated:', markerData);
                }
            }
            
            // Hide edit panel
            document.getElementById('editPropertyPanel').classList.remove('show');
            currentEditLayer = null;
            
            alert('✅ Changes saved successfully!');
        });
        
        // Edit Cancel Button
        editCancelBtn.addEventListener('click', function() {
            // Remove highlight from selected layer
            if (currentEditLayer && currentEditLayer instanceof L.Polygon && currentEditLayer._dataId) {
                const blockData = mapState.blocks.find(b => b.id === currentEditLayer._dataId);
                if (blockData) {
                    currentEditLayer.setStyle({ 
                        weight: 3,
                        color: blockData.color,
                        fillColor: blockData.color,
                        fillOpacity: 0.2
                    });
                }
            }
            
            // Hide edit panel without saving
            document.getElementById('editPropertyPanel').classList.remove('show');
            currentEditLayer = null;
            console.log('❌ Edit cancelled');
        });
        
        // Color Picker
        document.querySelectorAll('.color-option').forEach(colorBtn => {
            colorBtn.addEventListener('click', function() {
                // Remove selected class from all
                document.querySelectorAll('.color-option').forEach(btn => btn.classList.remove('selected'));
                
                // Add selected class to clicked
                this.classList.add('selected');
                
                // Update selected color
                selectedBlockColor = this.getAttribute('data-color');
                console.log('🎨 Block color selected:', selectedBlockColor);
                
                // Update draw control with new color
                if (currentMode === 'draw') {
                    map.removeControl(drawControl);
                    initDrawControl();
                    map.addControl(drawControl);
                }
            });
        });
        
        // Helper functions
        function getMarkerName(type) {
            const counters = mapState.markers.filter(m => m.type === type).length + 1;
            const names = {
                'block_label': 'Label ' + counters,
                'sprayer': 'Sprayer ' + counters,
                'sensor': 'S' + counters,
                'tank': 'TN' + counters
            };
            return names[type] || 'Marker ' + counters;
        }
        
        function getIconForType(type, name) {
            switch(type) {
                case 'block_label':
                    return icons.blockLabel(name);
                case 'sprayer':
                    return icons.sprayer();
                case 'sensor':
                    return icons.sensor(name);
                case 'tank':
                    return icons.tank(name);
                default:
                    return L.divIcon({
                        className: 'custom-marker-icon',
                        html: '<div style="background: #3b82f6; width: 24px; height: 24px; border-radius: 50%; border: 3px solid white;"></div>',
                        iconSize: [24, 24]
                    });
            }
        }
        
        console.log('✅ IoT Irrigation Map loaded');
        console.log('�📍 Center:', CENTER);
        console.log('📦 Areas:', mapData.areas.length);
        console.log('🚿 Sprayers:', mapData.sprayers.length);
        console.log('📡 Sensors:', mapData.sensors.length);
        console.log('🛢️ Tanks:', mapData.tanks.length);
        console.log('🎨 Draw controls initialized');
    }
})();
</script>
