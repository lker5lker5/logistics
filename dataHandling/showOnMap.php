<!DOCTYPE html>
<html>
<head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Info windows</title>
    <style>
        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }
        #map {
            height: 100%;
        }
    </style>
</head>
<body>
<label>Point A：</label>
<input type="text" id="pointA" />
<label>Point B：</label>
<input type="text" id="pointB" />
<button id="getRoute">GetRoute</button>
<div id="map"></div>
<?php
//require('Accident.php');
//$startPoint = "Caulfield+station,vic";
//$endPoint = "boxhill+station,vic";
//$r1 = getRoute($startPoint, $endPoint);
//
//$stepArray = getSteps($r1);
//$r = getAccident($stepArray);
//
//$json_accident =  json_encode($r);
$json_accident = file_get_contents('TestAccident.json');
//$array = json_decode($json_accident);
?>
<script src="http://apps.bdimg.com/libs/jquery/1.11.1/jquery.js"></script>


<script>

    // This example displays a marker at the center of Australia.
    // When the user clicks the marker, an info window opens.

    var obj = null;

    $(document).ready(function(){
        $("#getRoute").click(function(){
            $.ajax({
                type: "GET",
                url: "http://localhost:63342/test/Digisoft_DC2/getAccidentController.php?startPoint="+$("#pointA").val()+"&endPoint="+$("#pointB").val(),
                dataType: "json",
                success: function(data) {

                    obj = JSON.parse(data);
                    initMap();
                    alert("finish");
                    // if (data.success) {
//                    $("#Route").html(data[0].ACCIDENT_NO);
//                    initMap(data);
                    // } else {
                    // $("#Route").html("Error：" + data.msg);
                    //}
                },
                error: function(jqXHR){
                    alert("Error：" + jqXHR.status);
                },
            });
        });

    });

//    var obj = JSON.parse('<?php //echo $json_accident ?>//');

    function initMap() {
        var lat = -37.8773737;
        var lng = 145.0414211;

        var caulfield = {lat: lat, lng: lng};


//        var obj = JSON.parse(data);

        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 15,
            center: caulfield
        });

        for (var i = 0; i < obj.length - 1; i++) {
            var marker = new google.maps.Marker({
                position: {lat: obj[i].LATITUDE, lng: obj[i].LONGITUDE},
                map: map
            });


            var contentString = '<div id="content">' +
                '<div id="siteNotice">' +
                '</div>' +
                '<h1 id="firstHeading" class="firstHeading">Accident</h1>' +
                '<div id="bodyContent">' +
                '<p><b>Date: </b>' + obj[i].ACCIDENT_DATE + '<br />' +
                '<b>Time: </b>' + obj[i].ACCIDENT_TIME + '<br />' +
                '<b>Day: </b>' + obj[i].DAY_OF_WEEK + '<br />' +
                '<b>Road(s): </b>' + obj[i].ROAD_NAME + '<br />' +
                '</div>' +
                '</div>';


            var infowindow = new google.maps.InfoWindow({
//                 content: contentString
            });

            marker.addListener('click', function () {
                infowindow.setContent(contentString);
                infowindow.open(map, this);
            });


        }


    }





</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?signed_in=true&callback=initMap"></script>
</body>
</html>