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
                    <a class="inline-flex items-start text-primary" href="/">
                        <span class="sr-only">Logo</span>
                        <img src="./img/logo.webp" class="h-auto max-h-12 w-auto max-w-12" alt="Logo">
                        <div class="ml-2">
                            <span class="font-bold uppercase text-2xl block">SIGAP-HAPAT</span>
                            <span class="text-xs block">Sistem Informasi Geospasial Hak dan Pajak Atas Tanah</span>
                        </div>
                    </a>

                </div>

                <div class="flex items-center gap-3">
                    <a class="rounded-md bg-primary px-2.5 py-1.5 font-semibold text-white shadow-sm hover:bg-primary/75 transition"
                        href="https://s.id/testkedaton"
                        target="_blank">
                        Uji Usability
                    </a>
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

    <!-- Alert Info -->
    <div id="alert-info" class="fixed inset-x-0 top-20 md:top-10 mx-auto w-fit z-50 hidden opacity-0 -translate-y-5 transition-all duration-500">
        <div id="alert-box" class="flex items-center gap-3 rounded-lg px-4 py-3 text-sm shadow-lg ring-1">
            <svg id="alert-icon" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20"></svg>
            <span id="alert-message"></span>
        </div>
    </div>

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
    <div id="searchPanel" class="hidden z-21 left-panel flex flex-col gap-2 py-2 border-t border-gray-200 bg-white p-2 shadow-lg backdrop-blur-lg fixed top-1/5 -translate-y-2/4 left-2 min-h-[auto] min-w-64 rounded-lg border">
        <div class="px-3 py-1 flex items-center justify-between">
            <p class="font-semibold">Pencarian Data</p>
            <button class="text-sm font-extrabold close-panel hover:opacity-80">✕</button>
        </div>

        <div class="relative flex items-center">
            <select id="searchField" class="absolute left-0 h-full border-r border-gray-300 text-xs rounded-l-md px-2 w-30 bg-white">
                <option value="nama_pemilik">Nama Pemilik</option>
                <option value="nama_wajib_pajak" class="truncate">nama wajib pajak</option>
                <option value="nib">NIB</option>
                <option value="nop">NOP</option>
            </select>
            <input type="search" id="searchValue" class="w-full pl-32 pr-3 py-2 border text-sm rounded-md focus:outline-none focus:border-gray-400" placeholder="Ketik kata kunci..." />
            <ul id="suggestions" class="absolute top-full left-32 z-50 bg-white border border-gray-300 rounded w-64 mt-1 hidden max-h-40 overflow-y-auto text-sm"></ul>
        </div>
        <button id="searchButton" class="mt-1 px-1.5 py-1 bg-primary text-white text-sm rounded hover:bg-primary/75">Cari</button>
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

                <button id="applyFilterButton" class="bg-primary hover:bg-primary/70 text-white px-2 py-1 rounded text-xs mt-2">Terapkan Filter</button>
            </div>
            <span class="font-semibold"><i class="ri-stack-line"></i> Blok Layer</span>
            <div class="">
            </div>
        </div>
    </div>


    <!-- Property Panel -->
    <div id="propertyPanel" class="absolute transition-all duration-300 ease-in-out hidden z-50 bg-white shadow-lg rounded-t-lg border border-t border-gray-200
    max-w-full w-full bottom-0 left-0 md:top-1/2 md:right-2 md:left-auto md:transform md:-translate-y-1/2 md:rounded-lg md:max-w-[28rem] md:bottom-auto overflow-hidden">
        <div class="bg-accent px-4 py-1 flex items-center justify-between">
            <h4 class="font-semibold">📄 Informasi Bidang</h4>
            <button onclick="closePropertyPanel()" class="text-sm hover:opacity-80 font-extrabold">✕</button>
        </div>
        <div class="flex space-x-2 p-2">
            <button
                class="tab-btn px-4 py-2 text-sm font-medium rounded-md bg-blue-500 text-white focus:outline-none"
                data-target="#tab-percil">
                Data Hak
            </button>
            <button
                class="tab-btn px-4 py-2 text-sm font-medium rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300 focus:outline-none"
                data-target="#tab-wp">
                Data Wajib Pajak
            </button>
            <button
                class="tab-btn px-4 py-2 text-sm font-medium rounded-md bg-gray-200 text-gray-700 hover:bg-gray-300 focus:outline-none"
                data-target="#tab-lokasi">
                Lokasi
            </button>
        </div>
        <div id="propertyPanelContent" class="max-h-[22rem] overflow-y-auto p-4 text-sm text-gray-700 space-y-2">
            <div id="tab-coord">
                <p>Memuat data...</p>
            </div>

            <div id="tab-percil" class="tab-content">
                <div class="">
                    <p>Memuat data...</p>
                </div>
            </div>

            <div id="tab-wp" class="tab-content hidden">
                <div class="">
                    <p>Memuat data...</p>
                </div>
            </div>

            <div id="tab-lokasi" class="tab-content hidden">
                <div class="">
                    <p>Memuat data...</p>
                </div>
            </div>

        </div>
    </div>



    <!-- Welcome Modal -->
    <div id="welcomeModal" class="fixed inset-0 z-[100] items-center justify-center bg-black/60 hidden opacity-0 transition-opacity duration-500 p-4">
        <div class="relative w-full max-w-lg bg-white rounded-xl shadow-2xl transform scale-95 transition-transform duration-500 opacity-0 max-h-[90vh] overflow-y-auto flex flex-col" id="welcomeContent">

            <!-- Header -->
            <div class="relative bg-primary px-5 py-4 shrink-0">
                <div class="relative z-10 text-center text-white">
                    <h2 class="text-xl md:text-2xl font-bold mb-0.5 tracking-tight">SIGAP – HAPAT</h2>
                    <p class="text-white/90 text-xs md:text-sm font-medium">(Sistem Informasi Geospasial Hak & Pajak Atas Tanah)</p>
                </div>
            </div>

            <!-- Content -->
            <div class="p-5 md:p-6 overflow-y-auto">
                <div class="text-center mb-4 md:mb-5">
                    <h3 class="text-base md:text-lg font-bold text-gray-800 leading-snug">
                        “Transformasi dan Integrasi Digital <br class="hidden md:block" />
                        Pengelolaan Data Pertanahan Hak dan Pajak Atas Tanah"
                    </h3>
                    <div class="h-1 w-16 bg-primary/30 mx-auto mt-3 rounded-full"></div>
                </div>

                <div class="space-y-3 text-gray-600 text-xs md:text-sm text-justify md:text-center leading-relaxed">
                    <p>
                        Selamat datang di platform terintegrasi untuk Sistem Informasi
                        yang menyatukan kepastian Hak Atas Tanah dengan akurasi Pajak Atas Tanah.
                    </p>
                    <p>
                        Kami hadir untuk menghilangkan sekat antara data legalitas hak dan data fiskal atas tanah, mewujudkan pengelolaan data pertanahan yang lebih transparan, akuntabel, dan presisi dalam satu peta digital.
                    </p>
                </div>

                <div class="mt-5 p-3.5 bg-gray-50 border border-gray-100 rounded-lg text-center">
                    <p class="text-gray-700 text-xs md:text-sm font-medium">
                        Untuk memudahkan dalam monitoring, pengelolaan, dan validasi aset tanah
                        serta kewajiban pajak berbasis peta digital.
                    </p>
                </div>
            </div>

            <!-- Footer -->
            <div class="px-5 py-4 bg-gray-50 border-t border-gray-100 flex flex-col md:flex-row items-center justify-between gap-3 shrink-0 rounded-b-xl">
                <label class="flex items-center space-x-2 cursor-pointer group select-none">
                    <input type="checkbox" id="dontShowAgain" class="checkbox checkbox-primary checkbox-xs md:checkbox-sm rounded">
                    <span class="text-xs text-gray-500 group-hover:text-gray-700 transition-colors">Jangan tampilkan lagi selama 7 hari</span>
                </label>

                <button id="closeWelcomeBtn" class="w-full md:w-auto px-6 py-2 bg-primary hover:bg-primary/90 text-white text-sm font-semibold rounded-lg shadow-md transition-all duration-200 hover:-translate-y-0.5 active:scale-95">
                    Mulai Jelajahi
                </button>
            </div>
        </div>
    </div>

    <!-- Welcome Modal Logic -->
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const modal = document.getElementById('welcomeModal');
            const content = document.getElementById('welcomeContent');
            const closeBtn = document.getElementById('closeWelcomeBtn');
            const checkbox = document.getElementById('dontShowAgain');

            // Key for localStorage
            const STORAGE_KEY = 'sigap_welcome_hide_until';

            // Check if should show
            const dismissedUntil = localStorage.getItem(STORAGE_KEY);
            const now = new Date().getTime();

            if (!dismissedUntil || now > parseInt(dismissedUntil)) {
                // Show modal
                modal.classList.remove('hidden');
                modal.classList.add('flex');

                // Small delay to allow display:flex to apply before opacity transition
                requestAnimationFrame(() => {
                    modal.classList.remove('opacity-0');
                    content.classList.remove('opacity-0', 'scale-95');
                    content.classList.add('scale-100');
                });
            }

            closeBtn.addEventListener('click', () => {
                if (checkbox.checked) {
                    const days = 7;
                    const expiry = new Date().getTime() + (days * 24 * 60 * 60 * 1000);
                    localStorage.setItem(STORAGE_KEY, expiry);
                }

                // Animate out
                modal.classList.add('opacity-0');
                content.classList.remove('scale-100');
                content.classList.add('scale-95');

                setTimeout(() => {
                    modal.classList.remove('flex');
                    modal.classList.add('hidden');
                }, 500);
            });
        });
    </script>

    <script src=" https://cdn.jsdelivr.net/npm/ol@v10.5.0/dist/ol.js">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/proj4js/2.11.0/proj4.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@turf/turf@7/turf.min.js"></script>

    <script src="./js/scripts.js"></script>
    <script src="./js/map.js"></script>
</body>

</html>