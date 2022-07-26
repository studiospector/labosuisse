import Component from '@okiba/component';
import { on, qsa, qs } from '@okiba/dom';

import axiosClient from '../HTTPClient'

import { Loader } from '@googlemaps/js-api-loader';
import { MarkerClusterer } from '@googlemaps/markerclusterer';

const ui = {
    map: '.js-caffeina-sl-map',
    list: '.js-caffeina-sl-list',
    infowindowsWrapper: '.js-caffeina-sl-infowindows',
    geolocation: '.js-caffeina-sl-geolocation',
    search: '.js-caffeina-sl-search',
    loader: '.js-caffeina-sl-loader',
    notFound: '.js-caffeina-sl-notfound',
}

class StoreLocatorCaffeina extends Component {

    constructor({ el }) {
        super({ el, ui })

        // Base vars
        this.google = null
        this.map = null
        this.stores = null
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

        this.loader
            .load()
            .then((google) => {
                // Set google loader instance
                this.google = google
                // Get stores
                this.getStores().then((res) => { this.stores = res }).then(() => {
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
        if (this.stores.length > 0) {
            this.stores.forEach((markerData, i) => {
                this.addMarker(markerData, i);
            })
        }

        // Custom Marker Clusterer render 
        this.customMarkerClustererRender = {
            
            render: ({ count, position }, stats) => {

                const svg = window.btoa(`
                    <svg width="44" height="44" viewBox="0 0 44 44" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="22" cy="22" r="22" fill="#B52A2D" opacity=".8" />
                    </svg>
                `)
                
                return new this.google.maps.Marker({
                    position,
                    icon: {
                        url: `data:image/svg+xml;base64,${svg}`,
                        scaledSize: new this.google.maps.Size(44, 44),
                    },
                    label: {
                        text: String(count),
                        color: "#FFFFFF",
                        fontSize: "12px",
                        fontWeight: "700",
                    },
                    // adjust zIndex to be above other markers
                    zIndex: Number(this.google.maps.Marker.MAX_ZINDEX) + count,
                })
            },
        }

        // Add listener to open and close infowindow outside
        on(qsa('.js-caffeina-sl-store-open'), 'click', this.openInfowindowOutsideDispatcher)
        on(qsa('.js-caffeina-sl-store-close'), 'click', this.closeInfowindowOutside)

        // Add a marker clusterer to manage the markers
        new MarkerClusterer({
            map: this.map,
            markers: this.map.markers,
            renderer: this.customMarkerClustererRender,
        })

        // Center map
        this.centerMap()

        // Geolocation service
        this.geolocation()

        // Init search with autocomplete
        this.search()

        // Update Markers/Stores on map binding
        this.google.maps.event.addListener(this.map, 'bounds_changed', this.bindMap)

        // Set "href" to open maps app
        this.setOpenMapAppLink()

        // Search on Init
        this.searchOnInit()

        // Remove loader
        this.removeLoader()
    }


    /**
     * Get all Stores
     * 
     * @returns Stores fetched
     */
    getStores = async () => {
        let stores = []

        try {
            const { data } = await axiosClient.get(`/wp-json/v1/stores`);
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
            icon: window.location.origin + '/wp-content/themes/caffeina-theme/assets/images/map/markers/marker.svg'
        })

        // Add Marker to Map Marker array
        marker.position.storeID = i
        this.map.markers.push(marker)

        // Open Infowindow from Marker
        this.google.maps.event.addListener(marker, 'click', this.openInfowindowOutsideFromMarkerDispatcher)

        // Add store to list
        this.addStoreToList(markerData, i)

        // If you want to add Infowindow outside Map
        this.addInfowindowOutside(markerData, i)

        // If you want to add Infowindow into Map on Marker
        // this.addInfowindowInside(marker, markerData, i)
    }


    /**
     * Store element render into list
     * 
     * @param {Object} markerData Store data
     * @param {Number} i Incremental counter
     */
    addStoreToList = (markerData, i) => {
        const infowindow = `
            <div class="lb-store-locator__store caffeina-sl__store" data-store-lat="${markerData.geo_location.lat}" data-store-lng="${markerData.geo_location.lng}">
                <div class="lb-store-locator__store__shield">
                    <span class="lb-icon lb-icon-shield-icon">
                        <svg aria-label="shield-icon" xmlns="http://www.w3.org/2000/svg">
                            <use xlink:href="#shield-icon"></use>
                        </svg>
                    </span>
                </div>
                <div class="lb-store-locator__store__info">
                    <h6 class="lb-store-locator__store__info__title">${markerData.store}</h6>
                    <p class="lb-store-locator__store__info__address p-small">${markerData.geo_location.address}</p>
                    <p class="lb-store-locator__store__info__opened p-small">Aperto fino alle 19.30${markerData.open_until}</p>
                </div>
                <div class="lb-store-locator__store__open js-caffeina-sl-store-open" data-store-id="${i}">
                    <span class="lb-icon lb-icon-close-circle">
                        <svg aria-label="close-circle" xmlns="http://www.w3.org/2000/svg">
                            <use xlink:href="#close-circle"></use>
                        </svg>
                    </span>
                </div>
            </div>
        `
        this.ui.list.insertAdjacentHTML('beforeend', infowindow)
    }


    /**
     * Infowindow element render into infowindows list
     * 
     * @param {Object} markerData Store data
     * @param {Number} i Incremental counter
     */
    addInfowindowOutside = (markerData, i) => {
        const infowindow = `
            <div class="lb-store-locator__infowindow caffeina-sl__infowindow" data-store-id="${i}">
                <div class="lb-store-locator__infowindow__close js-caffeina-sl-store-close" data-store-id="${i}">
                    <span class="lb-icon lb-icon-close-circle">
                        <svg aria-label="close-circle" xmlns="http://www.w3.org/2000/svg">
                            <use xlink:href="#close-circle"></use>
                        </svg>
                    </span>
                </div>
                <h6 class="lb-store-locator__infowindow__title">${markerData.store}</h6>
                <p class="lb-store-locator__infowindow__address p-small">${markerData.geo_location.address}</p>
                <p class="lb-store-locator__infowindow__opened p-small">Aperto fino alle 19.30${markerData.open_until}</p>
                <p class="lb-store-locator__infowindow__telephone p-small">N. di telefono: ${markerData.phone}</p>
                <a class="button button-quaternary js-caffeina-sl-store-link" href="#" target="_blank" data-store-lat="${markerData.geo_location.lat}" data-store-lng="${markerData.geo_location.lng}">
                    <span class="button__label">Vai alle indicazioni</span>
                    <span class="lb-icon lb-icon-arrow-right">
                        <svg aria-label="arrow-right" xmlns="http://www.w3.org/2000/svg">
                        <use xlink:href="#arrow-right"></use>
                        </svg>
                    </span>
                </a>
                <div class="lb-store-locator__infowindow__timetable">
                    
                </div>
            </div>
        `
        this.ui.infowindowsWrapper.insertAdjacentHTML('beforeend', infowindow)
    }


    /**
     * Dispatch event to open Infowindow outside Map from Item list
     * 
     * @param {EventListenerObject} ev Click event
     */
    openInfowindowOutsideDispatcher = (ev) => {
        const open = ev.target.closest('.js-caffeina-sl-store-open')
        const storeID = open.dataset.storeId

        this.openInfowindowOutside(storeID)
    }


    /**
     * Dispatch event to open Infowindow outside Map from Marker in Map
     * 
     * @param {EventListenerObject} ev Click event
     */
    openInfowindowOutsideFromMarkerDispatcher = (ev) => {
        const storeID = ev.latLng.storeID

        // Close all Infowindows
        this.closeAllInfowindowOutside()
        // Open Infowindow
        this.openInfowindowOutside(storeID)
    }


    /**
     * Open Infowindow outside Map
     * 
     * @param {Number} storeID Store ID reference
     */
    openInfowindowOutside = (storeID) => {
        // Select Infowindow to open
        const infowindow = qs(`[data-store-id="${storeID}"]`, this.ui.infowindowsWrapper)
        infowindow.classList.add('caffeina-sl__infowindow--open')

        // Hide Store list
        this.ui.list.classList.add('caffeina-sl__list--hide')
        // Show Infowindows list
        this.ui.infowindowsWrapper.classList.add('caffeina-sl__infowindows--show')

        // Bound map on Store
        this.map.setCenter({
            lat: this.map.markers[storeID].position.lat(),
            lng: this.map.markers[storeID].position.lng(),
        })
        this.map.setZoom(15)

        let matchMedia = window.matchMedia("screen and (max-width: 767px)")

        if (matchMedia.matches) {
            setTimeout(() => window.getCustomScrollbar.scrollTo(this.ui.list), 1000)
        }
    }


    /**
     * Close Infowindow
     * 
     * @param {EventListenerObject} ev Click event
     */
    closeInfowindowOutside = (ev) => {
        const close = ev.target.closest('.js-caffeina-sl-store-close')
        const storeID = close.dataset.storeId
        const infowindow = qs(`[data-store-id="${storeID}"]`, this.ui.infowindowsWrapper)

        infowindow.classList.remove('caffeina-sl__infowindow--open')

        this.ui.list.classList.remove('caffeina-sl__list--hide')
        this.ui.infowindowsWrapper.classList.remove('caffeina-sl__infowindows--show')

        // Re-center map
        // this.centerMap(this.map)
    }


    /**
     * Close all Infowindows
     */
    closeAllInfowindowOutside = () => {
        // Get all Infowindows
        const infowindows = qsa('.caffeina-sl__infowindow', this.ui.infowindowsWrapper)

        // Close all Infowindows 
        infowindows.forEach(el => {
            el.classList.remove('caffeina-sl__infowindow--open')
        });

        // Show Store list
        this.ui.list.classList.remove('caffeina-sl__list--hide')
        // Hide Infowindows list
        this.ui.infowindowsWrapper.classList.remove('caffeina-sl__infowindows--show')
    }


    /**
     * Add GMap Marker Infowindow inside Map
     * 
     * @param {Marker} marker GMap Marker Object
     * @param {Object} markerData Store data
     * @param {Number} i Incremental counter
     */
    addInfowindowInside = (marker, markerData, i) => {
        // Init new GMap Infowindow
        const infowindow = new this.google.maps.InfoWindow({
            content: `
                <div class="caffeina-sl__map__infowindow">
                    ${markerData.store}
                </div>
            `
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
     * Update Store list items based on Marker in view on Map drag/change
     */
    bindMap = () => {
        let founded = []
        let notFounded = []

        // Get all Store list items
        const elems = qsa('.caffeina-sl__store', this.ui.list)

        // Filter Store list items based on Marker currently visible on Map
        for (let i = 0; i < this.map.markers.length; i++) {
            const markerLat = this.map.markers[i].position.lat()
            const markerLng = this.map.markers[i].position.lng()
            if (this.map.getBounds().contains(this.map.markers[i].getPosition())) {
                founded.push({
                    lat: markerLat,
                    lng: markerLng
                })
            } else {
                notFounded.push({
                    lat: markerLat,
                    lng: markerLng
                })
            }
        }

        // Show Store list items founded on Map
        founded.forEach((elFounded) => {
            elems.forEach(el => {
                if ((el.dataset.storeLat == elFounded.lat) && (el.dataset.storeLng == elFounded.lng)) {
                    el.setAttribute("data-is-found", "true")
                }
            })
        })

        // Hide Store list items not founded on Map
        notFounded.forEach((elNotFounded) => {
            elems.forEach(el => {
                if ((el.dataset.storeLat == elNotFounded.lat) && (el.dataset.storeLng == elNotFounded.lng)) {
                    el.setAttribute("data-is-found", "false")
                }
            })
        })

        // Add no results Message
        if (founded.length <= 0) {
            this.ui.notFound.classList.add('caffeina-sl__notfound--show')
        } else {
            this.ui.notFound.classList.remove('caffeina-sl__notfound--show')
        }
    }


    /**
     * Init Search field with GMap Autocomplete API
     */
    search = () => {
        const autocomplete = new this.google.maps.places.Autocomplete(this.ui.search, {
            componentRestrictions: { country: this.mapCountry },
            // fields: ["address_components", "geometry", "icon", "name"],
            // types: ["establishment"],
        })
        autocomplete.bindTo("bounds", this.map)

        autocomplete.addListener("place_changed", () => {
            let place = autocomplete.getPlace()

            this.closeAllInfowindowOutside()

            // User entered the name of a Place that was not suggested and
            // pressed the Enter key, or the Place Details request failed
            if (!place.geometry || !place.geometry.location) {
                // alert("Error: No details available for input: '" + place.name + "'.")
                return

            // If the place has a geometry, then show it on a map
            } else {
                if (place.geometry.viewport) {
                    this.map.fitBounds(place.geometry.viewport)
                    this.map.setZoom(12)
                } else {
                    this.map.setCenter(place.geometry.location)
                    this.map.setZoom(6)
                }
                // bindMap(newMap)
                // handleSearch(place, this.map, this.google)
            }
        })
    }


    /**
     * Search on init map
     */
    searchOnInit = () => {
        const request = {
            query: this.ui.search.value,
            fields: ['name', 'geometry'],
        }
    
        const service = new this.google.maps.places.PlacesService(this.map)
    
        service.findPlaceFromQuery(request, (results, status) => {
            if (status === this.google.maps.places.PlacesServiceStatus.OK) {
                this.ui.search.value = results[0].name
                // for (var i = 0; i < results.length; i++) {
                //     createMarker(results[i]);
                // }
                this.map.setCenter(results[0].geometry.location)
                this.map.setZoom(12)
            }
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
     * Init Geolocation of current User and show Marker on Map
     */
    geolocation = () => {
        this.ui.geolocation.addEventListener("click", () => {
            // Add loader
            this.addLoader()

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    }
                    const marker = new this.google.maps.Marker({
                        position: pos,
                        map: this.map,
                        animation: this.google.maps.Animation.DROP,
                    })
                    this.map.setCenter(pos)
                    this.map.setZoom(12)
                    // this.map.markers.push(marker)

                    // Remove loader
                    this.removeLoader()

                    // Reset list
                    this.closeAllInfowindowOutside()
                },
                    () => {
                        handleLocationError(true)
                    })
            } else {
                // Browser doesn't support Geolocation
                handleLocationError(false)
            }
        })

        const handleLocationError = (browserHasGeolocation) => {
            // Remove loader
            this.removeLoader()

            alert(browserHasGeolocation ? "Error: The Geolocation service failed." : "Error: Your browser doesn't support geolocation.")
        }
    }


    /**
     * Set link to GMap or Apple Maps APP based on device
     */
    setOpenMapAppLink = () => {
        const buttons = qsa('.js-caffeina-sl-store-link')

        buttons.forEach((el) => {
            let platform = navigator?.userAgentData?.platform || navigator?.platform

            const lat = el.dataset.storeLat
            const lng = el.dataset.storeLng

            // if we're on iOS, open in Apple Maps
            if ((platform.indexOf("iPhone") != -1) || (platform.indexOf("iPad") != -1) || (platform.indexOf("iPod") != -1)) {
                el.setAttribute("href", `http://maps.apple.com/?q=${lat},${lng}`)
                // else use Google
            } else {
                el.setAttribute("href", `https://www.google.com/maps/search/?api=1&query=${lat},${lng}`)
            }
        })
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

export default StoreLocatorCaffeina
