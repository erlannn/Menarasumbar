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
import Polygon from 'ol/geom/Polygon';
import { area as turfArea, union as turfUnion, intersect as turfIntersect, difference as turfDifference } from '@turf/turf';

// Helper function to convert Circle geometry to Polygon
function circleToPolygon(circleGeom, numPoints = 64) {
    const center = circleGeom.getCenter();
    const radius = circleGeom.getRadius();
    const coords = [];
    
    for (let i = 0; i < numPoints; i++) {
        const angle = (i / numPoints) * 2 * Math.PI;
        const x = center[0] + radius * Math.cos(angle);
        const y = center[1] + radius * Math.sin(angle);
        coords.push([x, y]);
    }
    // Close the polygon
    coords.push(coords[0]);
    
    return new Polygon([coords]);
}

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
            this.coverageSources = null;
            this.kecamatanSource = null;

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
                // Calculate coverage after layers are ready
                console.log('>>> init timeout: calling calculateAndDisplayCoverage');
                this.calculateAndDisplayCoverage();
            }, 1000);
        },

        calculateAndDisplayCoverage() {
            try {
                console.log('=== Coverage Calculation Start ===');
                
                if (!this.kecamatanSource || !this.coverageSources) {
                    console.warn('kecamatanSource or coverageSources not ready');
                    return;
                }

                const kecFeatures = this.kecamatanSource.getFeatures();
                console.log('Kecamatan features:', kecFeatures.length);
                if (!kecFeatures || kecFeatures.length === 0) {
                    console.warn('No kecamatan boundary features found');
                    return;
                }

                const geojsonFormat = new GeoJSON();
                const kecFeat = kecFeatures[0];
                const kecGeo = geojsonFormat.writeFeatureObject(kecFeat, { featureProjection: 'EPSG:3857', dataProjection: 'EPSG:4326' });
                const kecArea = turfArea(kecGeo);
                console.log('Kecamatan area (m²):', kecArea, '| km²:', (kecArea/1000000).toFixed(3));

                // Initialize layers if not exist
                if (!this.coveredLayers) this.coveredLayers = {};
                if (!this.blankLayers) this.blankLayers = {};

                const operatorStyles = {
                    OP1: { label: 'Telkomsel', coveredColor: 'rgba(255, 0, 0, 0.5)', blankColor: 'rgba(255, 255, 255, 0.95)' },
                    OP2: { label: 'Indosat', coveredColor: 'rgba(255, 255, 0, 1)', blankColor: 'rgba(255, 255, 255, 0.95)' }
                };

                const operatorData = {};

                // Calculate per operator
                Object.keys(this.coverageSources).forEach(opKey => {
                    const src = this.coverageSources[opKey];
                    const features = src.getFeatures();
                    console.log(`Operator ${opKey}: ${features.length} BTS`);
                    
                    const opConfig = operatorStyles[opKey] || { label: 'Lainnya', coveredColor: 'rgba(0, 123, 255, 0.4)', blankColor: 'rgba(255, 255, 255, 0.95)' };
                    
                    // Collect coverage polygons for this operator
                    const coveragePolys = [];
                    features.forEach((f, idx) => {
                        try {
                            let geom = f.getGeometry();
                            let polyGeom = geom;
                            
                            // Convert Circle to Polygon
                            if (geom && typeof geom.getType === 'function' && geom.getType() === 'Circle') {
                                polyGeom = circleToPolygon(geom, 64);
                            }

                            const tmpFeat = new Feature({ geometry: polyGeom });
                            const polyGeo = geojsonFormat.writeFeatureObject(tmpFeat, { 
                                featureProjection: 'EPSG:3857', 
                                dataProjection: 'EPSG:4326' 
                            });
                            
                            coveragePolys.push(polyGeo);
                        } catch (e) {
                            console.error(`Error converting BTS ${idx} geometry for ${opKey}:`, e);
                        }
                    });

                    // Union coverage polygons for this operator
                    let mergedOp = null;
                    if (coveragePolys.length > 0) {
                        try {
                            mergedOp = coveragePolys.reduce((acc, poly, idx) => {
                                if (!acc) return poly;
                                try {
                                    const result = turfUnion(acc, poly);
                                    return result || acc;
                                } catch (e) {
                                    console.warn(`Union step ${idx} for ${opKey} failed:`, e.message);
                                    return acc;
                                }
                            }, null);
                        } catch (e) {
                            console.error(`Union reduce failed for ${opKey}:`, e);
                            mergedOp = null;
                        }
                    }

                    // Intersect with kecamatan
                    let coveredArea = 0;
                    if (mergedOp) {
                        try {
                            const intersection = turfIntersect(mergedOp, kecGeo);
                            if (intersection) {
                                coveredArea = turfArea(intersection);
                            }
                        } catch (e) {
                            console.error(`Intersection error for ${opKey}:`, e);
                            coveredArea = 0;
                        }
                    }

                    const blankArea = Math.max(0, kecArea - coveredArea);
                    const pctBlank = kecArea > 0 ? (blankArea / kecArea) * 100 : 0;
                    const pctCovered = kecArea > 0 ? (coveredArea / kecArea) * 100 : 0;

                    console.log(`${opConfig.label} - Covered:`, coveredArea, '|', pctCovered.toFixed(2), '% | Blank:', blankArea, '|', pctBlank.toFixed(2), '%');

                    operatorData[opKey] = {
                        coveredArea: coveredArea,
                        blankArea: blankArea,
                        pctCovered: pctCovered,
                        pctBlank: pctBlank
                    };

                    // Create/update covered layer
                    let coveredGeo = null;
                    let blankGeo = null;
                    if (mergedOp) {
                        const intersection = turfIntersect(mergedOp, kecGeo);
                        if (intersection) {
                            coveredGeo = intersection;
                            blankGeo = turfDifference(kecGeo, intersection) || null;
                        } else {
                            coveredGeo = null;
                            blankGeo = kecGeo;
                        }
                    } else {
                        coveredGeo = null;
                        blankGeo = kecGeo;
                    }

                    const geojsonReader = new GeoJSON();

                    // Covered layer
                    if (this.coveredLayers[opKey] && this.coveredLayers[opKey].getSource()) {
                        this.coveredLayers[opKey].getSource().clear();
                        if (coveredGeo) {
                            const feats = geojsonReader.readFeatures(coveredGeo, { featureProjection: 'EPSG:3857', dataProjection: 'EPSG:4326' });
                            this.coveredLayers[opKey].getSource().addFeatures(feats);
                        }
                    } else {
                        const coveredSource = new VectorSource();
                        if (coveredGeo) {
                            const feats = geojsonReader.readFeatures(coveredGeo, { featureProjection: 'EPSG:3857', dataProjection: 'EPSG:4326' });
                            coveredSource.addFeatures(feats);
                        }
                        this.coveredLayers[opKey] = new VectorLayer({
                            source: coveredSource,
                            style: new Style({
                                fill: new Fill({ color: opConfig.coveredColor }),
                                stroke: new Stroke({ color: 'rgba(0, 0, 0, 0.5)', width: 1 })
                            }),
                            zIndex: 7,
                            visible: true,
                            label: `Area Tercover ${opConfig.label}`
                        });
                        this.map.addLayer(this.coveredLayers[opKey]);
                    }

                    // Blank layer
                    if (this.blankLayers[opKey] && this.blankLayers[opKey].getSource()) {
                        this.blankLayers[opKey].getSource().clear();
                        if (blankGeo) {
                            const feats = geojsonReader.readFeatures(blankGeo, { featureProjection: 'EPSG:3857', dataProjection: 'EPSG:4326' });
                            this.blankLayers[opKey].getSource().addFeatures(feats);
                        }
                    } else {
                        const blankSource = new VectorSource();
                        if (blankGeo) {
                            const feats = geojsonReader.readFeatures(blankGeo, { featureProjection: 'EPSG:3857', dataProjection: 'EPSG:4326' });
                            blankSource.addFeatures(feats);
                        }
                        this.blankLayers[opKey] = new VectorLayer({
                            source: blankSource,
                            style: new Style({
                                fill: new Fill({ color: opConfig.blankColor }),
                                stroke: new Stroke({ color: 'rgba(150, 150, 150, 0.6)', width: 1 })
                            }),
                            zIndex: 6,
                            visible: true,
                            label: `Blank Spot ${opConfig.label}`
                        });
                        this.map.addLayer(this.blankLayers[opKey]);
                    }
                });

                // Build statsHtml
                let statsHtml = `<strong>Area Kecamatan:</strong> ${ (kecArea/1000000).toFixed(3) } km²<br>`;
                if (operatorData.OP1) {
                    statsHtml += `<strong>Blank Spot Telkomsel:</strong> ${ (operatorData.OP1.coveredArea/1000000).toFixed(3) } km² (${operatorData.OP1.pctCovered.toFixed(2)}%)<br>`;
                    statsHtml += `<strong>Non-Blank Spot Telkomsel:</strong> ${ (operatorData.OP1.blankArea/1000000).toFixed(3) } km² (${operatorData.OP1.pctBlank.toFixed(2)}%)<br>`;
                }
                if (operatorData.OP2) {
                    statsHtml += `<br><strong>Blank Spot Indosat:</strong> ${ (operatorData.OP2.coveredArea/1000000).toFixed(3) } km² (${operatorData.OP2.pctCovered.toFixed(2)}%)<br>`;
                    statsHtml += `<strong>Non-Blank Spot Indosat:</strong> ${ (operatorData.OP2.blankArea/1000000).toFixed(3) } km² (${operatorData.OP2.pctBlank.toFixed(2)}%)<br>`;
                }

                // Update external placeholder (e.g., left panel)
                const ext = document.getElementById('coverage-summary');
                if (ext) {
                    ext.innerHTML = statsHtml;
                }
                
                console.log('Display updated');
                
                console.log('=== Coverage Calculation End ===');
            } catch (e) {
                console.error('calculateAndDisplayCoverage error:', e);
            }
        },

        _createLayerToggleUI() {
            try {
                if (!this.$refs || !this.$refs.map) return;

                const container = this.$refs.map;
                const ctrl = document.createElement('div');
                ctrl.style.position = 'absolute';
                ctrl.style.top = '10px';
                ctrl.style.right = '10px';
                ctrl.style.background = 'rgba(255,255,255,0.9)';
                ctrl.style.padding = '8px';
                ctrl.style.borderRadius = '6px';
                ctrl.style.zIndex = 2000;
                ctrl.style.maxHeight = '80vh';
                ctrl.style.overflowY = 'auto';

                const operatorStyles = {
                    OP1: { label: 'Telkomsel' },
                    OP2: { label: 'Indosat' }
                };

                Object.keys(operatorStyles).forEach(opKey => {
                    const opConfig = operatorStyles[opKey];
                    
                    // Blank spot checkbox
                    const cbBlank = document.createElement('input');
                    cbBlank.type = 'checkbox';
                    cbBlank.id = `cb-blank-${opKey}`;
                    cbBlank.checked = true;
                    cbBlank.style.marginRight = '6px';
                    const lblBlank = document.createElement('label');
                    lblBlank.htmlFor = `cb-blank-${opKey}`;
                    lblBlank.innerText = `Blank Spot ${opConfig.label}`;
                    lblBlank.style.display = 'block';
                    lblBlank.style.marginBottom = '4px';

                    // Covered area checkbox
                    const cbCovered = document.createElement('input');
                    cbCovered.type = 'checkbox';
                    cbCovered.id = `cb-covered-${opKey}`;
                    cbCovered.checked = true;
                    cbCovered.style.marginRight = '6px';
                    const lblCovered = document.createElement('label');
                    lblCovered.htmlFor = `cb-covered-${opKey}`;
                    lblCovered.innerText = `Area Tercover ${opConfig.label}`;
                    lblCovered.style.display = 'block';
                    lblCovered.style.marginBottom = '8px';

                    ctrl.appendChild(cbBlank);
                    ctrl.appendChild(lblBlank);
                    ctrl.appendChild(cbCovered);
                    ctrl.appendChild(lblCovered);

                    cbBlank.addEventListener('change', (e) => {
                        if (this.blankLayers && this.blankLayers[opKey]) {
                            this.blankLayers[opKey].setVisible(e.target.checked);
                        }
                    });

                    cbCovered.addEventListener('change', (e) => {
                        if (this.coveredLayers && this.coveredLayers[opKey]) {
                            this.coveredLayers[opKey].setVisible(e.target.checked);
                        }
                    });
                });

                container.appendChild(ctrl);
            } catch (e) {
                console.warn('Could not create layer toggle UI:', e);
            }
        },
        addBoundaryLayer() {
            console.log('>>> addBoundaryLayer called, fetching:', window.kecamatanGeoJSON);
            fetch(window.kecamatanGeoJSON)
                .then(response => {
                    console.log('>>> GeoJSON fetch response:', response.status);
                    return response.json();
                })
                .then(geojsonData => {
                    console.log('>>> GeoJSON data loaded, features:', geojsonData.features ? geojsonData.features.length : 0);
                    const source = new VectorSource({
                        features: new GeoJSON().readFeatures(geojsonData, {
                            featureProjection: 'EPSG:3857'
                        })
                    });
        
                    const geojsonLayer = new VectorLayer({
                        source: source,
                        style: new Style({
                            stroke: new Stroke({ color: 'yellow', width: 3 }),
                            fill: new Fill({ color: 'rgba(255, 0, 0, 0)' })
                        }),
                        zIndex: 1,
                        label: 'Batas Kecamatan'
                    });
        
                    this.map.addLayer(geojsonLayer);
                    this.kecamatanSource = source;
                    console.log('>>> kecamatanSource set, feature count:', source.getFeatures().length);
                })
                .catch(err => console.error('>>> GeoJSON fetch error:', err));
        },        

        addBTSLayers() {
            console.log('>>> addBTSLayers called');
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
                console.log('>>> BTS:', bts.nama_BTS, '| jenis_jaringan:', bts.jenis_jaringan, '| operator:', operator);
                const styleConfig = operatorStyles[operator] || {
                    src: '/img/default.png',
                    scale: 0.1,
                    label: 'Lainnya',
                    coverageColor: 'rgba(0, 123, 255, 0.2)',
                    coverageStroke: 'rgba(0, 123, 255, 0.6)'
                };
        
                const coord = fromLonLat([parseFloat(bts.Longitude), parseFloat(bts.Latitude)]);
                
                // Baca jangkauan dari tabel perangkatjaringan (Jangkauan_sinyal)
                let jangkauanKm = bts.Jangkauan_sinyal ? parseFloat(bts.Jangkauan_sinyal) : null;
                if (!jangkauanKm) {
                    // Fallback: hitung dari jenis_jaringan jika radius tidak ada
                    jangkauanKm = bts.jenis_jaringan === '3G' ? 4 :
                                  bts.jenis_jaringan === '4G' ? 3 :
                                  bts.jenis_jaringan === '5G' ? 2 : 3;
                }
                console.log('>>> BTS:', bts.nama_BTS, '| Jangkauan:', jangkauanKm, 'km');
        
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
                    console.log('>>> Added coverage feature for', bts.nama_BTS, 'to', operator);
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

            // keep reference to coverage sources for area calculations
            this.coverageSources = coverageSources;
            console.log('>>> coverageSources set, operators:', Object.keys(coverageSources));
            Object.keys(coverageSources).forEach(key => {
                console.log('>>> Operator', key, '- coverage features count:', coverageSources[key].getFeatures().length);
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
                    radius: 8,
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
