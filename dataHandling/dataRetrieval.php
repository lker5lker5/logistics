<?php
/**
 * Created by PhpStorm.
 * User: hongjizhou
 * Date: 25/08/15
 * Time: 22:19
 */

//SEP 5, 2015, ajax get pass variables
header('Access-Control-Allow-Origin:*');
header('Access-Control-Allow-Methods:POST,GET');
header('Access-Control-Allow-Credentials:true');
header("Content-Type: application/json;charset=utf-8");
//end SEP 5

require('Step.php');
require('TrafficVolume.php');
require('dayVolume.php');
error_reporting( E_ALL&~E_NOTICE );
//============================================================================================

//test db URL
function getGraphData(){
	return "https://dsp-acer-believe.cloud.dreamfactory.com:443/rest/DigisoftDB/hourly_data?filter=HMGNS_ID%3D27581%20and%20PERIOD_TYPE%3D'SCHOOL%20TERM'&fields=DOW%2CTotalTraffic";
}

function getSteps($json_String)
{
    $route_obj = json_decode($json_String);

    $routes = $route_obj->routes;

    $leg = $routes[0]->legs;

    $steps = $leg[0]->steps;
    var_dump($steps);


    $stepArray = array();

    foreach ($steps as $step) {
        //only the distance is greater 300 will be count
        if($step->distance->value>100) {
            //take teh google result and put into object
            $stepObj = new Step();
            $stepObj->setDistanceText($step->distance->text);
            $stepObj->setDistanceValue($step->distance->value);
            $stepObj->setDurationText($step->duration->text);
            $stepObj->setDurationValue($step->duration->value);
            $stepObj->setEndLocationLat($step->end_location->lat);
            $stepObj->setEndLocationLng($step->end_location->lng);
            $stepObj->setDescription($step->html_instructions);
            $stepObj->setStartLocationLat($step->start_location->lat);
            $stepObj->setStartLocationLng($step->start_location->lng);

//            echo $stepObj->getDescription();

            //get the road name for current step
            $description = $step->html_instructions;
            //replace the <div> and </div> with <b> and </b> in the string
            $description1 = str_replace("<div style=\"font-size:0.9em\">", "<b>", $description);
            $description2 = str_replace("</div>", "</b>", $description1);

            //get the first </b> position and cut the string before the position
            $startPos = strpos($description2, "</b>");
            $str = substr($description, $startPos + 3);

            //get the content between <b> and </b> which is the name of the road in  the new String
            $sPattern = "/<b>(.*?)<\/b>/";
            preg_match($sPattern, $str, $aMatch);
            $str = $aMatch[1];

            //if the road name has "/", cut the string after it
            if($startPos2 = strpos($str, "/")) {
                $str2 = substr($str, 0, $startPos2);
            }else{
                $str2 = $str;
            }
            //echo $str2 . "\n";

            //Replace String with meaningful string to get data from database
            //replace Rd -> ROAD btwn
            //replace Ave -> AVENUE btwn
            //replace St -> STREET btwn
            //replace Hwy-> HiGHWAY btwn
//            if (!strpos("Rd", $str2)) {
//                $str2 = str_replace("Rd", "ROAD btwn", $str2);
//            }
//            if (!strpos("Ave", $str2)) {
//                $str2 = str_replace("Ave", "AVENUE btwn", $str2);
//            }
//            if (!strpos("St", $str2)) {
//                $str2 = str_replace("St", "STREET btwn", $str2);
//            }
//            if (!strpos("Hwy", $str2)) {
//                $str2 = str_replace("Hwy", "HIGHWAY btwn", $str2);
//            }
            //put the Road name into the object
            $stepObj->setRoadName($str2);
//            echo $str2;
            //put the object into an array
            array_push($stepArray, $stepObj);

        }

    }

    return $stepArray;

}

//========================================================================
//get sessionID if it expires
function getSessionIDIfExpires()
{
    $data = array("email" => "zhouhongji@live.cn", "password" => "zhouhongji", "duration" => 3600);
    $data_string = json_encode($data);


    $ch = curl_init('https://dsp-acer-believe.cloud.dreamfactory.com/rest/user/session');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        'X-DreamFactory-Application-Name: digisoft'
    ));

    $result = curl_exec($ch);
    $response = json_decode($result);
    curl_close($ch);
    $json_sid = "{\"sessionid\":\"$response->session_id\"}";
    file_put_contents("sessionid.json",$json_sid);

}

//getSessionID();

function getResult($curl)
{
    $json_result = executeURL($curl);
    $result = json_decode($json_result);
    if($result->error[0]->code == 401){
        getSessionIDIfExpires();
        $json_result = executeURL($curl);
    }elseif($result->error[0]->code == 500){
        $json_result = "There is someting wrong";
    }

    return $json_result;
}

function executeURL($curl){

    $json_sid = file_get_contents("sessionid.json");
    $sid = json_decode($json_sid);
    $sessionID = $sid->sessionid;
//    $sessionID = "o76se41b8ej0rh8hg47rep76d3q5d81kbdglnrnj1ufi8jm0s9u1";

    $ch = curl_init($curl);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "X-DreamFactory-Application-Name: digisoft",
        "X-DreamFactory-Session-Token: $sessionID"
    ));

    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
}

//================================================================================================================================
function getDataFromDB($stepArray)
{

//    $json_string = file_get_contents('GoogleR.json');
//    $stepArray = getSteps($json_string);

	$stepFromDB = array();

    $queryRoad = null;

    foreach($stepArray as $step){
        $road = $step->getRoadName();
        $nRoad = "HMGNS_LNK_DESC%20like%20'"."%25".$road."%25'%20or%20";

        $newRoad = str_replace(" ", "%20", $nRoad);

        $queryRoad .= $newRoad;

    }

    $query =  substr($queryRoad,0, strlen($queryRoad)-8);

    $curl = "https://dsp-acer-believe.cloud.dreamfactory.com:443/rest/DigisoftDB/traffic_volume?filter=$query";
    $result = getResult($curl);

    foreach ($stepArray as $step) {

//        $road = $step->getRoadName();
//        $nRoad = "%20" . $road;
//
//        $newRoad = str_replace(" ", "%20", $nRoad);
//
//        $curl = "https://dsp-acer-believe.cloud.dreamfactory.com:443/rest/DigisoftDB/traffic_volume?filter=HMGNS_LNK_DESC%20LIKE%20'$newRoad%25'&fields=MIDPNT_LAT%2CMIDPNT_LON%2CTWO_WAY_AADT%2CHMGNS_ID&include_count=true";
//
//        $result = getResult($curl);

//        echo $result;

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

                $dis1 = sqrt(abs(($stepArray[0]->getStartLocationLat() - $pTVs[$i]->getMinpntLat()) / ($stepArray[0]->getStartLocationLng() - $pTVs[$i]->getMinpntLng())));
                $max = max($dis1, $max);
                if ($dis1 == $max) {
                    $maxElement = $pTVs[$i];
                }
                $i++;
            }
        }

        if ($maxElement != null) {
            $maxElement->setRoadName($road);
            $maxElement->setRoadNameURLkey($newRoad);

            array_push($stepFromDB, $maxElement);
        }
    }
    return $stepFromDB;
}

//This function is for the json for each road into object
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


function getHourlyData($finalTVs)
{
//get the Step
//    $finalTVs = getDataFromDB();

//echo $currentTime=date("Y-m-d");

    foreach ($finalTVs as $TV) {
        $hmgnsId = $TV->getHmgnsId();

//        echo $hmgnsId;
        $curl_hourly_data = "https://dsp-acer-believe.cloud.dreamfactory.com:443/rest/DigisoftDB/hourly_data?filter=HMGNS_ID%3D$hmgnsId%20and%20PERIOD_TYPE%3D'SCHOOL%20TERM'&fields=dow%2Cam12%2Cam1%2Cam2%2Cam3%2Cam4%2Cam5%2Cam6%2Cam7%2Cam8%2Cam9%2Cam10%2Cam11%2Cpm12%2Cpm1%2Cpm2%2Cpm3%2Cpm4%2Cpm5%2Cpm6%2Cpm7%2Cpm8%2Cpm9%2Cpm10%2Cpm11%2CTotalTraffic
";
        $json_hourly_data = getResult($curl_hourly_data);
//        echo $json_hourly_data."\n";
        $hourly_data = json_decode($json_hourly_data);
//        var_dump($hourly_data);
        $array = $hourly_data->record;

        $dayVolumeArray = array();

        foreach ($array as $a) {
            $dayVolume = new dayVolume();
            $dayVolume->setDOW($a->dow);
            $dayVolume->setAm1($a->am1);
            $dayVolume->setAm2($a->am2);
            $dayVolume->setAm3($a->am3);
            $dayVolume->setAm4($a->am4);
            $dayVolume->setAm5($a->am5);
            $dayVolume->setAm6($a->am6);
            $dayVolume->setAm7($a->am7);
            $dayVolume->setAm8($a->am8);
            $dayVolume->setAm9($a->am9);
            $dayVolume->setAm10($a->am10);
            $dayVolume->setAm11($a->am11);
            $dayVolume->setAm12($a->am12);
            $dayVolume->setPm1($a->pm1);
            $dayVolume->setPm2($a->pm2);
            $dayVolume->setPm3($a->pm3);
            $dayVolume->setPm4($a->pm4);
            $dayVolume->setPm5($a->pm5);
            $dayVolume->setPm6($a->pm6);
            $dayVolume->setPm7($a->pm7);
            $dayVolume->setPm8($a->pm8);
            $dayVolume->setPm9($a->pm9);
            $dayVolume->setPm10($a->pm10);
            $dayVolume->setPm11($a->pm11);
            $dayVolume->setPm12($a->pm12);
            $dayVolume->setTotalVolume($a->TotalTraffic);

            $json_day = json_encode($dayVolume);


            array_push($dayVolumeArray, $dayVolume);
        }

        $TV->setDayVolumeArray($dayVolumeArray);

    }
    return $finalTVs;

}

//============================================================
function getRoute($startPoint, $endPoint)
//function getRoute()
{
	//var_dump( $getStart);
    $curl_google_map_api = "https://maps.googleapis.com/maps/api/directions/json?origin=$startPoint&destination=$endPoint&key=%20AIzaSyAkHqtKMHETFG7CDxojnbDdZinDU0lR940";
    $ch = curl_init($curl_google_map_api);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
//    echo $result;
    return $result;

}

//============Get JSON including all data information ===============
function getJSON(){
	$getStart = "Clayton,Victoria,Australia";//$_GET["startPoint"];
	$getEnd = "Caulfield,Victoria,Australia";//$_GET["endPoint"];//
	$startPoint_rep1 = str_replace(',', '+', $getStart);
	$endPoint_rep1 = str_replace(',', '+', $getEnd);
	$startPoint = str_replace(' ', '',$startPoint_rep1);
	$endPoint = str_replace(' ', '',$endPoint_rep1);
	$r1 = getRoute($startPoint, $endPoint);
	$r2 = getSteps($r1);
	$r3 = getDataFromDB($r2);
	$r4 = getHourlyData($r3);
	
	$finalResult = json_encode($r4);	
	return $finalResult;
}
	
//===========SEP 5, 2015 AJAX======================
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    $result =  getJSON();
	
	//$result = getJSON();
	//echo $result;
}