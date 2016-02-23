<?php
/**
 * Created by PhpStorm.
 * User: hongjizhou
 * Date: 02/09/15
 * Time: 22:34
 */
require('ConnectToDreamFacory.php');
//require('GoogleR.json');
require('AnalyseJSON.php');
require('GetRoute.php');


//this funciton is to get the accident JSON from database
//input is an array for the google steps
//output is a JSON string contains Accident date, time, latitude, longitude, road name and count
function getAccident($stepArray)
{

    $queryRoad = null;

//    get the road name from stepArray and put into one string
    foreach ($stepArray as $step) {
        $road = $step->getRoadName();
//        echo $road . "\n";
        $nRoad = "ROAD_NAME%20like%20'" . "%25" . $road . "%25'%20or%20";

        $newRoad = str_replace(" ", "%20", $nRoad);

        $queryRoad .= $newRoad;

    }
    //cut the end extra part
    $query = substr($queryRoad, 0, strlen($queryRoad) - 8);

//    form a url to get connect with DB
    $curlAccident = "https://dsp-acer-believe.cloud.dreamfactory.com:443/rest/DigisoftDB/accidents?filter=$query&include_count=true";

    //use the getResult Function to get result
    $result = getResult($curlAccident);
    $array_result = json_decode($result);

    //get result into array
    $records = $array_result->record;
    $accident_array = array();

    foreach ($stepArray as $step) {

//        echo $step->getRoadName() . "\n";
        foreach ($records as $record) {
            //if the roadname is equal description, execute
            if (stristr($record->ROAD_NAME, $step->getRoadName())) {
                //if the latitude and longtitude is in the middle of the startpoint and end point, put the accident into array
                if ($record->LATITUDE != min($step->getStartLocationLat(), $step->getEndLocationLat(), $record->LATITUDE) && $record->LATITUDE != max($step->getStartLocationLat(), $step->getEndLocationLat(), $record->LATITUDE) && $record->LONGITUDE != min($step->getStartLocationLng(), $step->getEndLocationLng(), $record->LONGITUDE) && $record->LATITUDE != max($step->getStartLocationLat(), $step->getEndLocationLat(), $record->LATITUDE) && $record->LONGITUDE != max($step->getStartLocationLng(), $step->getEndLocationLng(), $record->LONGITUDE)) {
//                    echo $record->LATITUDE.",".$record->LONGITUDE."\n";
                    array_push($accident_array, $record);
                }

            }
        }
    }

//    echo count($accident_array);
    return $accident_array;



}


//$startPoint = "Caulfield+station,vic";
//$endPoint = "carnegie+station,vic";
//$r1 = getRoute($startPoint, $endPoint);
//
//$stepArray = getSteps($r1);
//$r = getAccident($stepArray);
//
//echo json_encode($r);
