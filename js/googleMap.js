    //set how many cities' weather should be displayed
    var cityWeatherNo = 50;

    //to get number of routes
    var noOfRoutes = "";

    /*The following lines are used for autocompletion function*/
    var autocomplete_start = new google.maps.places.Autocomplete(document.getElementById('start'));
    var autocomplete_end = new google.maps.places.Autocomplete(document.getElementById('end'));
    google.maps.event.addListener(autocomplete_start, 'place_changed', function() {
        var place_start = autocomplete_start.getPlace();
    });
    google.maps.event.addListener(autocomplete_end, 'place_changed', function() {
        var place_end = autocomplete_end.getPlace();
    });

    //declare a global variable of the map, because it is used in several sperated functions
    var map = "";

    //declare an empty array which is used to store markers which need to overly the google map
    var markers = [];

    //declare an empty array which is used to store weather markers which need to overly the google map
    var w_markers = [];

    /*The default value of the center point used in display weather is the Melbourne geo location
     * Later it will be replaced when the search is launched */
    var center_lat = -37.8602828;
    var center_lng = 145.079616;

    /**
     * Initialization of the google map
     */
    function initMap() {
        //initialize the map variable, and set the center point
        map = new google.maps.Map(document.getElementById('map'), {
            draggable: true,
            zoom: 7,
            center: {lat: -37.8602828, lng: 145.079616},
            scaleControl: false,
            scrollwheel: false,
            disableDoubleClickZoom: false
        });

        //show user's current location
        showCurLocation(map);

        console.log("===>initMap() has been called.");
    }

    /**
     * When search button is clicked, te map will be updated
     */
    function cal(){
        /*Initialize those variables which will be used in displaying the google map*/
        var directionsService = new google.maps.DirectionsService;
        var directionsDisplay = new google.maps.DirectionsRenderer;

        //set the map
        directionsDisplay.setMap(map);

        //set the Google response to this specific area
        directionsDisplay.setPanel(document.getElementById('panel'));

        //call the function which actually shows the details of the searching
        calculateAndDisplayRoute(directionsService, directionsDisplay);

        console.log("Before showing accidents => " + accidents_array.length);
        //overlay the accident details to the map
        showAccidentPoints(accidents_array,map);

        console.log("Accidents => " + accidents_array.length);
        console.log("Weather => " + weather_array.length);

        //overlay the weather information the map
        //showWeather(weather_array, map);
        console.log("cal()->showWeather: " + weather_array.length);

        //showCurLocation(map);

        console.log("===>cal() has been called.");
    }

    /**
     * This function allows users to perform another search without refreshing
     */
    function cal_2(){
        showAccidentPoints(accidents_array, map);
    }

    /**
     * To specify user's current location
     * @param map
     */
    function showCurLocation(map){
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                //initialize the current location where users are
                var pos = {
                    lat: position.coords.latitude,
                    lng: position.coords.longitude
                };

                //initialize the marker of the current location
                var marker = new google.maps.Marker({
                    position: pos,
                    map: map,
                    animation:google.maps.Animation.BOUNCE,
                    icon:'../images/cur_location.png'
                });

                //initialize the window to display the exact information
                var infowindow = new google.maps.InfoWindow({
                    content: "You are here"
                });

                //when you move your mouse over the marker it shows the information you want to show
                marker.addListener('mouseover', function(){
                    infowindow.setContent("here you are");
                    infowindow.open(map, this);
                });

                //when you move your mouse out of the marker it shows the information you want to show
                marker.addListener('mouseout', function(){
                   infowindow.close();
                });


            }, function() {
                console.log("clicked");
            });
        }
    }

    /**
     * Overlay the real-time weather information
     * @param weather_array
     * @param map
     */
    function showWeather(weather_array, map){

        //declare a new variable to set the weather marker
        var w_marker = null;

        //To iterate the weather array, each record will be mapped into a marker showing on the map
        for(var i = 0;i < weather_array.length;i++){
            //this is the weather, what the exact weather type
            var condition = weather_array[i].weather[0].main;

            //initialize the marker
            w_marker = new google.maps.Marker({
                position:  {lat:weather_array[i].coord.lat,lng:weather_array[i].coord.lon},
                map: map,
                icon: "http://openweathermap.org/img/w/" + weather_array[i].weather[0].icon + ".png"
            });

            w_markers.push(w_marker);

            //set the infowindow which is used to display weather information when users click the marker
            var contentString = '<div id="content">' +
                '<div id="siteNotice">' +
                '</div>' +
                '<h1 id="firstHeading" class="firstHeading">' + condition + '</h1>' +
                '<div id="bodyContent">' +
                '<p><b>City: </b>' + weather_array[i].name + '<br />' +
                '<b>Temp Range: </b>' + Math.round((weather_array[i].main.temp_min - 273.15),2)  + "°C ~ "
                    + Math.round((weather_array[i].main.temp_max - 273.15),2) + "°C"+ '<br />' +
                '<b>Description: </b>' + weather_array[i].weather[0].description + '<br />' +
                '</div>' +
                '</div>';

            //add the infowindow to the marker
            attachInfoWindowMessage(w_marker, contentString);
        }
    }

    function showWeatherWindow(weather_array){
        //reset the weather window
        document.getElementById('weather_icon').innerHTML = "";
        document.getElementById('city').innerHTML = "";
        document.getElementById('temp').innerHTML = "";
        document.getElementById('desc').innerHTML = "";

        imgSrc = "http://openweathermap.org/img/w/" + weather_array[0].weather[0].icon + ".png";
        console.log("Weather icon" + imgSrc);
        var weather_icon = document.createElement("img");
        document.getElementById('weather_icon').appendChild(weather_icon);
        weather_icon.src = imgSrc;

        document.getElementById('city').innerHTML = "<b>City: </b>" + weather_array[0].name;
        document.getElementById('temp').innerHTML = Math.round((weather_array[0].main.temp_min - 273.15),2)  + "°C ~ "
            + Math.round((weather_array[0].main.temp_max - 273.15),2) + "°C";
        document.getElementById('desc').innerHTML = "<b>Desc: </b>" + weather_array[0].weather[0].description;
    }

    /**
     * According to returned accident points, and transfer them to according markers.
     * @returns {Array: the marker array}
     */
    function getMarkerArray(accidents_array, map){
        console.log("getMarkerArray => " + accidents_array.length + "/" + map);

        //iterate the accidents_array, and each reocrd is shown as a marker
        for(var i = 0; i < accidents_array.length;i++) {
            //initialize the marker
            var marker = new google.maps.Marker({
                position: {lat: accidents_array[i].LATITUDE, lng: accidents_array[i].LONGITUDE},
                map: map,
                animation:google.maps.Animation.DROP,
                icon: 'https://developers.google.com/speed/docs/insights/images/exclamation.png'
            });
            markers[i] = marker;
        }
    }

    /**
     * Push all returned accident points on map
     * @param map: the initialized map
     */
    function showAccidentPoints(accidents_array, map){
        console.log("showAccidentPoints() => " + accidents_array.length);
        //get the marker array first, ensure each record is mapped to a marker to display
        getMarkerArray(accidents_array, map);

        //show accidents information to the map
        //similiar to above function
        for(var i = 0; i < accidents_array.length; i++){
            var time = accidents_array[i].ACCIDENT_TIME;
            var date = (accidents_array[i].ACCIDENT_DATE).split("-");

            //construct the accident information
            var contentString = '<div id="content" style="font-family: Roboto,Arial,sans-serif;color:\#2c2c2c;">'+
                '<div id="siteNotice">'+
                '</div>'+
                '<h1 id="firstHeading" class="firstHeading">Accident</h1>'+
                '<div id="bodyContent">'+
                '<p><b>Date: </b>' + date[2] + "-" + date[1] + "-" + date[0] + '<br />' +
                '<b>Time: </b>' + time.replace(/\.00/,'') + '<br />' +
                '<b>Day: </b>' + accidents_array[i].DAY_OF_WEEK + '<br />' +
                '<b>Road(s): </b>' + accidents_array[i].INITIAL_ROAD_NAME + '</p><br />' +
                '</div>'+
                '</div>';

            //attach the infowindow to each marker
            attachInfoWindowMessage(markers[i], contentString);
        }
    }

    /**
     * In order to attach infoWindow accordingly to markers
     * @param marker
     * @param content
     */
    function attachInfoWindowMessage(marker, content) {
        //to initialize each infowindow
        var infowindow = new google.maps.InfoWindow({
            content: content
        });

        //when users move the cursor on the marker, show the detail information
        marker.addListener('mouseover', function() {
            infowindow.open(marker.get('map'), marker);
        });

        //when you move your mouse out of the marker it shows the information you want to show
        marker.addListener('mouseout', function(){
            infowindow.close();
        });
    }

    /**
     * remove any existing markers from the map.
     */
    function removeExistMarker(map){
        console.log("Markers on map:" + markers.length);
        //set exist markers to null, it is used to clear all markers shown during last round searching
        for (var i = 0; i < markers.length; i++) {
            markers[i].setMap(null);
        }
    }

    /**
     * Remove the weather markers
     * @param w_markers the array holds the weather icons
     * @param map the google map
     */
    function removeWeatherMarker(w_markers){
        for (var i = 0; i < w_markers.length; i++) {
            w_markers[i].setMap(null);
        }
    }

    /**
     * Google map shows exact routes and direction services
     * This is the main function which shows the route information on the map
     * @param directionsService
     * @param directionsDisplay
     */
    function calculateAndDisplayRoute(directionsService, directionsDisplay) {
        //to get the value which users select
        var selectedMode = document.getElementById('mode').value;

        //This is the google JS, just change the origin and destination values
		directionsService.route({
            origin: document.getElementById('start').value,
            destination: document.getElementById('end').value,
            travelMode: google.maps.TravelMode[selectedMode],
            provideRouteAlternatives: true,
            durationInTraffic: true
        }, function(response, status) {
            //when the response is retrieved, then perform following functions
            if (status === google.maps.DirectionsStatus.OK) {
                console.log(response);

                //set the directions(routes)
                directionsDisplay.setDirections(response);

                //get how many routes are returned
                noOfRoutes = response.routes.length;
                console.log("Route No.:" + noOfRoutes);

                //according to number of route returned and dynamically change the number of columns of the table
                setTableColumns(noOfRoutes);

                //to determine which route is the quickest and which route is the shortest
                var shortestRouteIndex = getShortestRoute(noOfRoutes, response);
                var quickestRouteIndex = getQuickestRoute(noOfRoutes, response);

                /*
                 * get the start and the end point latitude and longitude
                 * Then according to these points, we calculate to get the midpoint which will
                 * be used to overlay the weather information
                 */
                //var start_lat = response.routes[0].legs[0].start_location.H;
                //var start_lng = response.routes[0].legs[0].start_location.L;
                //var end_lat = response.routes[0].legs[0].end_location.J;
                //var end_lng = response.routes[0].legs[0].end_location.M;
                //center_lat = (start_lat + end_lat)/2;
                //center_lng = (start_lng + end_lng)/2;

                // to perform get the geo code of the address
                var end_lat = "";
                var end_lng = "";
                var geocoder = new google.maps.Geocoder();
                var address = document.getElementById('end').value;

                geocoder.geocode({'address': address}, function(results, status) {
                    if (status === google.maps.GeocoderStatus.OK) {
                        console.log("===>GeoLocation: " + results[0].geometry.location);

                        console.log(end_lat + "-" + end_lng);
                        end_lat = results[0].geometry.location.lat();
                        end_lng = results[0].geometry.location.lng();

                        //to show the weather information, overlay to the map
                        getWeather(end_lat, end_lng, cityWeatherNo);

                    } else {
                        alert('Geocode was not successful for the following reason: ' + status);
                    }
                });



                console.log("Lat: " + end_lat);
                console.log("Lng: " + end_lng);


                //getWeather(end_lat, end_lng, cityWeatherNo);
                //console.log("===>Weather_array: " + weather_array.length);
                //console.log("===>Center point: " + center_lat + " and " + center_lng);


                //Choosing routes based on distance
                //if(document.getElementById("shortest").checked){
                    //every time it resets first
                    //resetTableCSS();
                    //Changing the css
                    changingCSSByShortest(shortestRouteIndex);
                //}

                //Choosing routes based on time
                //if(document.getElementById("quickest").checked){
                    //every time it resets first
                    //resetTableCSS();
                    //Changing the css
                    changingCSSByQuickest(quickestRouteIndex);
                //}

                //directionDisplay event listener, when users click another route, information will be changed accordingly
                google.maps.event.addListener(directionsDisplay, 'routeindex_changed', function(){
                    //remove exist markers
                    removeExistMarker(map);

                    //get the current route index when the user clicks
                    var index_changed = directionsDisplay.getRouteIndex();

                    /*
                     * According to the changed index, update the accident information and the traffic volume
                     */
                    ajaxPassingTraffic(index_changed);

                    console.log("===>Safety_ratio: " + safety_ratio.length);

                    console.log("Index changed to " + index_changed);
                });


                console.log("This round finished.");
            }
        });
    }


