import Component from '@okiba/component';
import { on, qsa, qs } from '@okiba/dom';

import axiosClient from '../HTTPClient'

import { Loader } from '@googlemaps/js-api-loader';
import { MarkerClusterer, MarkerUtils } from '@googlemaps/markerclusterer';

import templateLoader from '../../utils/templateLoader';

const ui = {
    map: '.js-distributor-map',
    loader: '.js-distributor-loader',
}

class DistributorsMap extends Component {

    constructor({ el }) {
        super({ el, ui })

        // Base vars
        this.lang = document.documentElement.lang
        this.google = null
        this.map = null
        this.distributors = null
        this.prevInfowindow = false

        // Map zoom
        this.mapZoom = this.ui.map.dataset.mapZoom || 5.5
        // Map localization
        this.mapCountry = this.ui.map.dataset.mapCountry
        this.mapLang = this.ui.map.dataset.mapLang
        // Map styles
        this.mapStyles = [
            {
                "featureType": "water",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#e9e9e9"
                    },
                    {
                        "lightness": 17
                    }
                ]
            },
            {
                "featureType": "landscape",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#f5f5f5"
                    },
                    {
                        "lightness": 20
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry.fill",
                "stylers": [
                    {
                        "color": "#ffffff"
                    },
                    {
                        "lightness": 17
                    }
                ]
            },
            {
                "featureType": "road.highway",
                "elementType": "geometry.stroke",
                "stylers": [
                    {
                        "color": "#ffffff"
                    },
                    {
                        "lightness": 29
                    },
                    {
                        "weight": 0.2
                    }
                ]
            },
            {
                "featureType": "road.arterial",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#ffffff"
                    },
                    {
                        "lightness": 18
                    }
                ]
            },
            {
                "featureType": "road.local",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#ffffff"
                    },
                    {
                        "lightness": 16
                    }
                ]
            },
            {
                "featureType": "poi",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#f5f5f5"
                    },
                    {
                        "lightness": 21
                    }
                ]
            },
            {
                "featureType": "poi.park",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#dedede"
                    },
                    {
                        "lightness": 21
                    }
                ]
            },
            {
                "elementType": "labels.text.stroke",
                "stylers": [
                    {
                        "visibility": "on"
                    },
                    {
                        "color": "#ffffff"
                    },
                    {
                        "lightness": 16
                    }
                ]
            },
            {
                "elementType": "labels.text.fill",
                "stylers": [
                    {
                        "saturation": 36
                    },
                    {
                        "color": "#333333"
                    },
                    {
                        "lightness": 40
                    }
                ]
            },
            {
                "elementType": "labels.icon",
                "stylers": [
                    {
                        "visibility": "off"
                    }
                ]
            },
            {
                "featureType": "transit",
                "elementType": "geometry",
                "stylers": [
                    {
                        "color": "#f2f2f2"
                    },
                    {
                        "lightness": 19
                    }
                ]
            },
            {
                "featureType": "administrative",
                "elementType": "geometry.fill",
                "stylers": [
                    {
                        "color": "#fefefe"
                    },
                    {
                        "lightness": 20
                    }
                ]
            },
            {
                "featureType": "administrative",
                "elementType": "geometry.stroke",
                "stylers": [
                    {
                        "color": "#fefefe"
                    },
                    {
                        "lightness": 17
                    },
                    {
                        "weight": 1.2
                    }
                ]
            }
        ]

        // Map parameters
        this.mapParameters = {
            // center: { lat: 41.58600176557397, lng: 12.840363935951498 },
            zoom: this.mapZoom,
            styles: this.mapStyles,
            // Controls
            mapTypeControl: false,
            streetViewControl: false,
            fullscreenControl: false,
            // Bounds and Zoom restictions
            // minZoom: this.mapZoom - 3,
            // maxZoom: this.mapZoom + 3,
            // restriction: {
            //     latLngBounds: {
            //         north: -10,
            //         south: -40,
            //         east: 160,
            //         west: 100,
            //     },
            // },
        }

        // Google map Loader
        this.loader = new Loader({
            apiKey: 'AIzaSyCG7SmW_OVvWwj1ngGzqBdLhOGgpXTpBnM',
            libraries: ['places'],
            region: this.mapCountry,
            language: this.mapLang,
        });

        this.addLoader()

        this.loader
            .load()
            .then((google) => {
                // Set google loader instance
                this.google = google
                // Get stores
                this.getDistributors().then((res) => { this.distributors = res }).then(() => {
                    // Init Map
                    this.initMap(this.ui.map)
                })
            })
            .catch((error) => {
                console.warn(error);
            })
    }


    /**
     * Init Map
     * 
     * @param {HTMLElement} mapEl Map element
     */
    initMap = (mapEl) => {
        // Init map
        this.map = new this.google.maps.Map(mapEl, this.mapParameters)

        // Init map markers array
        this.map.markers = []

        // Add markers
        if (this.distributors.length > 0) {
            this.distributors.forEach((markerData, i) => {
                this.addMarker(markerData, i);
            })
        }

        // Custom Marker Clusterer render 
        this.customMarkerClustererRender = {
            render: ({ count, position }, stats, map) => {
                const title = `Cluster of ${count} markers`
                const zIndex = Number(this.google.maps.Marker.MAX_ZINDEX) + count
                const svg = `
                    <svg width="44" height="44" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="22" cy="22" r="22" fill="#B52A2D" opacity=".8" />
                    </svg>
                `

                if (MarkerUtils.isAdvancedMarkerAvailable(map)) {
                    // create cluster SVG element
                    const parser = new DOMParser();
                    const svgEl = parser.parseFromString(svg, "image/svg+xml").documentElement;
                    svgEl.setAttribute("transform", "translate(0 25)");
                    const clusterOptions = {
                        map,
                        position,
                        zIndex,
                        title,
                        label: {
                            text: String(count),
                            color: "#FFFFFF",
                            fontSize: "12px",
                            fontWeight: "700",
                        },
                        content: svgEl,
                    };
                    return new this.google.maps.marker.AdvancedMarkerElement(clusterOptions);
                }

                const clusterOptions = {
                    position,
                    zIndex,
                    title,
                    icon: {
                        url: `data:image/svg+xml;base64,${btoa(svg)}`,
                        anchor: new this.google.maps.Point(25, 25),
                    },
                    label: {
                        text: String(count),
                        color: "#FFFFFF",
                        fontSize: "12px",
                        fontWeight: "700",
                    },
                };

                return (count > 0) ? new this.google.maps.Marker(clusterOptions) : null
            }
        }

        // Add a marker clusterer to manage the markers
        this.markerClusterer = new MarkerClusterer({
            map: this.map,
            markers: this.map.markers,
            renderer: this.customMarkerClustererRender,
        })

        // Center map
        this.centerMap()

        // Remove loader
        this.removeLoader()

        // Add Google loader instance
        window.lbGMapLoaderDistributors = this.google

        // Add Map to window object, to handle filters
        window.lbMapDistributors = this.map

        // Update MarkerClusterer
        window.lbMapDistributorsUpdateClusterer = (markers) => {
            this.addLoader()
            const filteredMarkers = markers.filter(item => item.visible)
            this.markerClusterer.clearMarkers()
            this.markerClusterer.addMarkers(filteredMarkers)
            this.removeLoader()
        }
    }


    /**
     * Get all Stores
     * 
     * @returns Stores fetched
     */
    getDistributors = async () => {
        let stores = []

        try {
            const url = `${this.lang != 'it' ? `/${this.lang}` : ''}/wp-json/v1/distributors`
            const { data } = await axiosClient.get(url)
            stores = data
        } catch (error) {
            console.error(error);
        }

        return stores
    }


    /**
     * Add single Marker to Map
     * 
     * @param {Object} markerData Store data
     * @param {Number} i Incremental counter
     */
    addMarker = (markerData, i) => {
        // Create Marker
        const marker = new this.google.maps.Marker({
            position: {
                lat: parseFloat(markerData.geo_location.lat),
                lng: parseFloat(markerData.geo_location.lng)
            },
            map: this.map,
            category: markerData.brands.map((brand) => { return brand.id }),
            icon: window.location.origin + '/wp-content/themes/caffeina-theme/assets/images/map/markers/marker.svg'
        })

        // Add Marker to Map Marker array
        marker.position.storeID = i
        this.map.markers.push(marker)

        // If you want to add Infowindow into Map on Marker
        this.addInfowindowInside(marker, markerData, i)
    }



    /**
     * Add GMap Marker Infowindow inside Map
     * 
     * @param {Marker} marker GMap Marker Object
     * @param {Object} markerData Store data
     * @param {Number} i Incremental counter
     */
    addInfowindowInside = async (marker, markerData, i) => {
        // Load twig template
        const template = await templateLoader('components/distributor-infowindow.twig')
        markerData.lang = this.lang
        const html = template.render(markerData)

        // Init new GMap Infowindow
        const infowindow = new this.google.maps.InfoWindow({
            content: html,
            // pixelOffset: new this.google.maps.Size(-350, 250),
            // disableAutoPan: false,
        })

        // add Event to handle more/less text
        this.google.maps.event.addListener(infowindow, 'domready', () => {
            const btnMore = qs('.js-infowindow-more-text')
            const content = qs('.js-infowindow-content')

            on(btnMore, 'click', (ev) => {
                const btnMoreLabel = ev.target.closest('.button__label')

                content.classList.toggle('is-full')

                btnMoreLabel.innerText = (content.classList.contains('is-full')) ? btnMore.dataset.lessText : btnMore.dataset.moreText
            })
        })

        // Open GMap Infowindow on Marker click
        this.google.maps.event.addListener(marker, 'click', () => {
            if (this.prevInfowindow) {
                this.prevInfowindow.close()
            }
            this.prevInfowindow = infowindow
            infowindow.open(this.map, marker)
        })
    }



    /**
     * Center Map based on all Markers in view
     */
    centerMap = () => {
        if (this.map.markers && this.map.markers.length > 0) {
            const bounds = new this.google.maps.LatLngBounds()
            this.map.markers.forEach(function (marker) {
                bounds.extend({
                    lat: marker.position.lat(),
                    lng: marker.position.lng()
                })
            })

            if (this.map.markers.length == 1) {
                this.map.setCenter(bounds.getCenter())
            } else {
                this.map.fitBounds(bounds)
            }
        } else {
            this.map.setCenter({
                lat: 41.58600176557397,
                lng: 12.840363935951498,
            })
            this.map.setZoom(5.5)
        }
    }



    /**
     * Add Loading Overlay to Map
     */
    addLoader = () => {
        this.ui.loader.classList.add('caffeina-sl__loader--loading')
    }


    /**
     * Remove Loading Overlay to Map
     */
    removeLoader = () => {
        this.ui.loader.classList.remove('caffeina-sl__loader--loading')
    }
}

export default DistributorsMap
