/**
 * Created by vinson on 12/09/15.
 */

//This is the global variable parsing the accidents json into an array
var accidents_array = "";

//This is the global variable parsing the volume json into an array
var volume_array = "";

//This is the global variable storing weather information
var weather_array = "";

//This is the global variable counting how many times the cal() method has been called
var count = 0;

// This is the global variable counting how many times the weather button has been clicked
var clickTimes = 0;

//Global variable: storing each route's number of accidents
var acc_cal = [];

//Global variable: storing each route's number of accidents
var vol_cal = [];

//Global variable: storing each calculation accordingly to each route (volume/no of accidents)
var safety_ratio = [];

// This is the global variable counting how many times the search button has been clicked
var searchTimes = 0;

// This is the global variable to indicate whether the safest marker is set already
var isSafestIconSet = false;
/**
 * Reset all declared variables to original values.
 */
function reset(){
    //reset all following variables
    accidents_array = "";
    volume_array = "";
    acc_cal = [];
    vol_cal = [];
    safety_ratio = [];

    //reset the table
    noOfRoutes = 0;
    console.log("Reset route No.:" + noOfRoutes);
    resetTableCSS();
    resetTable();

    //clear all contents of panel div
    document.getElementById("panel").innerHTML="";

}

/**
 * This function tells which functions need to be performed
 * when the search button is clicked.
 */
function searchClickEvents(){
    //first to check whether the input is valid or not, all the validation() method
    if(validation() == 1){
        alert("Only Victoria addresses are acceptable.");
    }else if(validation() == 2){
        alert("These two addresses should not be the same.");
    }else if(validation() == 3){
        alert("Please type into start and end addresses.");
    }
    else {
        //Performing another search, then the safest icon should be set back to unset
        isSafestIconSet = false;
        //add another click listener
        successfulJumpToMap();
        //first is to reset those variables
        reset();
        weatherOnMap();
        //to retrieve traffic information from the php side
        getTrafficInformation();
        console.log("Click Times: " + clickTimes);
        if (searchTimes != 0){
            resetGraphCanvas(0);
            /*draw graphs to the website*/
            drawLineGraph(accidents_array, 0);
            drawBarGraph(volume_array, 0);
        }

        searchTimes ++
    }
}

/**
 * Click search and jump to map layer only when performing successful search
 */
function successfulJumpToMap(){
    $('html, body').animate({ scrollTop: $('#map_guidance').offset().top }, 'slow');
}

/**
 * Validation of empty input of the search
 * @returns {boolean}
 */
function validation(){
    //get the textfield values of id start and end
    var getStart = document.getElementById("start").value.toLowerCase();
    var getEnd = document.getElementById("end").value.toLowerCase();

    var start = getStart.replace(/\s/g, '');
    var end = getEnd.replace(/\s/g, '');

    //the addresses input by users must obey the suggested addresses offered by Google,
    //and must include, "victoria, australia" these two words, and these two values should not be the same
    if(start.indexOf("victoria,australia")<0 || end.indexOf("victoria,australia")<0){
        //alert("waht");
        return 1;
    }else if (start == end){
        return 2;
    }else if(start.length === 0 || end.length === 0){
        return 3;
    }else{
        return 0;
    }
}

/**
 * Retrieve accident and traffic volume information of returned routes when it is firstly loaded
 */
function getTrafficInformation(){
    //set the loading div to be visible
    document.getElementById('loading').style.visibility = "visible";

    /*following we use AJAX techniques to retrieve data at the background*/
    //create a new XMLHttpRequest
    var xmlHttp = new XMLHttpRequest();

    //get the textfield values of id start and end
    var start = document.getElementById("start").value;
    var end = document.getElementById("end").value;

    //when the response is loaded successfully
    xmlHttp.onreadystatechange = function(){
        //specific actions can be only performed when the seponse is loaded successfully
        if(4 == xmlHttp.readyState && 200 == xmlHttp.status) {
            //set the loading div to be visible
            document.getElementById('loading').style.visibility = "hidden";
            document.getElementById('panel').style.display = "block";
            document.getElementById('weather_window').style.display = "block";
            document.getElementById('navigation').style.display = "block";
            document.getElementById('weather_btn').style.display = "block";

            //prompt that the current selected route is the first route
            //document.getElementById("r1_header").innerHTML = "<img src='../images/current_route.png' alt='Current Option' />";

            //retrieve the array contains two JSON Strings (accident info and volume info)
            var response = xmlHttp.responseText;
            //set the response information to accidents_array

            //parse the JSON into an array
            accidents_array = JSON.parse(response).accident;
            console.log(accidents_array);
            console.log("1st time accidents: " + accidents_array.length);

            //parse the JSON into an array
            volume_array = JSON.parse(response).trafficVolume;
            console.log("1st time accidents: " + volume_array.length);

            //the maximum number is limited, and if it reaches the limit, we discard the rest and make it complete json
            //var string = accidents_json.substring(0,accidents_json.lastIndexOf("}")+1);
            //var new_accident_json = string.concat("]");


            //Once it is clicked, some of divs should be shown which previous are hidden
            document.getElementById("table").style.display="block";
            document.getElementById("graph").style.display="block";

            //change the CSS of panel, because when it is unclicked, it shows just an image
            //and it is clicked, the actual responses should be set here
            document.getElementById("panel").className = "panel";
            document.getElementById("response_instruction").style.display = "block";

            //add the result to the appropriate place
            document.getElementById("accident_no1").innerHTML = accidents_array.length;

            //store the first route's calculation result
            acc_cal[0] =  accidents_array.length;
            console.log("getAccidentNo()===>" + acc_cal[0]);

            //the following is to calculate the total traffic volume
            var totalVolume = 0;
            for(var i = 0; i < volume_array.length; i++){
                totalVolume += volume_array[i].volume;
            }

            //display the result
            document.getElementById("traffic_volume1").innerHTML = numberWithCommas(totalVolume);

            //store the result to the array
            vol_cal[0] = totalVolume;
            console.log("getTraffic() ===> " + vol_cal);
            //when it is first loaded, just call the cal() method, otherwise call initMap() or cal()
            if (count == 0)
                cal();
            else{
                initMap();
                cal();
            }

            //to calculate the safety ratio
            calFirstSaftyRatio();


            //draw graph, line chart for accidents, bar chart for traffic volume
            drawLineGraph(accidents_array, 0);
            drawBarGraph(volume_array, 0);
            //this is the counter, every time it is called, the number increase 1
            count ++;
        }
    };
    //open the URL to retrieve data, the syntax
    xmlHttp.open("GET", "../dataHandling/centralController.php?startPoint="+start+"&endPoint="+end+"&index=0", true);
    xmlHttp.send();
    console.log("===>getAccidentNo() has been called.");
}

/**
 * To ensure the first count will be displayed properly using the delay.
 */
function calFirstSaftyRatio(){
    //calculate the first route safety ratio, and display it
    var ratio1 = Math.round(vol_cal[0]/acc_cal[0], 2);
    safety_ratio[0] = ratio1;
    document.getElementById("safety1").innerHTML = numberWithCommas(ratio1);
    safety_ratio[0] = ratio1;

    console.info(safety_ratio[0]);
}

/**
 * Monitoring the changing of the index of returned routes and act accordingly to update traffic information
 * @param index
 */
function ajaxPassingTraffic(index){
    //set the loading div to be visible
    document.getElementById('loading').style.visibility = "visible";
    document.getElementById('panel').style.display = "none";
    /*the following is the AJAX technique*/
    //create a new XMLHttpRequest
    var xmlHttp = new XMLHttpRequest();

    //get start and end addresses values
    var start = document.getElementById("start").value;
    var end = document.getElementById("end").value;

    //when the response is loaded successfully
    xmlHttp.onreadystatechange = function(){
        //specific actions can be only performed when the response is loaded successfully
        if(4 == xmlHttp.readyState && 200 == xmlHttp.status) {
            //set the loading div to be invisible when it is loaded successfully
            document.getElementById('loading').style.visibility = "hidden";
            document.getElementById('panel').style.display = "block";

            //set which route is being selected now
            //document.getElementById("r" + (index + 1) + "_header").innerHTML = "<img src='../images/current_route.png' alt='current selection' />";

            //retrieve response text returned from the target URL
            var response = xmlHttp.responseText;

            //the maximum number is limited, and if it reaches the limit, we discard the rest and make it complete json
            //var string = accidents_json.substring(0,accidents_json.lastIndexOf("}")+1);
            //var new_accident_json = string.concat("]");

            //parse the accident JSON into an array
            accidents_array = JSON.parse(response).accident;

            //parse the traffic volume string into an array
            volume_array = JSON.parse(response).trafficVolume;

            //display the result to appropriate space
            var accID = "accident_no" + (index + 1);
            document.getElementById(accID).innerHTML = accidents_array.length;

            //the following is to calculate the total traffic volume
            var totalVolume = 0;
            for(var i = 0; i < volume_array.length; i++){
                totalVolume += volume_array[i].volume;
            }

            //display the traffic volume result
            var volumeID = "traffic_volume" + (index + 1);
            document.getElementById(volumeID).innerHTML = numberWithCommas(totalVolume);

            //set each route's total traffic volume to the array
            switch(index){
                case 0:
                    vol_cal[0] = totalVolume;
                    break;
                case 1:
                    vol_cal[1] = totalVolume;
                    break;
                case 2:
                    vol_cal[2] = totalVolume;
                    break;
            }

            //to monitor the route index changed, and store the result to accidents_array
            switch(index){
                case 0:
                    acc_cal[0] = accidents_array.length;
                    break;
                case 1:
                    acc_cal[1] = accidents_array.length;
                    break;
                case 2:
                    acc_cal[2] = accidents_array.length;
                    break;
            }

            //to monitor the route index, when it is  changed, and store the result to safety_ratio array
            switch(index){
                case 0:
                    calSaftyRatio(0);
                    break;
                case 1:
                    calSaftyRatio(1);
                    break;
                case 2:
                    calSaftyRatio(2);
                    break;
            }

            //if it is the first time loaded, then call the cal() otherwise call cal_2() method
            if (count == 0)
                cal();
            else
                cal_2();

            /* when users click all returned routes, we will calculate which one is the safest*/
            // when all routes are viewed, get which one has the highest safety_ratio
            if(safety_ratio.length == noOfRoutes){
                //reset the table css first beforing showing the final result
                resetTableCSS();
                setTableColumns(noOfRoutes);

                //to determine which route is the safest route
                var max = safety_ratio[0];
                var max_index = 0;
                for (var i = 0; i < safety_ratio.length; i++){
                    console.log("Safety Ratio: " + safety_ratio[i]);
                    if(safety_ratio[i] == "Infinity"){
                        max = safety_ratio[i];
                        max_index = i;
                        break;
                    }
                    if(safety_ratio[i] >= max){
                        max = safety_ratio[i];
                        max_index = i;
                    }
                }

                //set the safest marker
                if(isSafestIconSet == false) {
                    var safestID = "r" + (max_index + 1) + "_yes";
                    //document.getElementById(safestID).innerHTML = "\<img src='../images/safest.png' alt='yes' \/\>";
                    var elem = document.createElement("img");
                    document.getElementById(safestID).appendChild(elem);
                    elem.src = '../images/safest.png';
                    isSafestIconSet = true;
                }


                /*
                 * The following codes are used to determine which route will be highlighted
                 */
                if(max_index == 0) {
                    document.getElementById("r1_header").className = "choicea on";
                    document.getElementById("r1_dis").className = "choicea on";
                    document.getElementById("r1_time").className = "choicea on";
                    document.getElementById("r1_acc").className = "choicea on";
                    document.getElementById("r1_tff").className = "choicea on";
                    document.getElementById("r1_wth").className = "choicea on";
                    document.getElementById("r1_yes").className = "choicea on";
                    document.getElementById("r1_safety").className = "choicea on";
                }
                if(max_index == 1){
                    document.getElementById("r2_header").className = "choiceb on";
                    document.getElementById("r2_dis").className = "choiceb on";
                    document.getElementById("r2_time").className = "choiceb on";
                    document.getElementById("r2_acc").className = "choiceb on";
                    document.getElementById("r2_tff").className = "choiceb on";
                    document.getElementById("r2_wth").className = "choiceb on";
                    document.getElementById("r2_yes").className = "choiceb on";
                    document.getElementById("r2_safety").className = "choiceb on";
                }
                if(max_index == 2){
                    document.getElementById("r3_header").className = "choicec on";
                    document.getElementById("r3_dis").className = "choicec on";
                    document.getElementById("r3_time").className = "choicec on";
                    document.getElementById("r3_acc").className = "choicec on";
                    document.getElementById("r3_tff").className = "choicec on";
                    document.getElementById("r3_wth").className = "choicec on";
                    document.getElementById("r3_yes").className = "choicec on";
                    document.getElementById("r3_safety").className = "choicec on";
                }
            }

            //clear the exist graphs then draw the new in the same place
            resetGraphCanvas(index);

            /*draw graphs to the website*/
            drawLineGraph(accidents_array, index);
            drawBarGraph(volume_array, index);

            count++;
        }
    };

    //the syntax, open the target url to retrieve the data.
    xmlHttp.open("GET", "../dataHandling/centralController.php?startPoint="+start+"&endPoint="+end+"&index="+index, true);
    xmlHttp.send();
}

/**
 * To calculate each route's safety ratio
 * @param i
 */
function calSaftyRatio(i){
    //monitor the index, when it is changed, calculate the safety_ratio and display it
    safety_ratio[i] = Math.round(vol_cal[i] / acc_cal[i], 2);
    document.getElementById("safety" + (i + 1)).innerHTML = numberWithCommas(safety_ratio[i]);
    console.info(safety_ratio[0]);
}

/**
 * Retreive weather information in melbourne
 */
function getWeather(lat, lng, cityNo){
    //create a new XMLHttpResponse object
    var xmlHttp = new XMLHttpRequest();

    //dynamically create the URL, the center location is calculated as the midpoint of start and end points
    var url = "http://api.openweathermap.org/data/2.5/find?lat=" + lat + "&lon=" + lng + "&cnt=" + cityNo + "&appid=4b3d6bd437a4005431163f261019c8c2";
    console.log("URL" + url);

    //when the response is loaded successfully
    xmlHttp.onreadystatechange = function(){
        //specific actions can be only performed when the response is loaded successfully
        if(4 == xmlHttp.readyState && 200 == xmlHttp.status) {
            //get the response text
            var weatherJSON = xmlHttp.responseText;

            //parse the response text into an array
            var weather = JSON.parse(weatherJSON);
            weather_array = weather.list;

            //add weather name into table
            var weatherInfoArray = document.getElementsByClassName('weatherInfo');
            //set the weather description to the table
            for (var i = 0; i < weatherInfoArray.length; i++) {
                weatherInfoArray[i].innerHTML = weather_array[0].weather[0].main;
            }

            //call the showWeather() function and to overlay weather icons
            //showWeather(weather_array, map);
            showWeatherWindow(weather_array);
        }
    };
    //xmlHttp.open("GET", "http://api.openweathermap.org/data/2.5/weather?q=melbourne,au", true);
    //retrieve response information of the target URL
    xmlHttp.open("GET", url, true);
    xmlHttp.send();

    console.log("===>getWeather() has been called.");
}

/**
 * According to the response, determine which route is the shortest.
 * @param noOfRoutes
 * @param response
 * return {Array: a sorted array ranks routes based on distance}
 */
function getShortestRoute(noOfRoutes, response){
    //an empty array, which will be used to store results later
    var resultArray = [];

    //this is used to get shortest route index
    var shortestIndex = 0;

    //to get the response information mentioning the distance value
    var shortestDistance = response.routes[0].legs[0].distance.value;

    // to determine which route has the shortest route
    for(var i = 0; i < noOfRoutes; i++){
        document.getElementById("distance" + (i + 1)).innerHTML = response.routes[i].legs[0].distance.text;
        var curIndex = i;
        var distance = response.routes[i].legs[0].distance.value;
        if (distance <= shortestDistance) {
            shortestIndex = curIndex;
            resultArray.unshift(curIndex);
        }else
            resultArray.push(curIndex);
    }

    console.log("Shortest: " + resultArray[0]);

    return resultArray[0];
}

/**
 * According to the response, determine which route is the quickest.
 * @param noOfRoutes
 * @param response
 */
function getQuickestRoute(noOfRoutes, response){
    //an empty array, which will be used to store results later
    var resultArray = [];

    //this is used to get quickest route index
    var quickestIndex = 0;

    //to get the response information mentioning the time value
    var quickestRoute = response.routes[0].legs[0].duration.value;

    // to determine which route has the shortest time
    for(var i = 0; i < noOfRoutes; i++){
        document.getElementById("time" + (i + 1)).innerHTML = response.routes[i].legs[0].duration.text;
        var curIndex = i;
        var time = response.routes[i].legs[0].duration.value;
        if (time <= quickestRoute) {
            quickestIndex = curIndex;
            resultArray.unshift(curIndex);
        }else
            resultArray.push(curIndex);
    }

    console.log("Quickest: " + resultArray[0]);

    return resultArray[0];
}

/**
 * The function is to change the css of table based on shortest distance
 * @param shortestRouteIndex
 */
function changingCSSByShortest(shortestRouteIndex){
    /*
     * following codes are to set the shortest icon for the shortest route
     */
    if(shortestRouteIndex == 0) {
        var elem = document.createElement("img");
        document.getElementById("r1_yes").appendChild(elem);
        elem.src = '../images/shortest.png';
        document.getElementById("distance1").style.color = "#000";
    }

    if(shortestRouteIndex == 1) {
        var elem = document.createElement("img");
        document.getElementById("r2_yes").appendChild(elem);
        elem.src = '../images/shortest.png';
        document.getElementById("distance2").style.color = "#000";
    }

    if(shortestRouteIndex == 2) {
        var elem = document.createElement("img");
        document.getElementById("r3_yes").appendChild(elem);
        elem.src = '../images/shortest.png';
        document.getElementById("distance3").style.color = "#000";
    }
}

/**
 * The function is to change the css of table based on shortest time
 * @param quickestRouteIndex
 */
function changingCSSByQuickest(quickestRouteIndex){
    /*
     * following codes are to set the quickest icon for the quickest route
     */
    if(quickestRouteIndex == 0) {
        //document.getElementById("r1_yes").innerHTML = "\<img src='../images/quickest.png' alt='yes' \/\>";
        var elem = document.createElement("img");
        document.getElementById("r1_yes").appendChild(elem);
        elem.src = '../images/quickest.png';
        document.getElementById("time1").style.color = "#000";
    }

    if(quickestRouteIndex == 1) {
        //document.getElementById("r2_yes").innerHTML = "\<img src='../images/quickest.png' alt='yes' \/\>";
        var elem = document.createElement("img");
        document.getElementById("r2_yes").appendChild(elem);
        elem.src = '../images/quickest.png';
        document.getElementById("time2").style.color = "#000";
    }

    if(quickestRouteIndex == 2) {
        //document.getElementById("r3_yes").innerHTML = "\<img src='../images/quickest.png' alt='yes' \/\>";
        var elem = document.createElement("img");
        document.getElementById("r3_yes").appendChild(elem);
        elem.src = '../images/quickest.png';
        document.getElementById("time3").style.color = "#000";
    }
}

/**
 * To recover to original css of the table
 * That is to say, to remove the highligh effect of the table
 */
function resetTableCSS(){
    //document.getElementById("r1_header").innerHTML = "Route 1";
    document.getElementById("r1_header").className = "choicea";
    document.getElementById("r1_dis").className = "choicea";
    document.getElementById("r1_time").className = "choicea";
    document.getElementById("r1_acc").className = "choicea";
    document.getElementById("r1_tff").className = "choicea";
    document.getElementById("r1_wth").className = "choicea";
    document.getElementById("r1_yes").className = "choicea";
    document.getElementById("r1_safety").className = "choicea";

    //document.getElementById("r2_header").innerHTML = "Route 2";
    document.getElementById("r2_header").className = "choiceb";
    document.getElementById("r2_dis").className = "choiceb";
    document.getElementById("r2_time").className = "choiceb";
    document.getElementById("r2_acc").className = "choiceb";
    document.getElementById("r2_tff").className = "choiceb";
    document.getElementById("r2_wth").className = "choiceb";
    document.getElementById("r2_yes").className = "choiceb";
    document.getElementById("r2_safety").className = "choiceb";

    //document.getElementById("r3_header").innerHTML = "Route 3";
    document.getElementById("r3_header").className = "choicec";
    document.getElementById("r3_dis").className = "choicec";
    document.getElementById("r3_time").className = "choicec";
    document.getElementById("r3_acc").className = "choicec";
    document.getElementById("r3_tff").className = "choicec";
    document.getElementById("r3_wth").className = "choicec";
    document.getElementById("r3_yes").className = "choicec";
    document.getElementById("r3_safety").className = "choicec";


    //set all columns back to visible
    document.getElementById("r3_header").style.visibility = "visible";
    document.getElementById("r3_dis").style.visibility = "visible";
    document.getElementById("r3_time").style.visibility = "visible";
    document.getElementById("r3_yes").style.visibility = "visible";
    document.getElementById("r3_acc").style.visibility = "visible";
    document.getElementById("r3_tff").style.visibility = "visible";
    document.getElementById("r3_wth").style.visibility = "visible";
    document.getElementById("r3_safety").style.visibility = "visible";

    document.getElementById("r2_header").style.visibility = "visible";
    document.getElementById("r2_dis").style.visibility = "visible";
    document.getElementById("r2_time").style.visibility = "visible";
    document.getElementById("r2_yes").style.visibility = "visible";
    document.getElementById("r2_acc").style.visibility = "visible";
    document.getElementById("r2_tff").style.visibility = "visible";
    document.getElementById("r2_wth").style.visibility = "visible";
    document.getElementById("r2_safety").style.visibility = "visible";

    document.getElementById("r1_header").style.visibility = "visible";
    document.getElementById("r1_dis").style.visibility = "visible";
    document.getElementById("r1_time").style.visibility = "visible";
    document.getElementById("r1_yes").style.visibility = "visible";
    document.getElementById("r1_acc").style.visibility = "visible";
    document.getElementById("r1_tff").style.visibility = "visible";
    document.getElementById("r1_wth").style.visibility = "visible";
    document.getElementById("r1_safety").style.visibility = "visible";
}

/**
 * reset the table to clear the icons based on last searching
 */
function resetTable(){
    //set back to original image
    document.getElementById('r1_yes').innerHTML = '<img src="i/check.png" alt="yes" />';
    document.getElementById('r2_yes').innerHTML = '<img src="i/check.png" alt="yes" />';
    document.getElementById('r3_yes').innerHTML = '<img src="i/check.png" alt="yes" />';

    //set back to original text color
    document.getElementById('distance1').style.color = "#fff";
    document.getElementById('distance2').style.color = "#fff";
    document.getElementById('distance3').style.color = "#fff";

    document.getElementById('time1').style.color = "#fff";
    document.getElementById('time2').style.color = "#fff";
    document.getElementById('time3').style.color = "#fff";

}

/**
 * According to number of returned routes, the table should show according columns
 * @param routeNo
 */
function setTableColumns(routeNo){
    //if the number of routes equals to 2, than the third column should not be shown
    if(routeNo == 2){
        document.getElementById("r3_header").style.visibility = "hidden";
        document.getElementById("r3_dis").style.visibility = "hidden";
        document.getElementById("r3_time").style.visibility = "hidden";
        document.getElementById("r3_yes").style.visibility = "hidden";
        document.getElementById("r3_acc").style.visibility = "hidden";
        document.getElementById("r3_tff").style.visibility = "hidden";
        document.getElementById("r3_wth").style.visibility = "hidden";
        document.getElementById("r3_safety").style.visibility = "hidden";
    }

    //if the number of routes equals to 1, than the second and the third column should not be shown
    if(routeNo == 1){

        var elem = document.createElement("img");
        document.getElementById("r1_yes").appendChild(elem);
        elem.src = '../images/safest.png';

        document.getElementById("r1_header").className = "choicea on";
        document.getElementById("r1_dis").className = "choicea on";
        document.getElementById("r1_time").className = "choicea on";
        document.getElementById("r1_acc").className = "choicea on";
        document.getElementById("r1_tff").className = "choicea on";
        document.getElementById("r1_wth").className = "choicea on";
        document.getElementById("r1_yes").className = "choicea on";
        document.getElementById("r1_safety").className = "choicea on";

        document.getElementById("r2_header").style.visibility = "hidden";
        document.getElementById("r2_dis").style.visibility = "hidden";
        document.getElementById("r2_time").style.visibility = "hidden";
        document.getElementById("r2_yes").style.visibility = "hidden";
        document.getElementById("r2_acc").style.visibility = "hidden";
        document.getElementById("r2_tff").style.visibility = "hidden";
        document.getElementById("r2_wth").style.visibility = "hidden";
        document.getElementById("r2_safety").style.visibility = "hidden";

        document.getElementById("r3_header").style.visibility = "hidden";
        document.getElementById("r3_dis").style.visibility = "hidden";
        document.getElementById("r3_time").style.visibility = "hidden";
        document.getElementById("r3_yes").style.visibility = "hidden";
        document.getElementById("r3_acc").style.visibility = "hidden";
        document.getElementById("r3_tff").style.visibility = "hidden";
        document.getElementById("r3_wth").style.visibility = "hidden";
        document.getElementById("r3_safety").style.visibility = "hidden";
    }

    //if there is no route retrieved, then 404 picture should show
    if(routeNo == 0){
        document.getElementById("table").className = "table_404";
    }
}

/**
 * Remove current route option
 */
//function removeCurRouteMarker(){
//    document.getElementById("r1_header").innerHTML = "Route 1";
//    document.getElementById("r2_header").innerHTML = "Route 2";
//    document.getElementById("r3_header").innerHTML = "Route 3";
//}

/**
 * Line chart width and height according to div size
 * @param accidents_array
 */
function drawLineGraph(accidents_array, index){
    //initialize an empty array
    var dowValue = [0,0,0,0,0,0,0];

    //to see the actual number of accidents happend in days of the week
    //According to response information, calculate exact number of each day in the week, and save it to the array
    for (var i = 0; i < accidents_array.length; i++) {
        switch (accidents_array[i].DAY_OF_WEEK) {
            case "Sunday":
                dowValue[6] += 1;
                break;
            case "Monday":
                dowValue[0] += 1;
                break;
            case "Tuesday":
                dowValue[1] += 1;
                break;
            case "Wednesday":
                dowValue[2] += 1;
                break;
            case "Thursday":
                dowValue[3] += 1;
                break;
            case "Friday":
                dowValue[4] += 1;
                break;
            case "Saturday":
                dowValue[5] += 1;
                break;
        }
    }

    //set the chartJS data
    var chartData = {
        //values of x-axis
        labels: ["MON", "TUE", "WED", "THU", "FRI", "SAT", "SUN"],

        //color of the graph, stroke and the actual data array which needs to be represented.
        datasets: [
            {
                //fillColor: "rgba(220,220,220,0.2)",
                //strokeColor: "rgba(220,220,220,1)",
                //pointColor: "rgba(220,220,220,1)",
                //pointStrokeColor: "#fff",
                fillColor : "rgba(172,194,132,0.4)",
                strokeColor : "#ACC26D",
                pointColor : "#fff",
                pointStrokeColor : "#9DB86D",
                data: dowValue
            }
        ]
    }

    //get the canvas where we want to draw the graph
    var chart = document.getElementById("line").getContext("2d");
    //change the line graph title (specify which route it refers to)
    //document.getElementById("line_title").innerHTML = "Route " + (index + 1) + " - Daily Accidents";

    //draw the graph on the canvas
    new Chart(chart).Line(chartData, {scaleShowGridLines: false});
}

/**
 * Draw the histogram of the traffic volume
 * @param volume_array
 */
function drawBarGraph(volume_array, index){
    //initialize an empty array to hold the values of labels
    var volume_labels = [];

    //initilize an empty array to hold the exact data
    var volume_data = [];

    //according to volume array, store those information into the array
    for(var i = 0; i < volume_array.length; i++){
        if(volume_array[i].roadName != null)
            volume_labels.push(volume_array[i].roadName);
        else
            volume_labels.push("Unmatched");

        volume_data.push(volume_array[i].volume);
    }

    //set the chartJS data
    var chartData = {
        //values of x-axis
        labels: volume_labels,
        //color of the graph, stroke and the actual data array which needs to be represented.
        datasets: [
            {
                //fillColor : "rgba(172,194,132,0.4)",
                //strokeColor : "#ACC26D",
                fillColor : "rgba(70,130,180,0.4)",
                strokeColor : "rgba(72,174,209,0.4)",
                //pointColor : "#fff",
                //pointStrokeColor : "#9DB86D",
                data: volume_data
            }
        ]
    }

    //get the canvas where we want to draw the graph
    var chart = document.getElementById("bar").getContext("2d");

    //change the bar graph title (specify which route it refers to)
    //document.getElementById("bar_title").innerHTML = "Route " + (index + 1) + " - Daily Traffic Volume";

    //draw the graph on the canvas
    new Chart(chart).Bar(chartData, {scaleShowGridLines: false});
}

/**
 * To Format the numbers with commas
 * @param x
 * @returns {string}
 */
function numberWithCommas(x) {
    return x.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

/**
 * Show or hide weather markers on the map
 */
function weatherOnMap(){
    if(clickTimes % 2 === 0){
        removeWeatherMarker(w_markers);
    }else{
        showWeather(weather_array, map);
    }

    clickTimes ++;
}

/**
 * To remove the graph when performing the next search
 */
function resetGraphCanvas(index){
    document.getElementById("graph_div").innerHTML = "";
    document.getElementById("graph_div").innerHTML = '<div class="col-md-6 specialty-info wow fadeInLeft animated" data-wow-delay="0.5s" style="visibility: visible; -webkit-animation-delay: 0.5s;">'
        + '<b id="line_title" style="font-family: Roboto,Arial,sans-serif;color: #708090">Route ' + (index + 1) + ' - Average Daily <span style="font-size: 16px; color: #ACC26D"> Accidents</span> (2010 - 2014)</b>'
        + '<div style="float: left; width: 100%; height: 400px">'
        + '<canvas id="line" style="width:100%;height: 100%;"></canvas>'
        + '</div>'
        + '</div>'
        + '<div class="col-md-6 specialty-info">'
        + '<b id="bar_title" style="font-family: Roboto,Arial,sans-serif;color: #708090">Route ' + (index + 1) + ' - Average Daily <span style="font-size: 16px; color: #48aed1">Traffic Volume</span> (2010 - 2014)</b>'
        + '<div style="float: left; width: 100%; height: 400px">'
        + '<canvas id="bar" style="width:100%;height: 100%;"></canvas>'
        + '</div>'
        + '</div>';
}