//Digunakan untuk memanggil modul openlayers dan penampilan peta
// import OSM from 'ol/source/OSM';
import 'ol/ol.css';
import { Map, View } from 'ol';
import TileLayer from 'ol/layer/Tile';
import XYZ from 'ol/source/XYZ';
import { fromLonLat } from 'ol/proj';

import Feature from 'ol/Feature';
import Point from 'ol/geom/Point';
import Circle from 'ol/geom/Circle';
import { Style, Icon, Stroke, Fill, Circle as CircleStyle } from 'ol/style';
import VectorSource from 'ol/source/Vector';
import VectorLayer from 'ol/layer/Vector';
import GeoJSON from 'ol/format/GeoJSON';
import { getDistance } from 'ol/sphere';
import { toLonLat } from 'ol/proj';

window.map = function () {
    return {
        legendOpened: false,
        map: null,
        userLocationLayer: null,
        userLocationFeature: null,

        init() {
            if (!this.$refs.map) return;

            this.map = new Map({
                target: this.$refs.map,
                layers: [
                    new TileLayer({
                        source: new XYZ({
                            attributions: 'Tiles © Esri',
                            url: 'https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{x}'
                        }),
                        label: 'ESRI Satellite'
                    })
                ],
                view: new View({
                    center: fromLonLat([
                        Number(window.kecamatanCenter.lon),
                        Number(window.kecamatanCenter.lat)
                    ]),
                    zoom: 7
                })
            });

            window.mapObj = this;

            this.addBoundaryLayer();
            this.addBTSLayers();

            // Animasi zoom setelah layer selesai load
            setTimeout(() => {
                this.map.getView().animate({
                    center: fromLonLat([
                        Number(window.kecamatanCenter.lon),
                        Number(window.kecamatanCenter.lat)
                    ]),
                    zoom: 13,
                    duration: 800
                });
            }, 400);
        },

        addBoundaryLayer() {
            fetch(window.kecamatanGeoJSON)
                .then(response => response.json())
                .then(geojsonData => {
                    const source = new VectorSource({
                        features: new GeoJSON().readFeatures(geojsonData, {
                            featureProjection: 'EPSG:3857'
                        })
                    });
        
                    const geojsonLayer = new VectorLayer({
                        source: source,
                        style: new Style({
                            stroke: new Stroke({ color: 'yellow', width: 2 }),
                            fill: new Fill({ color: 'rgba(255, 0, 0, 0)' })
                        }),
                        zIndex: 1,
                        label: 'Batas Kecamatan'
                    });
        
                    this.map.addLayer(geojsonLayer);
        
                    // 🔥 Tambahkan callback ini
                    source.on('change', () => {
                        if (source.getState() === 'ready') {
                            this.map.getView().animate({
                                center: fromLonLat([
                                    Number(window.kecamatanCenter.lon),
                                    Number(window.kecamatanCenter.lat)
                                ]),
                                zoom: 13,
                                duration: 800
                            });
                        }
                    });
                });
        },        

        addBTSLayers() {
            const operatorStyles = {
                OP1: {
                    src: '/img/Telkomsel.png',
                    scale: 0.04,
                    label: 'Telkomsel',
                    coverageColor: 'rgba(255, 0, 0, 0)',
                    coverageStroke: 'rgba(255, 0, 0, 0.6)'
                },
                OP2: {
                    src: '/img/indosat3dd.png',
                    scale: 0.03,
                    label: 'Indosat',
                    coverageColor: 'rgba(255, 255, 0, 0)',
                    coverageStroke: 'rgba(255, 255, 0, 0.6)'
                },
            };
        
            let markerSources = {};
            let coverageSources = {};
        
            // Siapkan sumber per operator
            Object.keys(operatorStyles).forEach(op => {
                markerSources[op] = new VectorSource();
                coverageSources[op] = new VectorSource();
            });
        
            window.btsData.forEach(bts => {
                const operator = bts.Kode_operator;
                const styleConfig = operatorStyles[operator] || {
                    src: '/img/default.png',
                    scale: 0.1,
                    label: 'Lainnya',
                    coverageColor: 'rgba(0, 123, 255, 0.2)',
                    coverageStroke: 'rgba(0, 123, 255, 0.6)'
                };
        
                const coord = fromLonLat([parseFloat(bts.Longitude), parseFloat(bts.Latitude)]);
                const jangkauanKm = bts.jenis_jaringan === '3G' ? 5 :
                                    bts.jenis_jaringan === '4G' ? 3 :
                                    bts.jenis_jaringan === '5G' ? 2 : null;
        
                // Marker BTS
                const marker = new Feature({
                    geometry: new Point(coord),
                    name: bts.nama_BTS,
                    alamat: bts.alamat,
                    jenis_jaringan: bts.jenis_jaringan,
                    jangkauan_sinyal: jangkauanKm,
                    operator: styleConfig.label
                });
        
                marker.setStyle(new Style({
                    image: new Icon({
                        anchor: [0.5, 1],
                        src: styleConfig.src,
                        scale: styleConfig.scale
                    })
                }));
        
                markerSources[operator].addFeature(marker);
        
                // Jangkauan BTS
                if (jangkauanKm) {
                    const coverage = new Feature({
                        geometry: new Circle(coord, jangkauanKm * 1000),
                        operator: styleConfig.label,
                        jenis_jaringan: bts.jenis_jaringan,
                        jangkauan_sinyal: jangkauanKm
                    });
        
                    coverage.setStyle(new Style({
                        stroke: new Stroke({ color: styleConfig.coverageStroke, width: 2 }),
                        fill: new Fill({ color: styleConfig.coverageColor })
                    }));
        
                    coverageSources[operator].addFeature(coverage);
                }
            });
        
            // Tambahkan layer per operator
            Object.keys(operatorStyles).forEach(op => {
                this.map.addLayer(new VectorLayer({
                    source: markerSources[op],
                    label: `BTS ${operatorStyles[op].label}`,
                    zIndex: 10
                }));
        
                this.map.addLayer(new VectorLayer({
                    source: coverageSources[op],
                    label: `Jangkauan ${operatorStyles[op].label}`,
                    zIndex: 5
                }));
            });
        
            // Pop-up klik
            this.map.on('click', (evt) => {
                const feature = this.map.forEachFeatureAtPixel(evt.pixel, f => f);
        
                const popup = document.getElementById('popup');
                const popupContent = document.getElementById('popup-content');
        
                if (feature && popup && popupContent) {
                    const name = feature.get('name');
                    const alamat = feature.get('alamat');
                    const jenis = feature.get('jenis_jaringan');
                    const jangkauan = feature.get('jangkauan_sinyal');
                    const operator = feature.get('operator');
        
                    let html = '';
        
                    if (feature === this.userLocationFeature) {
                        const userCoord = feature.getGeometry().getCoordinates();
                        const closestBTS = this.getClosestBTS(userCoord);
                        html += `<strong>Lokasi Anda</strong><br>`;
                        html += closestBTS ? `Anda terhubung ke BTS: <strong>${closestBTS.nama_BTS}</strong>` :
                                             `<em>Anda tidak tersambung ke BTS terdekat, <br> anda berada dalam area Blank Spot</em>`;
                    } else if (name && alamat) {
                        html += `<strong>${name}</strong><br>${alamat}<br>`;
                        if (jenis && jangkauan) {
                            html += `<em>Jaringan: ${jenis}<br>Jangkauan: ±${jangkauan} km</em>`;
                        }
                    } else if (operator && jenis && jangkauan) {
                        html += `<strong>${operator}</strong><br>`;
                        html += `<em>Jaringan: ${jenis}<br>Jangkauan: ±${jangkauan} km</em>`;
                    } else {
                        html += feature.get('name') || 'Area';
                    }
        
                    popupContent.innerHTML = html;
                    popup.style.left = `${evt.pixel[0]}px`;
                    popup.style.top = `${evt.pixel[1]}px`;
                    popup.style.display = 'block';
                } else {
                    popup.style.display = 'none';
                }
            });
        },

        getClosestBTS(userCoord) {
            let closest = null;
            let minDist = Infinity;
        
            const userCoordLonLat = toLonLat(userCoord);
        
            window.btsData.forEach(bts => {
                const btsCoordLonLat = [
                    parseFloat(bts.Longitude),
                    parseFloat(bts.Latitude)
                ];
        
                const dist = getDistance(btsCoordLonLat, userCoordLonLat);
        
                const radius = (bts.jenis_jaringan === '3G' ? 8 :
                                bts.jenis_jaringan === '4G' ? 5 :
                                bts.jenis_jaringan === '5G' ? 3 : 0) * 1000;
        
                if (radius > 0 && dist <= radius) {
                    if (dist < minDist) {
                    closest = bts;
                    minDist = dist;
                    }
                }
            });
        
            return closest;
        },

        showUserLocation(lat, lon) {
            const coords = fromLonLat([lon, lat]);

            this.userLocationFeature = new Feature({
                geometry: new Point(coords),
                name: 'Lokasi Saya'
            });

            this.userLocationFeature.setStyle(new Style({
                image: new CircleStyle({
                    radius: 6,
                    fill: new Fill({ color: '#0000FF' }),
                    stroke: new Stroke({ color: '#FFFFFF', width: 2 })
                })
            }));

            if (this.userLocationLayer) {
                this.userLocationLayer.getSource().clear();
            } else {
                this.userLocationLayer = new VectorLayer({
                    source: new VectorSource(),
                    label: 'Lokasi Saya',
                    zIndex: 99
                });
                this.map.addLayer(this.userLocationLayer);
            }

            this.userLocationLayer.getSource().addFeature(this.userLocationFeature);
            this.map.getView().animate({ center: coords, zoom: 14 });
        }
    }
}
