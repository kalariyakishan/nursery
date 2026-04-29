<x-app-layout>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap');
        .font-inter { font-family: 'Inter', sans-serif; }
        
        .glass {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.4);
        }
        
        .map-container { position: relative; overflow: hidden; border-radius: 1rem; box-shadow: 0 10px 30px -10px rgba(0,0,0,0.1); }
        .pac-target-input { border-radius: 0.75rem !important; padding: 0.75rem 1rem !important; border: 1px solid #e2e8f0 !important; box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1) !important; font-family: 'Inter', sans-serif !important; font-size: 13px !important; outline: none !important; }
        .pac-target-input:focus { border-color: #22c55e !important; box-shadow: 0 0 0 2px rgba(34,197,94,0.2) !important; }
        
        ::-webkit-scrollbar { width: 5px; height: 5px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #cbd5e1; border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: #94a3b8; }
        
        [x-cloak] { display: none !important; }
        
        .fade-in { animation: fadeIn 0.4s ease-in-out; }
        @keyframes fadeIn { from { opacity: 0; transform: translateY(5px); } to { opacity: 1; transform: translateY(0); } }
    </style>

    <!-- Load Google Maps API -->
    <script src="https://maps.googleapis.com/maps/api/js?key={{ env('GOOGLE_MAPS_API_KEY') }}&libraries=drawing,geometry"></script>
    <script src="https://unpkg.com/@googlemaps/markerclusterer/dist/index.min.js"></script>
    
    <div class="min-h-[calc(100vh-4rem)] bg-[#f8fafc] font-inter py-6 fade-in" x-data="plantationPlanner()" x-init="init()">
        
        <div class="max-w-[1800px] mx-auto px-4 sm:px-6 lg:px-8">
            
            <!-- Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h1 class="text-2xl font-extrabold text-gray-900 flex items-center gap-2">
                        <span class="text-3xl">🌱</span> Smart Plantation Planner
                    </h1>
                    <p class="text-gray-500 text-[13px] font-medium mt-1">Design your farm layout and irrigation system efficiently</p>
                </div>
            </div>

            <div class="flex flex-col lg:flex-row gap-6 h-[calc(100vh-12rem)] min-h-[700px]">
                
                <!-- LEFT SIDEBAR -->
                <div class="w-full lg:w-[360px] flex-shrink-0 flex flex-col gap-5 overflow-y-auto pr-1 pb-10">
                    <x-card>
                        <x-slot name="title"><span class="text-gray-800">📋 Plan Setup</span></x-slot>
                        
                        <x-input label="Plan Name" x-model="form.name" placeholder="E.g., North Field Orchard" icon="✏️" />
                        
                        <x-select label="Planting Method" x-model="form.method" @change="calculatePreview" icon="📐">
                            <option value="grid">Grid Layout (Straight)</option>
                            <option value="zigzag">Zig-Zag (Triangular)</option>
                            <option value="random">Natural/Random</option>
                        </x-select>

                        <div class="grid grid-cols-2 gap-3">
                            <x-input type="number" step="0.1" min="0.1" label="Row Spacing (m)" x-model="form.row_spacing" @input.debounce.500ms="calculatePreview" />
                            <x-input type="number" step="0.1" min="0.1" label="Plant Spacing (m)" x-model="form.plant_spacing" @input.debounce.500ms="calculatePreview" />
                        </div>

                        <!-- Irrigation Toggle -->
                        <div class="mt-2 pt-4 border-t border-gray-100">
                            <label class="flex items-center space-x-3 cursor-pointer group">
                                <div class="relative flex items-center">
                                    <input type="checkbox" x-model="form.enable_irrigation" @change="calculatePreview" class="w-4 h-4 rounded border-gray-300 text-green-600 shadow-sm focus:border-green-500 focus:ring-green-500 transition-colors peer">
                                </div>
                                <span class="text-[13px] font-extrabold text-gray-700 group-hover:text-green-600 transition-colors flex items-center gap-1.5">
                                    <span class="text-base">💧</span> Enable Irrigation
                                </span>
                            </label>

                            <div x-show="form.enable_irrigation" x-transition class="mt-4 p-4 bg-gradient-to-br from-blue-50/50 to-indigo-50/30 rounded-xl border border-blue-100/50">
                                <x-select label="Main Pipe Routing" x-model="form.pipeline_routing" @change="handleRoutingChange" class="!bg-white !py-2">
                                    <option value="auto">Auto (Hugs Border)</option>
                                    <option value="custom">Draw Custom Main Pipe</option>
                                </x-select>

                                <div x-show="form.pipeline_routing === 'custom'" class="mb-4">
                                    <p class="text-[11px] text-gray-500 mb-2 leading-tight">Click below, draw on map, double-click to finish.</p>
                                    <button type="button" @click="startDrawingMainPipe" class="w-full flex justify-center py-2 px-3 border border-blue-200 rounded-lg shadow-sm text-xs font-bold text-blue-700 bg-white hover:bg-blue-50 transition-all focus:ring-2 focus:ring-blue-500 transform active:scale-95">
                                        <span x-show="!isDrawingPipe">🖊️ Draw Custom Pipe</span>
                                        <span x-show="isDrawingPipe" class="animate-pulse">Drawing mode active...</span>
                                    </button>
                                    <div x-show="form.custom_main_pipeline.length > 0 && !isDrawingPipe" class="text-[11px] text-green-600 mt-2 font-bold flex items-center gap-1 bg-green-50 p-1.5 rounded border border-green-100">
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg> Custom pipe drawn
                                    </div>
                                </div>

                                <div x-show="form.pipeline_routing === 'auto'" class="text-[11px] text-blue-800 mb-3 bg-blue-100/40 p-2 rounded-lg flex items-start gap-1.5 border border-blue-100">
                                    <span class="text-sm leading-none mt-0.5">📍</span>
                                    <span class="leading-relaxed">Click anywhere on the map to set your <strong class="font-bold">Water Source</strong>.</span>
                                </div>
                                
                                <x-select label="Irrigation Type" x-model="form.irrigation_type" @change="calculatePreview" class="!bg-white !py-2">
                                    <option value="drip">Drip Irrigation</option>
                                    <option value="sprinkler">Sprinkler System</option>
                                    <option value="manual">Manual / Open</option>
                                </x-select>
                                
                                <div x-show="form.irrigation_type === 'drip'">
                                    <x-select label="Drippers per Plant" x-model="form.drippers_per_plant" @change="calculatePreview" class="!bg-white !py-2 mb-0">
                                        <option value="1">1 Dripper</option>
                                        <option value="2">2 Drippers</option>
                                    </x-select>
                                </div>
                            </div>
                        </div>

                        <div class="mt-6 space-y-3">
                            <x-button @click="savePlan" x-bind:disabled="isSaving || !hasPolygon" class="group">
                                <span x-show="!isSaving" class="flex items-center gap-2">
                                    <svg class="w-4 h-4 group-hover:scale-110 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path></svg>
                                    Save Plan
                                </span>
                                <span x-show="isSaving" class="flex items-center gap-2">
                                    <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>
                                    Saving...
                                </span>
                            </x-button>
                            <x-button variant="outline" @click="clearMap">
                                Clear Map
                            </x-button>
                        </div>
                    </x-card>
                </div>

                <!-- CENTER MAP AREA -->
                <div class="flex-1 h-full relative map-container rounded-2xl overflow-hidden border border-gray-200/60 bg-white">
                    
                    <!-- Search Box Floating -->
                    <div class="absolute top-4 left-4 z-10 w-72 lg:w-80">
                        <input id="pac-input" class="w-full rounded-xl border-0 shadow-lg focus:ring-2 focus:ring-green-500 text-[13px] glass" type="text" placeholder="Search farm location...">
                    </div>

                    <!-- Layers Toggle Floating -->
                    <div class="absolute top-4 right-4 z-10 glass rounded-xl p-1 shadow-lg flex gap-1 border border-gray-200">
                        <button @click="toggleLayer('plants')" :class="showPlants ? 'bg-white shadow-sm text-green-700' : 'text-gray-500 hover:text-gray-800'" class="px-3 py-1.5 text-[11px] font-extrabold uppercase tracking-wide rounded-lg transition-all">Plants</button>
                        <button @click="toggleLayer('irrigation')" :class="showIrrigation ? 'bg-white shadow-sm text-blue-700' : 'text-gray-500 hover:text-gray-800'" class="px-3 py-1.5 text-[11px] font-extrabold uppercase tracking-wide rounded-lg transition-all" x-show="form.enable_irrigation">Pipes</button>
                    </div>

                    <!-- Loading Overlay -->
                    <div x-show="isCalculating" x-transition.opacity class="absolute inset-0 bg-white/40 z-20 flex items-center justify-center backdrop-blur-[2px]">
                        <div class="bg-white px-5 py-3 rounded-2xl shadow-xl flex items-center gap-3 border border-gray-100">
                            <div class="relative w-6 h-6">
                                <div class="absolute inset-0 border-[3px] border-green-100 rounded-full"></div>
                                <div class="absolute inset-0 border-[3px] border-green-500 rounded-full border-t-transparent animate-spin"></div>
                            </div>
                            <span class="text-gray-800 font-bold text-[13px]">Optimizing layout...</span>
                        </div>
                    </div>

                    <div id="map" class="w-full h-full bg-gray-100"></div>
                </div>

                <!-- RIGHT SIDEBAR -->
                <div class="w-full lg:w-[360px] flex-shrink-0 flex flex-col gap-5 overflow-y-auto px-1 pb-10">
                    
                    <!-- Analytics Summary -->
                    <x-card class="bg-gradient-to-br from-emerald-50/50 to-green-50/30 border-green-100/50 shadow-sm">
                        <x-slot name="title"><span class="text-green-800 text-[15px]">📊 Analytics Overview</span></x-slot>
                        
                        <x-summary-item label="Total Area" x-text="formatArea(summary.area) + ' m²'" />
                        <x-summary-item label="Area in Acres" x-text="formatNumber(summary.area * 0.000247105, 2) + ' ac'" />
                        <x-summary-item label="Plant Density" x-text="calculateDensity() + ' / ac'" :border="false" />
                        
                        <div class="mt-3 p-4 bg-white rounded-xl shadow-[0_2px_10px_-3px_rgba(34,197,94,0.15)] border border-green-50 flex flex-col items-center justify-center transform transition-transform duration-300 hover:scale-[1.02]">
                            <span class="text-[11px] font-extrabold text-green-800/60 uppercase tracking-widest mb-0.5">Total Plants</span>
                            <span class="text-[2.5rem] leading-none font-black text-transparent bg-clip-text bg-gradient-to-br from-green-600 to-emerald-400">
                                <span x-text="formatNumber(summary.total_plants, 0)"></span>
                            </span>
                        </div>
                    </x-card>

                    <!-- Irrigation Summary -->
                    <template x-if="form.enable_irrigation && summary.irrigation">
                        <x-card class="bg-gradient-to-br from-blue-50/50 to-indigo-50/30 border-blue-100/50 shadow-sm fade-in">
                            <x-slot name="title"><span class="text-blue-800 text-[15px]">💧 Water System</span></x-slot>
                            <x-summary-item label="Main Pipe Length" x-text="formatNumber(summary.irrigation.total_main_pipe_length, 1) + ' m'" valueClass="text-blue-700" />
                            <x-summary-item label="Sub Pipe Length" x-text="formatNumber(summary.irrigation.total_sub_pipe_length, 1) + ' m'" valueClass="text-blue-700" />
                            
                            <template x-if="form.irrigation_type === 'drip'">
                                <div class="mt-3 p-3.5 bg-white rounded-xl shadow-[0_2px_10px_-3px_rgba(37,99,235,0.15)] border border-blue-50 flex items-center justify-between">
                                    <div class="flex flex-col">
                                        <span class="text-[11px] font-extrabold text-blue-800/60 uppercase tracking-widest mb-0.5">Total Drippers</span>
                                        <span class="text-[10px] font-bold text-gray-400"><span x-text="form.drippers_per_plant"></span> per plant</span>
                                    </div>
                                    <span class="text-2xl font-black text-blue-600">
                                        <span x-text="formatNumber(summary.irrigation.total_drippers, 0)"></span>
                                    </span>
                                </div>
                            </template>
                        </x-card>
                    </template>

                    <!-- Gujarati Guidance -->
                    <x-card class="bg-gradient-to-br from-[#FFFBEB] to-[#FEF3C7] border-[#FDE68A] shadow-sm">
                        <h3 class="text-[15px] font-extrabold text-[#92400E] mb-3 flex items-center gap-2 border-b border-[#FCD34D]/50 pb-2">
                            <span>📋</span> વાવેતર માર્ગદર્શન
                        </h3>
                        <ul class="space-y-2 text-[13px] font-medium text-[#92400E] marker:text-[#F59E0B] list-disc pl-4 leading-relaxed">
                            <li>નકશા પ્રમાણે હદની અંદર વાવેતર કરવું.</li>
                            <li>છોડ વચ્ચે <strong class="font-extrabold bg-[#FEF3C7] px-1 rounded" x-text="form.plant_spacing"></strong> મીટર અંતર.</li>
                            <li>લાઈન વચ્ચે <strong class="font-extrabold bg-[#FEF3C7] px-1 rounded" x-text="form.row_spacing"></strong> મીટર અંતર.</li>
                            <li x-show="form.method === 'zigzag'">બીજી લાઈન પહેલી લાઈનના છોડની વચ્ચે આવે તે રીતે (ઝિગ-ઝેગ).</li>
                            <template x-if="form.enable_irrigation">
                                <li class="text-blue-800 font-bold mt-2 pt-2 border-t border-[#FCD34D]/30">નકશામાં વાદળી લાઈન મુજબ પાઇપ નાખવી.</li>
                            </template>
                        </ul>
                    </x-card>

                    <!-- Saved Plans -->
                    <x-card class="flex-1 flex flex-col min-h-[200px]">
                        <x-slot name="title"><span class="text-gray-800 text-[15px]">💾 Saved Plans</span></x-slot>
                        <div class="space-y-2 overflow-y-auto flex-1 pr-1 -mr-2">
                            @forelse($plans as $plan)
                                <div class="group flex items-center justify-between p-3 rounded-xl border border-gray-100 bg-gray-50 hover:bg-white hover:shadow-[0_2px_8px_-2px_rgba(0,0,0,0.05)] hover:border-green-200 transition-all cursor-pointer" @click="loadPlan({{ $plan->toJson() }})">
                                    <div class="overflow-hidden pr-2">
                                        <h4 class="text-[13px] font-extrabold text-gray-800 truncate group-hover:text-green-700 transition-colors">{{ $plan->name }}</h4>
                                        <p class="text-[11px] font-medium text-gray-500 mt-0.5">{{ number_format($plan->area, 0) }} m² • <span class="text-green-600 font-bold">{{ number_format($plan->total_plants) }}</span> plants</p>
                                    </div>
                                    <div class="flex items-center gap-0.5 opacity-0 group-hover:opacity-100 transition-opacity">
                                        <a href="{{ route('plantation-plans.pdf', $plan->id) }}" target="_blank" @click.stop class="p-1.5 text-blue-500 hover:text-blue-700 hover:bg-blue-50 rounded-lg transition-colors" title="Download PDF">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"></path></svg>
                                        </a>
                                        <button @click.stop="deletePlan({{ $plan->id }})" class="p-1.5 text-red-400 hover:text-red-600 hover:bg-red-50 rounded-lg transition-colors" title="Delete">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                        </button>
                                    </div>
                                </div>
                            @empty
                                <div class="h-full flex items-center justify-center text-center text-[13px] text-gray-400 font-medium">
                                    No saved plans yet.
                                </div>
                            @endforelse
                        </div>
                    </x-card>

                </div>
            </div>
        </div>
    </div>

    <script>
        function plantationPlanner() {
            let map = null;
            let drawingManager = null;
            let polygon = null;
            let markers = [];
            let markerCluster = null;
            let waterSourceMarker = null;
            let pipelinePolylines = [];
            let customPipePolyline = null;

            return {
                form: {
                    name: '',
                    method: 'grid',
                    row_spacing: 10.0,
                    plant_spacing: 10.0,
                    polygon_coordinates: [],
                    enable_irrigation: false,
                    irrigation_type: 'drip',
                    drippers_per_plant: 1,
                    pipeline_routing: 'auto',
                    custom_main_pipeline: [],
                    water_source_coordinates: null
                },
                summary: {
                    area: 0,
                    total_plants: 0,
                    irrigation: null
                },
                isCalculating: false,
                isSaving: false,
                hasPolygon: false,
                isDrawingPipe: false,
                abortController: null,
                showPlants: true,
                showIrrigation: true,

                init() {
                    setTimeout(() => {
                        this.initMap();
                    }, 50);
                },

                initMap() {
                    const center = { lat: 20.5937, lng: 78.9629 };
                    
                    map = new google.maps.Map(document.getElementById('map'), {
                        zoom: 5,
                        center: center,
                        mapTypeId: 'hybrid',
                        tilt: 0,
                        disableDefaultUI: true,
                        zoomControl: true,
                        mapTypeControl: true,
                        mapTypeControlOptions: {
                            style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
                            position: google.maps.ControlPosition.TOP_RIGHT,
                        },
                        fullscreenControl: true,
                        fullscreenControlOptions: { position: google.maps.ControlPosition.BOTTOM_RIGHT }
                    });

                    const input = document.getElementById('pac-input');
                    const geocoder = new google.maps.Geocoder();
                    
                    input.addEventListener('keypress', (e) => {
                        if (e.key === 'Enter') {
                            e.preventDefault();
                            if (!input.value) return;
                            
                            geocoder.geocode({ address: input.value }, (results, status) => {
                                if (status === 'OK' && results[0]) {
                                    if (results[0].geometry.viewport) {
                                        map.fitBounds(results[0].geometry.viewport);
                                    } else {
                                        map.setCenter(results[0].geometry.location);
                                        map.setZoom(16);
                                    }
                                }
                            });
                        }
                    });

                    drawingManager = new google.maps.drawing.DrawingManager({
                        drawingMode: google.maps.drawing.OverlayType.POLYGON,
                        drawingControl: true,
                        drawingControlOptions: {
                            position: google.maps.ControlPosition.TOP_CENTER,
                            drawingModes: [google.maps.drawing.OverlayType.POLYGON]
                        },
                        polygonOptions: {
                            fillColor: '#16a34a',
                            fillOpacity: 0.15,
                            strokeWeight: 3,
                            strokeColor: '#16a34a',
                            clickable: false,
                            editable: true,
                            zIndex: 1
                        },
                        polylineOptions: {
                            strokeColor: '#2563eb',
                            strokeWeight: 4,
                            strokeOpacity: 0.9,
                            editable: true,
                        }
                    });

                    drawingManager.setMap(map);

                    google.maps.event.addListener(drawingManager, 'overlaycomplete', (event) => {
                        if (event.type === google.maps.drawing.OverlayType.POLYGON) {
                            if (polygon) polygon.setMap(null);
                            polygon = event.overlay;
                            this.hasPolygon = true;
                            drawingManager.setDrawingMode(null);

                            this.updatePolygonCoordinates();
                            this.calculatePreview();

                            polygon.getPaths().forEach((path) => {
                                ['insert_at', 'remove_at', 'set_at'].forEach(evt => {
                                    google.maps.event.addListener(path, evt, () => {
                                        this.updatePolygonCoordinates();
                                        this.calculatePreview();
                                    });
                                });
                            });
                        } else if (event.type === google.maps.drawing.OverlayType.POLYLINE) {
                            if (customPipePolyline) customPipePolyline.setMap(null);
                            
                            const path = event.overlay.getPath();
                            this.form.custom_main_pipeline = [];
                            for (let i = 0; i < path.getLength(); i++) {
                                const xy = path.getAt(i);
                                this.form.custom_main_pipeline.push({ lat: xy.lat(), lng: xy.lng() });
                            }
                            if (this.form.custom_main_pipeline.length > 0) {
                                this.form.water_source_coordinates = this.form.custom_main_pipeline[0];
                            }

                            drawingManager.setDrawingMode(null);
                            this.isDrawingPipe = false;
                            event.overlay.setMap(null);
                            this.calculatePreview();
                        }
                    });

                    google.maps.event.addListener(map, 'click', (e) => {
                        if (this.form.enable_irrigation && this.form.pipeline_routing === 'auto' && !this.isDrawingPipe) {
                            this.form.water_source_coordinates = {
                                lat: e.latLng.lat(),
                                lng: e.latLng.lng()
                            };
                            this.renderWaterSource();
                            this.calculatePreview();
                        }
                    });
                },

                updatePolygonCoordinates() {
                    if (!polygon) return;
                    const vertices = polygon.getPath();
                    this.form.polygon_coordinates = [];
                    for (let i = 0; i < vertices.getLength(); i++) {
                        const xy = vertices.getAt(i);
                        this.form.polygon_coordinates.push({ lat: xy.lat(), lng: xy.lng() });
                    }
                },

                clearMap() {
                    if (polygon) { polygon.setMap(null); polygon = null; }
                    this.clearMarkers();
                    this.clearIrrigation();
                    if (waterSourceMarker) { waterSourceMarker.setMap(null); waterSourceMarker = null; }
                    this.hasPolygon = false;
                    this.form.polygon_coordinates = [];
                    this.form.custom_main_pipeline = [];
                    this.form.water_source_coordinates = null;
                    this.summary.area = 0;
                    this.summary.total_plants = 0;
                    this.summary.irrigation = null;
                    this.form.name = '';
                    drawingManager.setDrawingMode(google.maps.drawing.OverlayType.POLYGON);
                },

                handleRoutingChange() {
                    if (this.form.pipeline_routing === 'custom') {
                        this.form.water_source_coordinates = null;
                        this.renderWaterSource();
                    } else {
                        this.form.custom_main_pipeline = [];
                    }
                    this.calculatePreview();
                },

                startDrawingMainPipe() {
                    this.isDrawingPipe = true;
                    this.form.custom_main_pipeline = [];
                    drawingManager.setDrawingMode(google.maps.drawing.OverlayType.POLYLINE);
                },

                toggleLayer(layer) {
                    if (layer === 'plants') {
                        this.showPlants = !this.showPlants;
                        if (markerCluster) {
                            this.showPlants ? markerCluster.setMap(map) : markerCluster.setMap(null);
                        } else {
                            markers.forEach(m => m.setMap(this.showPlants ? map : null));
                        }
                    } else if (layer === 'irrigation') {
                        this.showIrrigation = !this.showIrrigation;
                        pipelinePolylines.forEach(p => p.setMap(this.showIrrigation ? map : null));
                        if (waterSourceMarker) waterSourceMarker.setMap(this.showIrrigation ? map : null);
                    }
                },

                async calculatePreview() {
                    if (!this.hasPolygon || this.form.polygon_coordinates.length < 3) return;
                    if (!this.form.row_spacing || !this.form.plant_spacing) return;

                    if (this.abortController) this.abortController.abort();
                    this.abortController = new AbortController();

                    this.isCalculating = true;

                    try {
                        const response = await fetch('{{ route('plantation-plans.preview') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                            body: JSON.stringify(this.form),
                            signal: this.abortController.signal
                        });

                        const data = await response.json();
                        
                        if (response.ok) {
                            this.summary.area = data.area;
                            this.summary.total_plants = data.total_plants;
                            this.summary.irrigation = data.irrigation || null;
                            this.renderMarkers(data.points);
                            this.renderIrrigation(data.irrigation);
                        }
                    } catch (error) {
                        if (error.name !== 'AbortError') console.error("Network error:", error);
                    } finally {
                        this.isCalculating = false;
                    }
                },

                renderWaterSource() {
                    if (waterSourceMarker) waterSourceMarker.setMap(null);
                    if (this.form.water_source_coordinates && this.form.pipeline_routing === 'auto') {
                        waterSourceMarker = new google.maps.Marker({
                            position: this.form.water_source_coordinates,
                            map: this.showIrrigation ? map : null,
                            icon: {
                                url: 'data:image/svg+xml;utf-8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="%232563eb"><path d="M12 2c-5.33 4.55-8 8.48-8 11.8a8 8 0 0 0 16 0c0-3.32-2.67-7.25-8-11.8z"/></svg>',
                                scaledSize: new google.maps.Size(32, 32),
                                anchor: new google.maps.Point(16, 32)
                            },
                            title: 'Water Source',
                            animation: google.maps.Animation.DROP
                        });
                    }
                },

                clearIrrigation() {
                    pipelinePolylines.forEach(p => p.setMap(null));
                    pipelinePolylines = [];
                },

                renderIrrigation(irrigationData) {
                    this.clearIrrigation();
                    if (!irrigationData) return;

                    irrigationData.main_pipeline.forEach(path => {
                        pipelinePolylines.push(new google.maps.Polyline({
                            path: path, geodesic: true, strokeColor: '#1e40af', strokeOpacity: 0.9, strokeWeight: 5, map: this.showIrrigation ? map : null
                        }));
                    });

                    irrigationData.sub_pipelines.forEach(path => {
                        pipelinePolylines.push(new google.maps.Polyline({
                            path: path, geodesic: true, strokeColor: '#3b82f6', strokeOpacity: 0.6, strokeWeight: 2, map: this.showIrrigation ? map : null
                        }));
                    });
                },

                clearMarkers() {
                    if (markerCluster) {
                        markerCluster.clearMarkers();
                        if (typeof markerCluster.setMap === 'function') markerCluster.setMap(null);
                        markerCluster = null;
                    }
                    if (markers && markers.length > 0) {
                        markers.forEach(marker => marker.setMap(null));
                        markers = [];
                    }
                },

                renderMarkers(points) {
                    this.clearMarkers();

                    let iconColor = '#16a34a'; 
                    if (this.form.method === 'zigzag') iconColor = '#d97706'; 
                    if (this.form.method === 'random') iconColor = '#7c3aed'; 

                    const svgMarker = {
                        path: google.maps.SymbolPath.CIRCLE,
                        fillColor: iconColor,
                        fillOpacity: 1,
                        strokeWeight: 1.5,
                        strokeColor: '#ffffff',
                        scale: 4,
                    };

                    markers = points.map(point => {
                        return new google.maps.Marker({
                            position: { lat: point.lat, lng: point.lng },
                            icon: svgMarker,
                            title: `Plant Position`
                        });
                    });

                    if (markers.length > 300) {
                        markerCluster = new markerClusterer.MarkerClusterer({
                            map: this.showPlants ? map : null,
                            markers: markers,
                            renderer: {
                                render: ({ count, position }) => {
                                    return new google.maps.Marker({
                                        position,
                                        icon: {
                                            path: google.maps.SymbolPath.CIRCLE,
                                            fillColor: iconColor,
                                            fillOpacity: 0.85,
                                            strokeWeight: 2,
                                            strokeColor: '#ffffff',
                                            scale: Math.min(16 + (count/80), 32),
                                        },
                                        label: { text: String(count), color: 'white', fontSize: '12px', fontWeight: 'bold', fontFamily: 'Inter' },
                                        zIndex: Number(google.maps.Marker.MAX_ZINDEX) + count,
                                    });
                                }
                            }
                        });
                    } else {
                        markers.forEach(marker => marker.setMap(this.showPlants ? map : null));
                    }
                },

                async savePlan() {
                    if (!this.form.name) return alert("Please enter a plan name.");
                    this.isSaving = true;
                    try {
                        const response = await fetch('{{ route('plantation-plans.store') }}', {
                            method: 'POST',
                            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
                            body: JSON.stringify(this.form)
                        });
                        if (response.ok) {
                            window.location.reload();
                        } else {
                            const data = await response.json();
                            alert("Failed to save plan: " + (data.message || "Unknown error"));
                        }
                    } catch (error) {
                        alert("Network error while saving.");
                    } finally {
                        this.isSaving = false;
                    }
                },

                loadPlan(plan) {
                    this.clearMap();
                    
                    this.form.name = plan.name;
                    this.form.method = plan.method;
                    this.form.row_spacing = plan.row_spacing;
                    this.form.plant_spacing = plan.plant_spacing;
                    this.form.polygon_coordinates = typeof plan.polygon_coordinates === 'string' ? JSON.parse(plan.polygon_coordinates) : plan.polygon_coordinates;
                    
                    this.form.enable_irrigation = plan.irrigation_plan ? true : false;
                    if (plan.irrigation_plan) {
                        this.form.irrigation_type = plan.irrigation_plan.irrigation_type;
                        this.form.drippers_per_plant = plan.irrigation_plan.drippers_per_plant;
                        
                        this.form.custom_main_pipeline = typeof plan.irrigation_plan.main_pipeline === 'string'
                            ? JSON.parse(plan.irrigation_plan.main_pipeline)[0] || []
                            : plan.irrigation_plan.main_pipeline[0] || [];
                            
                        this.form.pipeline_routing = plan.irrigation_plan.main_pipeline && plan.irrigation_plan.main_pipeline[0] && plan.irrigation_plan.main_pipeline[0].length > 2 ? 'custom' : 'auto';
                        this.form.water_source_coordinates = typeof plan.irrigation_plan.water_source_coordinates === 'string' ? JSON.parse(plan.irrigation_plan.water_source_coordinates) : plan.irrigation_plan.water_source_coordinates;
                        this.renderWaterSource();
                    }

                    this.summary.area = plan.area;
                    this.summary.total_plants = plan.total_plants;
                    this.hasPolygon = true;

                    const paths = this.form.polygon_coordinates.map(p => new google.maps.LatLng(p.lat, p.lng));
                    polygon = new google.maps.Polygon({
                        paths: paths, fillColor: '#16a34a', fillOpacity: 0.15, strokeWeight: 3, strokeColor: '#16a34a', editable: true, map: map
                    });

                    const bounds = new google.maps.LatLngBounds();
                    paths.forEach(path => bounds.extend(path));
                    map.fitBounds(bounds);

                    drawingManager.setDrawingMode(null);

                    polygon.getPaths().forEach((path) => {
                        ['insert_at', 'remove_at', 'set_at'].forEach(evt => {
                            google.maps.event.addListener(path, evt, () => {
                                this.updatePolygonCoordinates(); this.calculatePreview();
                            });
                        });
                    });

                    this.calculatePreview();
                },

                async deletePlan(id) {
                    if (!confirm("Are you sure you want to delete this plan?")) return;
                    try {
                        const response = await fetch(`/plantation-plans/${id}`, { method: 'DELETE', headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' }});
                        if (response.ok) window.location.reload();
                    } catch (error) {
                        console.error("Network error:", error);
                    }
                },

                formatNumber(num, decimals = 0) {
                    return Number(num).toLocaleString('en-US', { minimumFractionDigits: decimals, maximumFractionDigits: decimals });
                },

                formatArea(sqMeters) { return this.formatNumber(sqMeters, 2); },

                calculateDensity() {
                    if (this.summary.area <= 0) return 0;
                    const acres = this.summary.area * 0.000247105;
                    return this.formatNumber(this.summary.total_plants / acres, 0);
                }
            }
        }
    </script>
</x-app-layout>
