<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=poppins:100,100i,200,200i,300,300i,400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" />
    <link href="https://cdn.jsdelivr.net/npm/remixicon@4.5.0/fonts/remixicon.css" rel="stylesheet" />

    <link href="https://cdn.jsdelivr.net/npm/ol@v10.5.0/ol.css" rel="stylesheet" />
    <link href="./css/style.css" rel="stylesheet" />

    <script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

    <style>
        #map {
            width: 100%;
        }
    </style>
</head>

<body class="min-h-screen antialiased font-urbane">
    <header class="bg-base-200">
        <div class="max-w-screen-xl px-4 mx-auto sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-[64px]">
                <div class="flex-1 md:flex md:items-center md:gap-12">
                    <a class="inline-flex items-center text-primary" href="/">
                        <span class="sr-only">Logo</span>
                        <img src="./img/logo.webp" class="h-auto max-h-12 w-auto max-w-12" alt="Logo">
                        <span class="ml-2 font-bold uppercase text-2xl">Web Name</span>
                    </a>
                </div>

                <div class="md:flex md:items-center md:gap-12">
                    <!-- <nav aria-label="Global" class="hidden md:block">
                            <ul class="flex items-center gap-6 text-sm">
                                <li>
                                    <a
                                        class="text-gray-500 transition hover:text-gray-500/75 dark:text-white dark:hover:text-white/75"
                                        href="#"
                                    >
                                        About
                                    </a>
                                </li>

                                <li>
                                    <a
                                        class="text-gray-500 transition hover:text-gray-500/75 dark:text-white dark:hover:text-white/75"
                                        href="#"
                                    >
                                        Careers
                                    </a>
                                </li>

                                <li>
                                    <a
                                        class="text-gray-500 transition hover:text-gray-500/75 dark:text-white dark:hover:text-white/75"
                                        href="#"
                                    >
                                        History
                                    </a>
                                </li>

                                <li>
                                    <a
                                        class="text-gray-500 transition hover:text-gray-500/75 dark:text-white dark:hover:text-white/75"
                                        href="#"
                                    >
                                        Services
                                    </a>
                                </li>

                                <li>
                                    <a
                                        class="text-gray-500 transition hover:text-gray-500/75 dark:text-white dark:hover:text-white/75"
                                        href="#"
                                    >
                                        Projects
                                    </a>
                                </li>

                                <li>
                                    <a
                                        class="text-gray-500 transition hover:text-gray-500/75 dark:text-white dark:hover:text-white/75"
                                        href="#"
                                    >
                                        Blog
                                    </a>
                                </li>
                            </ul>
                        </nav> -->

                    <!-- <div class="flex items-center gap-4">
                            <div class="sm:flex sm:gap-4">
                                <a
                                    class="rounded-md bg-secondary px-5 py-2.5 text-sm font-medium text-secondary-content shadow-sm"
                                    href="#"
                                >
                                    Login
                                </a>

                                <div class="hidden sm:flex">
                                    <a
                                        class="rounded-md bg-accent px-5 py-2.5 text-sm font-medium text-accent-content"
                                        href="#"
                                    >
                                        Register
                                    </a>
                                </div>
                            </div>

                            <div class="block md:hidden">
                                <button
                                    class="p-2 transition rounded-sm text-accent-content bg-accent hover:text-accent-content/75"
                                >
                                    <svg
                                        xmlns="http://www.w3.org/2000/svg"
                                        class="size-5"
                                        fill="none"
                                        viewBox="0 0 24 24"
                                        stroke="currentColor"
                                        stroke-width="2"
                                    >
                                        <path
                                            stroke-linecap="round"
                                            stroke-linejoin="round"
                                            d="M4 6h16M4 12h16M4 18h16"
                                        />
                                    </svg>
                                </button>
                            </div>
                        </div> -->
                </div>
            </div>
        </div>
    </header>

    <nav id="side-nav-panel" class="z-20 flex shrink-0 grow-0 justify-around gap-2.5 py-4 border-t border-gray-200 bg-white/50 p-1 shadow-lg backdrop-blur-lg fixed top-2/4 -translate-y-2/4 left-2 min-h-[auto] min-w-[auto] flex-col rounded-lg border">
        <button type="button" role="button" id="search-toggle" class="flex aspect-square min-h-[10px] w-12 flex-col items-center justify-center gap-0.5 rounded-md p-1.5 text-base-content hover:bg-base-300">
            <i class="ri-search-line text-2xl"></i>
            <small class="text-center text-xs font-medium"> Search </small>
        </button>
        <button type="button" role="button" id="layer-toggle" class="flex aspect-square min-h-[10px] w-12 flex-col items-center justify-center gap-0.5 rounded-md p-1.5 text-base-content hover:bg-base-300">
            <i class="ri-stack-line text-2xl"></i>
            <small class="text-center text-xs font-medium"> Layer </small>
        </button>
    </nav>

    <div class="map h-[calc(100vh-64px)]" id="map"></div>

    <div class="" id="topLeft"></div>

    <div class="" id="topRight"></div>

    <div class="" id="bottomLeft">
        <div class="flex items-end">
            <!-- Mouse Position -->
            <div class="relative mb-1" id="mousePosition"></div>

            <!-- Scaleline -->
            <div class="relative" id="scaleline"></div>

            <!-- Basemap -->
            <div class="basemap-switcher font-medium">
                <div class="trigger-basemap font-bold" onclick="toggleOptions()">
                    <img
                        id="active-basemap"
                        src="./img/icon/here_satelliteday.png"
                        alt="Active Basemap" />
                    <span class="block">Basemap</span>
                </div>
                <div class="basemap-options">
                    <label class="basemap-option">
                        <input
                            type="radio"
                            name="basemap"
                            value="bing"
                            checked
                            onclick="setBasemap('bing', this)" />
                        <img
                            src="./img/icon/here_satelliteday.png"
                            alt="Satellite" />
                        <span>Satellite</span>
                    </label>
                    <label class="basemap-option">
                        <input
                            type="radio"
                            name="basemap"
                            value="mapbox"
                            onclick="setBasemap('mapbox', this)" />
                        <img
                            src="./img/icon/here_normalday.png"
                            alt="Mapbox" />
                        <span>Street Mapbox</span>
                    </label>
                    <label class="basemap-option">
                        <input
                            type="radio"
                            name="basemap"
                            value="osm"
                            onclick="setBasemap('osm', this)" />
                        <img
                            src="./img/icon/openstreetmap_mapnik.png"
                            alt="OpenStreet" />
                        <span>OpenStreet Map</span>
                    </label>
                    <label class="basemap-option">
                        <input
                            type="radio"
                            name="basemap"
                            value="esriTerrain"
                            onclick="setBasemap('esriTerrain', this)" />
                        <img
                            src="./img/icon/esri_worldterrain.png"
                            alt="Esri Terrain" />
                        <span>Esri Terrain</span>
                    </label>
                </div>
            </div>
        </div>
    </div>

    <div class="" id="bottomRight">

        <!-- Zoom Toggle -->
        <div class="relative" id="zoomToggle"></div>

        <!-- Minimap -->
        <div class="relative" id="minimap"></div>

        <!-- attribution -->
        <div class="relative" id="attribution"></div>
    </div>

    <!-- Search Panel -->
    <div id="searchPanel" class="hidden z-21 left-panel flex shrink-0 grow-0 justify-around gap-2 py-2 border-t border-gray-200 bg-white p-1 shadow-lg backdrop-blur-lg fixed top-1/6 -translate-y-2/4 left-2 min-h-[auto] min-w-64 flex-col rounded-lg border h-18">
        <div class="px-3 py-1 gap-2 flex items-center justify-between">
            <input type="search" id="search-input-panel" class="w-full px-1.5 py-1 border rounded-md" placeholder="Cari nama bidang..." />
            <button class="text-sm font-extrabold close-panel hover:opacity-80">✕</button>
        </div>
    </div>

    <!-- Layer Panel -->
    <div id="layerPanel" class="hidden left-panel fixed top-1/2 left-18 -translate-y-1/2 bg-white border rounded-lg min-w-80 min-h-20 shadow-2xl z-19 max-h-[70vh]">
        <div class="px-3 border-b py-1 flex items-center justify-between">
            <p class="font-semibold">Layer Panel</p>
            <button class="text-sm font-extrabold close-panel hover:opacity-80">✕</button>
        </div>
        <div id="layerPanelContent" class="p-4 text-sm text-gray-700 space-y-2 overflow-y-auto max-h-[64vh]">
            <span class="font-semibold"><i class="ri-filter-2-line"></i> Filter</span>
            <div class="space-y-1 mb-2">
                <label class="block text-xs font-medium">Agama</label>
                <select id="filterAgama" class="w-full border rounded p-1 text-sm">
                    <option value="">Semua</option>
                    <option value="Islam">Islam</option>
                    <option value="Kristen">Kristen</option>
                    <option value="Budha">Budha</option>
                    <option value="Hindu">Hindu</option>
                    <option value="Kong Hu Cu">Konghucu</option>
                    <option value="null">-</option>
                </select>

                <label class="block text-xs font-medium">Tipe Hak</label>
                <select id="filterTipeHak" class="w-full border rounded p-1 text-sm">
                    <option value="">Semua</option>
                    <option value="Hak Milik">Hak Milik</option>
                    <option value="Hak Pakai">Hak Pakai</option>
                    <option value="Hak Guna Usaha">Hak Guna Usaha</option>
                    <option value="Hak Guna Bangunan">Hak Guna Bangunan</option>
                    <option value="Hak Sewa">Hak Sewa</option>
                    <option value="null">-</option>
                </select>

                <button id="applyFilterBtn" class="bg-primary hover:bg-primary/70 text-white px-2 py-1 rounded text-xs mt-2">Terapkan Filter</button>
            </div>
            <span class="font-semibold"><i class="ri-stack-line"></i> Blok Layer</span>
            <div class="">
            </div>
        </div>
    </div>


    <!-- Property Panel -->
    <div id="propertyPanel" class="absolute transition-all duration-300 ease-in-out hidden z-50 bg-white shadow-lg rounded-t-lg border border-t border-gray-200
    max-w-full w-full bottom-0 left-0 md:top-1/2 md:right-2 md:left-auto md:transform md:-translate-y-1/2 md:rounded-lg md:max-w-[28rem] md:bottom-auto overflow-hidden">
        <div class="bg-accent px-4 py-2 flex items-center justify-between">
            <h4 class="font-semibold">📄 Informasi Bidang</h4>
            <button onclick="closePropertyPanel()" class="text-sm hover:opacity-80 font-extrabold">✕</button>
        </div>
        <div id="propertyPanelContent" class="max-h-[22rem] overflow-y-auto p-4 text-sm text-gray-700 space-y-2">
            <p>Memuat data...</p>
        </div>
    </div>


    <script src="https://cdn.jsdelivr.net/npm/ol@v10.5.0/dist/ol.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.11.0/proj4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@turf/turf@7/turf.min.js"></script>

    <script src="./js/map.js"></script>
</body>

</html>