<?php
/**
 * Created by PhpStorm.
 * User: hongjizhou
 * Date: 07/09/15
 * Time: 15:59
 */


require('accessDB.php');
require('accessGoogleMap.php');
require('Step.php');
require('TrafficVolume.php');
require('dayVolume.php');
error_reporting( E_ALL&~E_NOTICE );

$result = getTrafficVolume();
echo $result;

/*
 * Central function to use all mehthods
 */
function getTrafficVolume(){
    $getStart = $_REQUEST["startPoint"];
    $getEnd = $_REQUEST["endPoint"];
    $startPoint = str_replace(' ', '', $getStart);
    $endPoint = str_replace(' ', '', $getEnd);
    $routeNo = $_REQUEST["index"];
//    $startPoint = "Caulfield+station,vic";
//    $endPoint = "carnegie+station,vic";
//    $routeNo=0;
    $r1 = getRoute($startPoint, $endPoint);
    $r2 = getSteps($r1,$routeNo);
    $r3 = getDataFromDB($r2);
    //echo json_encode($r3);
    return json_encode($r3);
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
