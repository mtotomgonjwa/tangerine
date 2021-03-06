//Creates a new blank array for all the listing markers.
var markers = [];
var map;
//This global polygon variable is to ensure only ONE polygon is rendered.
var polygon = null;
function initMap() {

    // Create a styles array to use with the map.
    var styles = [
        {
            featureType: 'water',
            stylers: [
                {color: '#19a0d8'}
            ]
        }, {
            featureType: 'administrative',
            elementType: 'labels.text.stroke',
            stylers: [
                {color: '#ffffff'},
                {weight: 6}
            ]
        }, {
            featureType: 'administrative',
            elementType: 'labels.text.fill',
            stylers: [
                {color: '#e85113'}
            ]
        }, {
            featureType: 'road.highway',
            elementType: 'geometry.stroke',
            stylers: [
                {color: '#efe9e4'},
                {lightness: -40}
            ]
        }, {
            featureType: 'transit.station',
            stylers: [
                {weight: 9},
                {hue: '#e85113'}
            ]
        }, {
            featureType: 'road.highway',
            elementType: 'labels.icon',
            stylers: [
                {visibility: 'off'}
            ]
        }, {
            featureType: 'water',
            elementType: 'labels.text.stroke',
            stylers: [
                {lightness: 100}
            ]
        }, {
            featureType: 'water',
            elementType: 'labels.text.fill',
            stylers: [
                {lightness: -100}
            ]
        }, {
            featureType: 'poi',
            elementType: 'geometry',
            stylers: [
                {visibility: 'on'},
                {color: '#f0e4d3'}
            ]
        }, {
            featureType: 'road.highway',
            elementType: 'geometry.fill',
            stylers: [
                {color: '#efe9e4'},
                {lightness: -25}
            ]
        }
    ];

    // These are the advertisement points listings that will be shown to the user.
    // We have these in a database instead.
    var locations = [];
    $.ajax({
        method: 'GET',
        url: 'http://tangerine.local/paste',
        success: function (results) {
            $.each(JSON.parse(results), function (site, k) {

                /* console.log(k.landmark+' | '+k.latitude+' | '+k.longitude);*/

                locations.push(
                    {
                        title: k.landmark.replace("\"", "'"),
                        location: {
                            lat: parseFloat(k.latitude),
                            lng: parseFloat(k.longitude)
                        }


                    }
                );

            });

            map = new google.maps.Map(document.getElementById('map'), {
                center: {lat: -1.274941, lng: 36.810459},
                zoom: 13,
                styles: styles,
                mapTypeControl: false
            });
            google.maps.event.addListener(map, 'click', function (e) {
                alert("Latitude: " + e.latLng.lat() + "\r\nLongitude: " + e.latLng.lng());
            });


            /*console.log(locations[0].title);*/
            var largeInfoWindow = new google.maps.InfoWindow();
            //Inititalize the drawing manager
            var drawingManager = new google.maps.drawing.DrawingManager({
                drawingMode: google.maps.drawing.OverlayType.POLYGON,
                drawingControl: true,
                drawingControlOptions: {
                    position: google.maps.ControlPosition.TOP_LEFT,
                    drawingModes: [
                        google.maps.drawing.OverlayType.POLYGON
                    ]
                }
            });

            // Style the markers a bit. This will be our listing marker icon.
            var defaultIcon = makeMarkerIcon('0091ff');

            // Create a "highlighted location" marker color for when the user
            // mouses over the marker.
            var highlightedIcon = makeMarkerIcon('FFFF24');

            var bounds = new google.maps.LatLngBounds();
            // The following group uses the location array to create an array of markers on initialize
            for (var i = 0; i < locations.length; i++) {
                var position = locations[i].location;
                var title = locations[i].title;
                //Create a marker per location, and put into markers array
                console.log(title);
                var marker = new google.maps.Marker({
                    map: map,
                    position: position,
                    title: title,
                    icon: defaultIcon,
                    animation: google.maps.Animation.DROP,
                    id: i

                });
                // push the marker to our arrat of markers
                markers.push(marker);

                // Create an onlick event to open an infowindow at each marker
                marker.addListener('click', function () {
                    populateInfoWindow(this, largeInfoWindow);

                });
                // Two event listeners - one for mouseover, one for mouseout,
                // to change the colors back and forth.
                marker.addListener('mouseover', function () {
                    this.setIcon(highlightedIcon);
                });
                marker.addListener('mouseout', function () {
                    this.setIcon(defaultIcon);
                });
                //Extend the boundaries of the map for each marker

                bounds.extend(markers[i].position);
            }
            /// Extend the boundaries of the map for each marker
            map.fitBounds(bounds);

            document.getElementById('show-listings').addEventListener('click', showListings);
            document.getElementById('hide-listings').addEventListener('click', hideListings);
            document.getElementById('toggle-drawing').addEventListener('click', function () {
                toggleDrawing(drawingManager);

            });
            //Add an event listener so that the polygon is captured, call the
            // searchWithinPolygon function. This will show the markers in the polygon,
            // and hide any outside of it
            drawingManager.addListener('overlaycomplete', function (event) {
                //
                //
                if (polygon) {
                    polygon.setMap(null);
                    hideListings();
                }
                //    Switching the drawing mode to the Hand(i.e. No longer drawing).
                drawingManager.setDrawingMode(null);
                //    Creating a new editable polygon from the overlay.
                polygon = event.overlay;
                polygon.setEditable(true);
                //    Search within the polygon
                searchWithinPolygon();
                //    Make sure the search is re-done if the polygon is changed.
                polygon.getPath().addListener('set_at', searchWithinPolygon);
                polygon.getPath().addListener('insert_at', searchWithinPolygon);
            });

            // This function populates the infowindow when the marker is clicked. We'll only allow
            // one infowindow which will open at the marker that is clicked, and populate based
            // on that markers position
            function populateInfoWindow(marker, infowindow) {
                //// Check to make sure the infowindow is not already opened on this marker.
                if (infowindow.marker != marker) {
                    infowindow.marker = marker;
                    infowindow.setContent('');
                    infowindow.open(map, marker);
                    //// Make sure the marker property is cleared if the infowindow is closed.
                    infowindow.addListener('closeclick', function () {
                        infowindow.setMarker(null);
                    });
                    var streetViewService = new google.maps.StreetViewService();
                    var radius = 50;
                    // In case the status is OK, which means the pano was found, compute the
                    // position of the streetview image, then calculate the heading, then get a
                    // panorama from that and set the options
                    function getStreetView(data, status) {
                        if (status == google.maps.StreetViewStatus.OK) {
                            var nearStreetViewLocation = data.location.latLng;
                            var heading = google.maps.geometry.spherical.computerHeading(
                                nearStreetViewLocation, marker.position);
                            infowindow.setContent('<div>' + marker.title + '<br>' + marker.position + '</div><div id="pano"></div>');
                            var panoramaOptions = {
                                position: nearStreetViewLocation,
                                pov: {
                                    heading: heading,
                                    pitch: 30
                                }
                            };
                            var panorama = new google.maps.StreetViewPanorama(
                                document.getElementById('pano'), panoramaOptions);
                        } else {
                            infowindow.setContent('<div>' + marker.title + '<br>' + marker.position + '</div>' +
                                '<div>No Street View Found</div>')
                        }


                    }

                    // Use streetview service to get the closest streetview image within
                    // 50 meters of the markers position
                    streetViewService.getPanoramaByLocation(marker.position, radius, getStreetView());
                    // Open the infowindow on the correct marker.
                    infowindow.open(map, marker);

                }

            }

            // This function will loop through the markers array and display them all.
            function showListings() {
                var bounds = new google.maps.LatLngBounds();
                // Extend the boundaries of the map for each marker and display the marker
                for (var i = 0; i < markers.length; i++) {
                    markers[i].setMap(map);
                    bounds.extend(markers[i].position);
                }
                map.fitBounds(bounds);
            }

            // This function will loop through the listings and hide them all.
            function hideListings() {
                for (var i = 0; i < markers.length; i++) {
                    markers[i].setMap(null);
                }
            }

            // This function takes in a COLOR, and then creates a new marker
            // icon of that color. The icon will be 21 px wide by 34 high, have an origin
            // of 0, 0 and be anchored at 10, 34).
            function makeMarkerIcon(markerColor) {
                var markerImage = new google.maps.MarkerImage(
                    'http://chart.googleapis.com/chart?chst=d_map_spin&chld=1.15|0|' + markerColor +
                    '|40|_|%E2%80%A2',
                    new google.maps.Size(21, 34),
                    new google.maps.Point(0, 0),
                    new google.maps.Point(10, 34),
                    new google.maps.Size(21, 34));
                return markerImage;
            }

            function toggleDrawing(drawingManager) {
                if (drawingManager.map) {
                    drawingManager.setMap(null);
                    //    in case the user drew anything, get rid of the polygon
                    if (polygon) {
                        polygon.setMap(null);
                    }
                } else {
                    drawingManager.setMap(map);
                }

            }

            //    This function hides all the markers outside the polygon
            //    and shows only the ones within it. This is so that the
            //    user can specify the exact area of the search
            function searchWithinPolygon() {
                for (var i = 0; i < markers.length; i++) {
                    if (google.maps.geometry.poly.containsLocation(markers[i].position, polygon)) {
                        markers[i].setMap(map);
                    } else {
                        markers[i].setMap(null);
                    }
                }

            }


        }


    });


}

























