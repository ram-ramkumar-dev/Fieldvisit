@extends('layouts.app')

@section('content') 
<style>
        #map {
            height: 400px;
            width: 100%;
        }
        #pano {
            height: 400px;
            width: 100%;
        }
        #search-input {
            width: 300px;
            margin: 10px 0;
        }
    </style>
    <input id="search-input" class="controls" type="text" placeholder="Search for a location">
    <div id="map"></div>
    <div id="pano"></div>
 <!-- Asynchronous loading of the Google Maps API -->
 <script>
        function initMap() {
            const defaultLocation = { lat: 3.1319, lng: 101.6841 };
            const map = new google.maps.Map(document.getElementById("map"), {
                center: defaultLocation,
                zoom: 14,
            });

            const panorama = new google.maps.StreetViewPanorama(
                document.getElementById("pano"),
                {
                    position: defaultLocation,
                    pov: {
                        heading: 34,
                        pitch: 10,
                    },
                }
            );

            map.setStreetView(panorama);

            const input = document.getElementById("search-input");
            const searchBox = new google.maps.places.SearchBox(input);

            map.addListener("bounds_changed", () => {
                searchBox.setBounds(map.getBounds());
            });

            searchBox.addListener("places_changed", () => {
                const places = searchBox.getPlaces();

                if (places.length == 0) {
                    return;
                }

                const place = places[0];

                if (!place.geometry || !place.geometry.location) {
                    console.log("Returned place contains no geometry");
                    return;
                }

                map.setCenter(place.geometry.location);
                panorama.setPosition(place.geometry.location);

                const marker = new google.maps.Marker({
                    map,
                    position: place.geometry.location,
                });
            });
        }

        // Load the Google Maps script asynchronously
        function loadScript(src, callback) {
            const script = document.createElement('script');
            script.src = src;
            script.defer = true;
            script.async = true;
            script.onload = callback;
            document.head.appendChild(script);
        }

        loadScript('https://maps.googleapis.com/maps/api/js?key=AIzaSyBfk2lf9GDygZA8S95qs4Q94pRYrEjls8M&libraries=places&callback=initMap');
    </script>
    
@endsection  