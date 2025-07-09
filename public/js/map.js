const Map = ol.Map;
const View = ol.View;
const TileLayer = ol.layer.Tile;
const VectorLayer = ol.layer.Vector;
const VectorSource = ol.source.Vector;
const Feature = ol.Feature;
const { OSM, BingMaps, XYZ, ImageTile } = ol.source;
const { fromLonLat, transformExtent, toLonLat, Projection, useGeographic, getProjection, getTransform, addCoordinateTransforms, addProjection, transform } = ol.proj;
const Layer = ol.layer.WebGLTile;
const Source = ol.source.ImageTile;
const LayerGroup = ol.layer.Group;
const Overlay = ol.Overlay;
const { GeoJSON, KML, WKB } = ol.format;
const { Point, Circle, LineString, Polygon } = ol.geom;
const { Circle: CircleStyle, Style, Fill, Stroke, Text, IconImage, RegularShape, Icon } = ol.style;
const { Attribution, OverviewMap, ScaleLine, MousePosition } = ol.control;
const { register } = ol.proj.proj4;
const { toStringHDMS, toStringXY } = ol.coordinate;

proj4.defs("EPSG:23836", "+proj=tmerc +lat_0=0 +lon_0=112.5 +k=0.9999 +x_0=200000 +y_0=1500000 +ellps=WGS84 +towgs84=0,0,0,0,0,0,0 +units=m +no_defs +type=crs");
register(proj4);

// BaseMap
const osmBaseMap = new TileLayer({
    source: new OSM(),
    crossOrigin: "anonymous",
    visible: false,
    preload: 15,
});

const sourceBingMaps = new BingMaps({
    key: "AjQ2yJ1-i-j_WMmtyTrjaZz-3WdMb2Leh_mxe9-YBNKk_mz1cjRC7-8ILM7WUVEu",
    imagerySet: "AerialWithLabels",
    maxZoom: 20,
});
const bingAerialBaseMap = new TileLayer({
    preload: Infinity,
    source: sourceBingMaps,
    crossOrigin: "anonymous",
    visible: true,
});

const mapboxBaseURL = "https://api.mapbox.com/styles/v1/{id}/tiles/{z}/{x}/{y}?access_token=pk.eyJ1IjoiNjg2MzUzMyIsImEiOiJjbDh4NDExZW0wMXZsM3ZwODR1eDB0ajY0In0.6jHWxwN6YfLftuCFHaa1zw";
const mapboxStyleId = "mapbox/streets-v11";
const mapboxSource = new XYZ({
    url: mapboxBaseURL.replace("{id}", mapboxStyleId),
});
const mapboxBaseMap = new TileLayer({
    source: mapboxSource,
    crossOrigin: "anonymous",
    visible: false,
    preload: 15,
});

const esriMap = new TileLayer({
    source: new Source({
        attributions: 'Tiles © <a href="https://services.arcgisonline.com/ArcGIS/' + 'rest/services/World_Topo_Map/MapServer">ArcGIS</a>',
        url: "https://server.arcgisonline.com/ArcGIS/rest/services/" + "World_Topo_Map/MapServer/tile/{z}/{y}/{x}",
    }),
    crossOrigin: "anonymous",
    visible: false,
});

const baseMaps = [osmBaseMap, bingAerialBaseMap, mapboxBaseMap, esriMap];

// Minimap
const overviewMapControl = new OverviewMap({
    layers: [
        new TileLayer({
            source: new OSM(),
        }),
    ],
    target: document.getElementById("minimap"),
    className: "ol-overviewmap ol-custom-overviewmap",
    collapsed: false,
    tipLabel: "Minimap",
    collapseLabel: "\u00BB",
    label: "\u00AB",
});

// Attribution
const attribution = new Attribution({
    target: document.getElementById("attribution"),
    collapsible: true,
    className: "ol-attribution",
});

// ScaleLine
const scaleControl = new ScaleLine({
    target: document.getElementById("scaleline"),
    units: "metric",
    bar: true,
    steps: 4,
    text: true,
    minWidth: 140,
    maxWidth: 180,
    className: "ol-scale-line",
});

// zoom
const zoomControl = new ol.control.Zoom({
    target: document.getElementById("zoomToggle"),
    className: "ol-custom-zoom",
    zoomInClassName: "btn ol-custom-zoom-in",
    zoomOutClassName: "btn ol-custom-zoom-out",
    zoomInLabel: "",
    zoomOutLabel: "",
});

// Mouse Position
const mousePositionControl = new MousePosition({
    target: document.getElementById("mousePosition"),
    coordinateFormat: function (coordinate) {
        const { formattedLon, formattedLat } = coordinateFormatIndo(coordinate, "dd");

        return "Long: " + formattedLon + " &nbsp&nbsp&nbsp  Lat: " + formattedLat;
    },
    projection: "EPSG:4326",
    placeholder: "Long: - &nbsp&nbsp&nbsp  Lat: -",
    className: "ol-custom-mouse-position",
});

/**
 * Formats the given coordinate into a specific format for Indo coordinates.
 *
 * @param {Array<number>} coordinate - The coordinate to be formatted. It should be an array with two elements: [longitude, latitude].
 * @param {string} [format="dd"] - The format to use for the coordinate. It can be "dd" for decimal degrees, or "dms" for degrees, minutes, and seconds.
 * @return {Object} An object containing the formatted longitude and latitude.
 * @example
 * dd=> {"formattedLon": "112.74719° BT", "formattedLat": "7.26786° LS"}
 * or
 * dms=> {"formattedLon": "112° 47' 17.00\" BT", "formattedLat": "7° 24' 46.00\" LS"}
 */
function coordinateFormatIndo(coordinate, format = "dd") {
    const lon = coordinate[0];
    const lat = coordinate[1];

    const lonDirection = lon < 0 ? "BB" : "BT";
    const latDirection = lat < 0 ? "LS" : "LU"; // LS: Lintang Selatan, LU: Lintang Utara

    if (format === "dms") {
        const convertToDMS = (coord, direction) => {
            const absoluteCoord = Math.abs(coord);
            const degrees = Math.floor(absoluteCoord);
            const minutes = Math.floor((absoluteCoord - degrees) * 60);
            const seconds = ((absoluteCoord - degrees - minutes / 60) * 3600).toFixed(2);
            return `${degrees}° ${minutes}' ${seconds}" ${direction}`;
        };
        const formattedLon = convertToDMS(lon, lonDirection);
        const formattedLat = convertToDMS(lat, latDirection);
        return {
            formattedLon,
            formattedLat,
        };
    } else {
        const formattedLon = `${Math.abs(lon).toFixed(5)}° ${lonDirection}`;
        const formattedLat = `${Math.abs(lat).toFixed(5)}° ${latDirection}`;
        return {
            formattedLon,
            formattedLat,
        };
    }
}

// View map
const view = new View({
    projection: "EPSG:3857",
    center: fromLonLat([111.91907902336122, -7.203098192491574]),
    zoom: 16,
});

// init map
const map = new Map({
    layers: [
        new LayerGroup({
            layers: baseMaps,
        }),
    ],

    target: "map",

    view: view,

    controls: [zoomControl, scaleControl, overviewMapControl, attribution, mousePositionControl],
});

map.getViewport().style.cursor = "grab";

window.addEventListener("resize", () => {
    if (window.innerWidth <= 768) {
        map.removeControl(mousePositionControl);
    } else {
        if (!map.getControls().getArray().includes(mousePositionControl)) {
            map.addControl(mousePositionControl);
        }
    }
});

function setBasemap(mapType, element = null) {
    document.getElementById("active-basemap").src = element?.nextElementSibling?.src ?? element?.querySelector("img")?.src;
    if (mapType === "osm") {
        setOsmBasemap();
    } else if (mapType === "bing") {
        setBingmapBasemap();
    } else if (mapType === "mapbox") {
        setMapboxBasemap();
    } else if (mapType === "esriTerrain") {
        setEsriBasemap();
    }

    localStorage.setItem("basemap", mapType);
}

function toggleOptions() {
    document.querySelector(".basemap-options").classList.toggle("flex");
}

function initBasemap() {
    const savedBasemap = localStorage.getItem("basemap");
    if (savedBasemap) {
        const element = document.querySelector(`input[name='basemap'][value='${savedBasemap}']`).parentElement;
        setBasemap(savedBasemap, element);
    } else {
        const checkedInput = document.querySelector("input[name='basemap']:checked");
        setBasemap(checkedInput.value, checkedInput.parentElement);
    }
}
initBasemap();

function setOsmBasemap() {
    osmBaseMap.setVisible(true);
    bingAerialBaseMap.setVisible(false);
    mapboxBaseMap.setVisible(false);
    esriMap.setVisible(false);
}

function setBingmapBasemap() {
    osmBaseMap.setVisible(false);
    bingAerialBaseMap.setVisible(true);
    mapboxBaseMap.setVisible(false);
    esriMap.setVisible(false);
}

function setMapboxBasemap() {
    osmBaseMap.setVisible(false);
    bingAerialBaseMap.setVisible(false);
    mapboxBaseMap.setVisible(true);
    esriMap.setVisible(false);
}

function setEsriBasemap() {
    osmBaseMap.setVisible(false);
    bingAerialBaseMap.setVisible(false);
    mapboxBaseMap.setVisible(false);
    esriMap.setVisible(true);
}

/**
 * Style for different geometries
 *
 * This style defines the appearance of features in the map. It is used for
 * points, lines and polygons.
 *
 * @type {Style}
 */
const pointStyle = new Style({
    image: new Icon({
        anchor: [0.5, 0.99],
        anchorXUnits: "fraction",
        anchorYUnits: "fraction",
        with: 50,
        height: 50,
        opacity: 0.9,
        src: "./img/icon/marker.svg",
    }),
});
const lineStyle = new Style({
    stroke: new Stroke({
        color: "red",
        width: 2,
    }),
});
const polygonStyle = new Style({
    stroke: new Stroke({
        color: "red",
        width: 1,
    }),
    fill: new Fill({
        color: "rgba(255, 0, 0, 0.3)",
    }),
});
const pointInDraw = new Style({
    fill: new Fill({
        color: "rgba(255, 255, 255, 0.2)",
    }),
    stroke: new Stroke({
        color: "rgba(0, 0, 0, 0.5)",
        lineDash: [10, 10],
        width: 2,
    }),
    image: new CircleStyle({
        radius: 5,
        stroke: new Stroke({
            color: "rgba(0, 0, 0, 0.7)",
        }),
        fill: new Fill({
            color: "rgba(255, 255, 255, 0.2)",
        }),
    }),
});
const selectedStyle = new Style({
    fill: new Fill({
        color: "rgba(255, 255, 0, 0.4)",
    }),
    stroke: new Stroke({
        color: "#ffff00",
        width: 2,
    }),
});
// color map by blok
const blokColors = {
    1: "rgba(255, 99, 132, 0.5)",
    2: "rgba(54, 162, 235, 0.5)",
    3: "rgba(75, 192, 192, 0.5)",
    4: "rgba(255, 206, 86, 0.5)",
    5: "rgba(153, 102, 255, 0.5)",
    6: "rgba(255, 159, 64, 0.5)",
    7: "rgba(100, 255, 100, 0.5)",
    8: "rgba(200, 100, 200, 0.5)",
    9: "rgba(100, 100, 255, 0.5)",
    "-": "rgba(255, 0, 0, 0.5)",
    default: "rgba(255, 0, 0, 0.5)",
};
function getBlokColor(blok) {
    return blokColors[blok] || blokColors["default"];
}
const getPolygonStyle = (feature) => {
    const blok = feature.get("blok");
    if (!blokVisibility[blok]) return null;
    const fillColor = getBlokColor(blok);
    return new Style({
        stroke: new Stroke({
            color: "#333",
            width: 1,
        }),
        fill: new Fill({
            color: fillColor,
        }),
    });
};

const drawingRunning = false;

/**
 * Function to choose the style based on geometry type.
 *
 * @param {module:ol/Feature~Feature} feature The feature to get the style for.
 * @return {module:ol/style/Style~Style} The style for the given feature.
 */
const getStyle = (feature) => {
    const geometry = feature.getGeometry();
    if (!geometry) return null;
    const type = geometry.getType();

    if (type === "Point") {
        return drawingRunning ? pointInDraw : pointStyle;
    } else if (type === "LineString") {
        return lineStyle;
    } else if (type === "Polygon" || type === "MultiPolygon") {
        return getPolygonStyle(feature);
    }
    return null;
};

/**
 * Changes the style of all features from KML/KMZ to the one defined in getStyle function.
 *
 * @param {module:ol/Feature~Feature[]} features The array of features to change the style of.
 */
const styleKMLKMZ = (features) => {
    features.forEach((feature) => {
        feature.setStyle(getStyle(feature));
    });
};

/**
 * Marks a clicked position on the map.
 *
 * @param {ol.Coordinate} coordinate - The coordinate of the clicked position.
 * @return {void}
 */
function markToClickedPosition(coordinate) {
    const marker = new Feature({
        geometry: new Point(coordinate),
    });
    if (vectorSourceEventClick) {
        vectorSourceEventClick.clear();
    }
    vectorLayerEventClick.getSource().addFeatures([marker]);
}

/**
 * Clears the clicked position marker on the map.
 *
 * @return {void}
 */
function clearMarkerClickedPosition() {
    if (vectorSourceEventClick) {
        vectorSourceEventClick.clear();
    }
}

function closePropertyPanel() {
    $("#propertyPanel").addClass("hidden");
    clearMarkerClickedPosition();
    vectorSourcePercil.getFeatures().forEach((f) => f.setStyle(getStyle));
}

const vectorSourceEventClick = new VectorSource();
const vectorLayerEventClick = new VectorLayer({
    source: vectorSourceEventClick,
    style: getStyle,
    zIndex: 99,
});
map.addLayer(vectorLayerEventClick);

map.on("pointermove", function (evt) {
    let found = false;
    map.forEachFeatureAtPixel(evt.pixel, function (feature, layer) {
        if (layer === vectorLayerPercil) {
            found = true;
            map.getViewport().style.cursor = "pointer";
        }
    });
    if (!found) {
        map.getViewport().style.cursor = "grab";
    }
});

map.on("singleclick", eventClickMap);

function eventClickMap(evt) {
    let viewResolution = /** @type {number} */ (view.getResolution());
    let projection = view.getProjection();

    let found = false;

    // Get Coordinate
    const coordinate = evt.coordinate;
    const LonLatcoordinate = toLonLat(coordinate, projection);
    const { formattedLon, formattedLat } = coordinateFormatIndo(LonLatcoordinate, "dms");
    const hdmsCoordinate = `${formattedLon} &nbsp ${formattedLat}`;

    // Reset style semua feature ke default
    vectorSourcePercil.getFeatures().forEach((f) => {
        f.setStyle(getStyle);
    });

    map.forEachFeatureAtPixel(evt.pixel, function (feature, layer) {
        if (layer === vectorLayerPercil) {
            found = true;

            $("#propertyPanel").removeClass("hidden");

            // Set style khusus untuk feature terpilih
            feature.setStyle(selectedStyle);

            markToClickedPosition(coordinate);

            const props = feature.getProperties();

            // Buat konten tabel info
            let tableContent = `
            <table class="table-auto w-full border border-accent text-sm">
                <tbody>
                    <tr class="border-b bg-base-300">
                        <th class="px-2 py-1 text-left">Koordinat Klik</th>
                        <td class="px-2 py-1">${hdmsCoordinate}</td>
                    </tr>
            `;
            // Loop semua property, kecuali geometry
            for (const [key, value] of Object.entries(props)) {
                if (key === "geometry") continue;

                tableContent += `
                <tr class="border-b">
                    <th class="px-2 border py-1 text-left bg-base-100 uppercase">${key.replace(/_/g, " ")}</th>
                    <td class="px-2 py-1">${value || "-"}</td>
                </tr>
            `;
            }
            tableContent += `
                </tbody>
            </table>
            `;

            $("#propertyPanelContent").html(tableContent);

            return true; // berhenti setelah fitur pertama
        }
    });

    // Jika klik di area kosong, hapus point & reset style
    if (!found) {
        clearMarkerClickedPosition();
        $("#propertyPanel").addClass("hidden");
    }
}

const hexToArrayBuffer = (hex) => {
    const bytes = new Uint8Array(hex.length / 2);
    for (let i = 0; i < hex.length; i += 2) {
        bytes[i / 2] = parseInt(hex.substr(i, 2), 16);
    }
    return bytes.buffer;
};

const vectorSourcePercil = new VectorSource();
const vectorLayerPercil = new VectorLayer({
    source: vectorSourcePercil,
    style: getStyle,
    zIndex: 10,
});

const format = new WKB();

const getPercil = async (agama = "", tipeHak = "") => {
    try {
        myAlert.show("getting", "Sedang memuat data...", 1500);
        const response = await fetch(`action/getPercil.php?agama=${agama}&tipeHak=${tipeHak}`);
        if (!response.ok) {
            myAlert.show("error", "Gagal mengambil data", 1500);
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        myAlert.show("success", "Data persil berhasil dimuat", 1500);
        return data;
    } catch (error) {
        console.error("Failed to fetch percil data:", error);
        return null;
    }
};

let blokVisibility = {};

let features = [];
async function loadPercilData(agama = "", tipeHak = "") {
    vectorSourcePercil.clear();
    closePropertyPanel();
    blokVisibility = {}; // reset blok toggle

    const data = await getPercil(agama, tipeHak);

    data.forEach((item) => {
        try {
            const blok = item["Blok"] || "default";

            if (!(blok in blokVisibility)) {
                blokVisibility[blok] = true;
            }

            const arrayBuffer = hexToArrayBuffer(item.Geometry);
            const feature = format.readFeature(arrayBuffer, {
                dataProjection: "EPSG:23836",
                featureProjection: "EPSG:3857",
            });
            feature.setProperties({
                blok: item["Blok"] || "-",
                nama_pemilik: item["Nama Pemilik"] || "-",
                nama_wajib_pajak: item["Nama Wajib Pajak"] || "-",
                tanggal_lahir: item["Tanggal Lahir"] || "-",
                tanggal_lahir_wp: item["Tanggal Lahir WP"] || "-",
                pekerjaan: item["Pekerjaan"] || "-",
                pekerjaan_wp: item["Pekerjaan WP"] || "-",
                agama: item["Agama"] || "-",
                alamat: item["Alamat"] || "-",
                alamat_wp: item["Alamat WP"] || "-",
                "kelurahan/desa": item["Desa"] || null,
                kecamatan: item["Kecamatan"] || null,
                kabupaten: item["Kabupaten"] || null,
                provinsi: item["Provinsi"] || null,
                penggunaan: item["Penggunaan"] || "-",
                nib: item["NIB"] || "-",
                nik: item["NIK"] || "-",
                nik_wp: item["NIK WP"] || "-",
                tahun_perolehan: item["Tahun Perolehan"] || "-",
                tanggal_berkas: item["Tanggal Berkas"] || "-",
                tahun_sptt: item["Tahun SPPT"] || "-",
                luas: item["Luas"] || "-",
                tipe_hak: item["Tipe Hak"] || "-",
                nop: item["NOP"] || "-",
                njop: item["NJOP"] || "-",
                bukti_perolehan: item["Buti Perolehan"],
                dasar_perolehan: item["Dasar Perolehan"],
            });

            vectorSourcePercil.addFeature(feature);
        } catch (error) {
            console.error("Gagal memproses WKB:", error);
        }
    });

    generateBlokToggle();
    map.addLayer(vectorLayerPercil);
}

loadPercilData();

function generateBlokToggle() {
    let html = "";
    Object.keys(blokVisibility).forEach((blok) => {
        html += `
        <label class="p-1 block">
          <input type="checkbox" class="blok-toggle" data-blok="${blok}" checked>
          Blok ${blok}
        </label>
    `;
    });
    $("#layerPanelContent>div:last-child").html(html);
}

$("body").on("change", ".blok-toggle", function () {
    const blok = $(this).data("blok");
    blokVisibility[blok] = $(this).is(":checked");
    // console.log("Blok", blok, "status:", blokVisibility[blok]); // cek trigger
    vectorSourcePercil.getFeatures().forEach((f) => f.setStyle(getStyle(f)));
});

$("#applyFilterBtn").click(async function () {
    const agama = $("#filterAgama").val();
    const tipeHak = $("#filterTipeHak").val();
    // Panggil ulang data dengan filter parameter
    await loadPercilData(agama, tipeHak);
});
