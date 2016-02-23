<?php
/**
 * Created by PhpStorm.
 * User: hongjizhou
 * Date: 06/09/15
 * Time: 13:11
 */


header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:POST,GET');
header('Access-Control-Allow-Credentials:true');
header("Content-Type: application/json;charset=utf-8");

require('accessDB.php');
require('accessGoogleMap.php');
//require('GetResultFromDB.php');
require('Step.php');
//require("TrafficVolume.php");
//require('dayVolume.php');
error_reporting( E_ALL&~E_NOTICE );

//====================================================================


/*if ($_SERVER["REQUEST_METHOD"] == "GET") {
    getAllAccident();
}*/
$result = getAllAccident();
echo $result;

/**
 *central funciton to get accident
 */
function getAllAccident(){
    $getStart = $_REQUEST["startPoint"];
    $getEnd = $_REQUEST["endPoint"];
    $startPoint = str_replace(' ', '', $getStart);
    $endPoint = str_replace(' ', '', $getEnd);
    $index = $_REQUEST["index"];
//    $startPoint = "Caulfield,vic";
//    $endPoint = "Carengie,vic";
//    $index = 1;
    $r_googleMAPAPI_json = getRoute($startPoint, $endPoint);
    $StepArray = getSteps($r_googleMAPAPI_json, $index);
    $accidents = getAccident($StepArray);

    return json_encode($accidents);
//    return $r_googleMAPAPI_json;
}


//======================================================================
//this funciton is to get the accident JSON from database
//input is an array for the google steps
//output is a JSON string contains Accident date, time, latitude, longitude, road name and count
/**
 * @param $stepArray google result from getSteps
 * @return string an accident array in json
 */
function getAccident($stepArray)
{

    $queryRoad = null;

//    get the road name from stepArray and put into one string
    foreach ($stepArray as $step) {
        $road = $step->getRoadName();

//        $nRoad = "ROAD_NAME%3D'" . "%25" . $road . "%25'%20or%20";
        $nRoad = "INITIAL_ROAD_NAME%3D'".$road."'%20or%20ENDING_ROAD_NAME%3D'".$road."'%20or%20";

        $newRoad = str_replace(" ", "%20", $nRoad);

        $queryRoad .= $newRoad;

    }
    //cut the end extra part
    $query = substr($queryRoad, 0, strlen($queryRoad) - 8);

//    form a url to get connect with DB


    $curlAccident = "https://dsp-acer-believe.cloud.dreamfactory.com:443/rest/DigisoftDB/ACCIDENTS?filter=$query";


    //use the getResult Function to get result
    $result = getResult($curlAccident);
    $array_result = json_decode($result);

    //get result into array
    $records = $array_result->record;
    $accident_array = array();

    foreach ($stepArray as $step) {

        foreach ($records as $record) {
            //if the roadname is equal description, execute
            if (stristr($record->INITIAL_ROAD_NAME, $step->getRoadName())||stristr($record->ENDING_ROAD_NAME, $step->getRoadName())) {
                //if the latitude and longtitude is in the middle of the startpoint and end point, put the accident into array
                if ($record->LATITUDE != min($step->getStartLocationLat(), $step->getEndLocationLat(), $record->LATITUDE) && $record->LATITUDE != max($step->getStartLocationLat(), $step->getEndLocationLat(), $record->LATITUDE) &&
                    $record->LONGITUDE != min($step->getStartLocationLng(), $step->getEndLocationLng(), $record->LONGITUDE) && $record->LONGITUDE != max($step->getStartLocationLng(), $step->getEndLocationLng(), $record->LONGITUDE)
                ) {
                        array_push($accident_array, $record);

                }

            }
        }
    }

   // $result = array("result" => $accident_array);

    //return $result;
    return $accident_array;
}