import Component from '@okiba/component';
import { on, qsa, qs } from '@okiba/dom';

import axios from 'axios';

import { Loader } from '@googlemaps/js-api-loader';

const ui = {
    map: '.js-caffeina-store-locator-map',
    list: '.js-caffeina-store-locator-list',
    infowindows: '.js-caffeina-store-locator-infowindows',
    geolocation: '.js-caffeina-store-locator-geolocation',
    search: '.js-caffeina-store-locator-search',
    loader: '.js-caffeina-store-locator-loader',
    notFound: '.js-caffeina-store-locator-notfound',
}

class StoreLocatorCaffeina extends Component {

    constructor({ el }) {
        super({ el, ui })

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
            // controls: false,
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
                // Get stores
                this.getStores().then((res) => { this.stores = res }).then(() => {
                    // Init Map
                    this.initMap(google, this.ui.map, this.mapParameters)
                })
            })
            .catch((error) => {
                console.warn(error);
            })
    }



    /**
     * Init map
     * 
     * @param {*} google 
     * @param {*} mapEl 
     * @param {*} mapParameters 
     * @returns 
     */
    initMap = (google, mapEl, mapParameters) => {
        // Init map
        this.map = new google.maps.Map(mapEl, mapParameters)

        // Init map markers
        this.map.markers = []

        // Add markers
        if (this.stores.length > 0) {
            this.stores.forEach((markerData, i) => {
                this.addMarker(google, this.map, markerData, i);
            })
        }

        // Add listener to open and close infowindow outside
        on(qsa('.js-caffeina-store-locator-store-open'), 'click', this.openInfowindowOutside)
        on(qsa('.js-caffeina-store-locator-store-close'), 'click', this.closeInfowindowOutside)

        // Center map
        this.centerMap(google, this.map)

        // Geolocation service
        this.geolocation(google, this.map)

        // Init search with autocomplete
        this.search(google, this.map)

        google.maps.event.addListener(this.map, 'bounds_changed', this.bindMap)

        // Set "href" to open maps app
        this.setOpenMapAppLink()

        // Remove loader
        this.removeLoader()
    }



    getStores = async () => {
        let stores = []

        try {
            const { data } = await axios.get(`${window.location.origin}/wp-json/v1/stores`);
            stores = data
        } catch (error) {
            console.error(error);
        }

        return stores
    }



    addMarker = (google, map, markerData, i) => {
        const latLng = {
            lat: parseFloat(markerData.geo_location.lat),
            lng: parseFloat(markerData.geo_location.lng)
        }

        const marker = new google.maps.Marker({
            position: latLng,
            map: map,
            icon: window.location.origin + '/wp-content/themes/caffeina-theme/assets/images/map/markers/marker.svg'
        })

        map.markers.push(marker)

        // google.maps.event.addListener(marker, 'click', this.openInfowindowOutside)

        // Add store to list
        this.addStoreToList(google, map, marker, markerData, i)

        // If you want to add Infowindow outside Map
        this.addInfowindowOutside(google, map, marker, markerData, i)

        // If you want to add Infowindow into Map on Marker
        // this.addInfowindowInside(google, map, marker, markerData, i)
    }



    addStoreToList = (google, map, marker, markerData, i) => {
        const infowindow = `
            <div class="lb-store-locator__store caffeina-store-locator__store" data-store-lat="${markerData.geo_location.lat}" data-store-lng="${markerData.geo_location.lng}">
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
                <div class="lb-store-locator__store__open js-caffeina-store-locator-store-open" data-store-id="${i}">
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



    addInfowindowOutside = (google, map, marker, markerData, i) => {
        const infowindow = `
            <div class="lb-store-locator__infowindow caffeina-store-locator__infowindow" data-store-id="${i}">
                <div class="lb-store-locator__infowindow__close js-caffeina-store-locator-store-close" data-store-id="${i}">
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
                <a class="button button-quaternary js-caffeina-store-locator-store-link" href="#" target="_blank" data-store-lat="${markerData.geo_location.lat}" data-store-lng="${markerData.geo_location.lng}">
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
        this.ui.infowindows.insertAdjacentHTML('beforeend', infowindow)
    }



    openInfowindowOutside = (ev) => {
        const open = ev.target.closest('.js-caffeina-store-locator-store-open')
        const storeID = open.dataset.storeId
        const infowindow = qs(`[data-store-id="${storeID}"]`, this.ui.infowindows)

        infowindow.classList.add('caffeina-store-locator__infowindow--open')

        this.ui.list.classList.add('caffeina-store-locator__list--hide')
        this.ui.infowindows.classList.add('caffeina-store-locator__infowindows--show')

        this.map.setCenter({
            lat: this.map.markers[storeID].position.lat(),
            lng: this.map.markers[storeID].position.lng(),
        })
        this.map.setZoom(15)
    }



    closeInfowindowOutside = (ev) => {
        const close = ev.target.closest('.js-caffeina-store-locator-store-close')
        const storeID = close.dataset.storeId
        const infowindow = qs(`[data-store-id="${storeID}"]`, this.ui.infowindows)

        infowindow.classList.remove('caffeina-store-locator__infowindow--open')

        this.ui.list.classList.remove('caffeina-store-locator__list--hide')
        this.ui.infowindows.classList.remove('caffeina-store-locator__infowindows--show')

        // Re-center map
        this.centerMap(google, this.map)
    }



    closeAllInfowindowOutside = () => {
        const infowindows = qsa('.caffeina-store-locator__infowindow', this.ui.infowindows)

        infowindows.forEach(el => {
            el.classList.remove('caffeina-store-locator__infowindow--open')
        });

        this.ui.list.classList.remove('caffeina-store-locator__list--hide')
        this.ui.infowindows.classList.remove('caffeina-store-locator__infowindows--show')
    }



    addInfowindowInside = (google, map, marker, markerData, i) => {
        const infowindow = new google.maps.InfoWindow({
            content: `
                <div class="caffeina-store-locator__map__infowindow">
                    ${markerData.store}
                </div>
            `
        })
        google.maps.event.addListener(marker, 'click', () => {
            if (this.prevInfowindow) {
                this.prevInfowindow.close()
            }
            this.prevInfowindow = infowindow
            infowindow.open(map, marker)
        })
    }



    bindMap = () => {
        let founded = []
        let notFounded = []
        const elems = qsa('.caffeina-store-locator__store', this.ui.list)

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

        founded.forEach((elFounded) => {
            elems.forEach(el => {
                if ((el.dataset.storeLat == elFounded.lat) && (el.dataset.storeLng == elFounded.lng)) {
                    el.setAttribute("data-is-found", "true")
                }
            })
        })

        notFounded.forEach((elNotFounded) => {
            elems.forEach(el => {
                if ((el.dataset.storeLat == elNotFounded.lat) && (el.dataset.storeLng == elNotFounded.lng)) {
                    el.setAttribute("data-is-found", "false")
                }
            })
        })

        if (founded.length <= 0) {
            this.ui.notFound.classList.add('caffeina-store-locator__notfound--show')
        } else {
            this.ui.notFound.classList.remove('caffeina-store-locator__notfound--show')
        }
    }



    /**
     * Search with autocomplete
     */
    search = (google, map) => {
        const autocomplete = new google.maps.places.Autocomplete(this.ui.search, {
            componentRestrictions: { country: this.mapCountry },
            // fields: ["address_components", "geometry", "icon", "name"],
            // types: ["establishment"],
        })
        autocomplete.bindTo("bounds", map)

        autocomplete.addListener("place_changed", () => {
            let place = autocomplete.getPlace()

            this.closeAllInfowindowOutside()

            if (!place.geometry || !place.geometry.location) {
                // User entered the name of a Place that was not suggested and
                // pressed the Enter key, or the Place Details request failed
                // alert("Error: No details available for input: '" + place.name + "'.")
                return
            } else {
                // If the place has a geometry, then present it on a map
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport)
                    map.setZoom(12)
                } else {
                    map.setCenter(place.geometry.location)
                    map.setZoom(6)
                }
                // bindMap(newMap)
                // handleSearch(place, map, google)
            }
        })
    }



    /**
     * Centers the map showing all markers in view
     * 
     * @param {*} map 
     * @param {*} google 
     */
    centerMap = (google, map) => {
        if (map.markers && map.markers.length > 0) {
            const bounds = new google.maps.LatLngBounds()
            map.markers.forEach(function (marker) {
                bounds.extend({
                    lat: marker.position.lat(),
                    lng: marker.position.lng()
                })
            })

            if (map.markers.length == 1) {
                map.setCenter(bounds.getCenter())
            } else {
                map.fitBounds(bounds)
            }
        } else {
            map.setCenter({
                lat: 41.58600176557397,
                lng: 12.840363935951498,
            })
            map.setZoom(5.5)
        }
    }



    /**
     * Set current location on map
     */
    geolocation = (google, map) => {
        this.ui.geolocation.addEventListener("click", () => {
            // Add loader
            this.addLoader()

            if (navigator.geolocation) {
                navigator.geolocation.getCurrentPosition((position) => {
                    const pos = {
                        lat: position.coords.latitude,
                        lng: position.coords.longitude,
                    }
                    const marker = new google.maps.Marker({
                        position: pos,
                        map: map,
                    })
                    map.setCenter(pos)
                    map.setZoom(8)
                    map.markers.push(marker)

                    // Remove loader
                    this.removeLoader()
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
     * Open directions in map app
     */
    setOpenMapAppLink = () => {
        const buttons = qsa('.js-caffeina-store-locator-store-link')

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



    addLoader = () => {
        this.ui.loader.classList.add('caffeina-store-locator__loader--loading')
    }



    removeLoader = () => {
        this.ui.loader.classList.remove('caffeina-store-locator__loader--loading')
    }
}

export default StoreLocatorCaffeina
