<?php
/**
 * Created by PhpStorm.
 * User: hongjizhou
 * Date: 22/09/15
 * Time: 11:01
 */

header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:POST,GET');
header('Access-Control-Allow-Credentials:true');
header("Content-Type: application/json;charset=utf-8");

require('accessDB.php');
require('accessGoogleMap.php');
require('Step.php');
require('TrafficVolume.php');
require('dayVolume.php');
error_reporting( E_ALL&~E_NOTICE );

//====================================================================


/*if ($_SERVER["REQUEST_METHOD"] == "GET") {
    getAllAccident();
}*/
$result = getAllAccident();
//$json = json_decode($result);
//var_dump($json->accident);
//var_dump($json->trafficVolume);
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
//    echo $r_googleMAPAPI_json;
    $StepArray = getSteps($r_googleMAPAPI_json, $index);
    $stepArray2 = $StepArray;
//    echo json_encode($StepArray);
    $accidents = getAccident($StepArray);
//    echo json_encode($accidents);
    $trafficVolume = getDataFromDB($stepArray2);
//    echo json_encode($trafficVolume);
    $result = array("accident"=>$accidents,"trafficVolume"=>$trafficVolume);

    return json_encode($result);
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

/*
 * funciton to get data from database
 */
function getDataFromDB($stepArray)
{


    $stepFromDB = array();

    $queryRoad = null;

    //take the roadName from array into a strign to get all data
    foreach($stepArray as $step){
        $road = $step->getRoadName();

        $nRoad = "HMGNS_LNK_DESC%3D'".$road."'%20or%20";

        $newRoad = str_replace(" ", "%20", $nRoad);

        $queryRoad .= $newRoad;

    }

    //cut the end of useless %20
    $query =  substr($queryRoad,0, strlen($queryRoad)-8);

    $curl = "https://dsp-acer-believe.cloud.dreamfactory.com:443/rest/DigisoftDB/traffic_volume?filter=$query";

    //get the result
    $result = getResult($curl);

    //select the proper one for the each step
    foreach ($stepArray as $step) {

        $pTVs = getDBObject($result);
        $i = 0;
        $max = 0;
        $maxElement = null;
        //get all potential road and compare select the one mid point near the start point
        if (sizeof($pTVs) == 0) {

        } else if (sizeof($pTVs) == 1) {
            $maxElement = $pTVs[0];
        } else {
            while (next($pTVs)) {

                $dis1 = sqrt(abs(($step->getStartLocationLat() - $pTVs[$i]->getMinpntLat()) / ($step->getStartLocationLng() - $pTVs[$i]->getMinpntLng())));
                $max = max($dis1, $max);
                if ($dis1 == $max) {
                    $maxElement = $pTVs[$i];
                }
//                foreach($pTVs as $pTV){
//                    if ($pTV->getMinpntLat() != min($step->getStartLocationLat(), $step->getEndLocationLat(), $pTV->getMinpntLat()) && $pTV->getMinpntLat() != max($step->getStartLocationLat(), $step->getEndLocationLat(), $pTV->getMinpntLat()) &&
//                        $pTV->getMinpntLng() != min($step->getStartLocationLng(), $step->getEndLocationLng(), $pTV->getMinpntLng()) && $pTV->getMinpntLng() != max($step->getStartLocationLng(), $step->getEndLocationLng(), $pTV->getMinpntLng()))
//                    {
//                        $maxElement = $pTV;
//                    }
//                }

                $i++;
            }
        }


        if ($maxElement != null) {
            $maxElement->setRoadName($step->getRoadName());
            $maxElement->setRoadNameURLkey("e?");

            array_push($stepFromDB, $maxElement);
        }

    }


    return $stepFromDB;
}

/**
 * This function is for the json for each road into object
 * @param $json
 * @return array
 */
function getDBObject($json)
{
    $jsonObjects = json_decode($json);
    $potentialTVs = array();
    foreach ($jsonObjects->record as $jsonObject) {
        $pTV = new TrafficVolume();
        $pTV->setMinpntLat($jsonObject->MIDPNT_LAT);
        $pTV->setMinpntLng($jsonObject->MIDPNT_LON);
        $pTV->setHmgnsId($jsonObject->HMGNS_ID);
        $pTV->setVolume($jsonObject->TWO_WAY_AADT);

        if ($jsonObject != null) {
            array_push($potentialTVs, $pTV);
        }
    }

    return $potentialTVs;
}
