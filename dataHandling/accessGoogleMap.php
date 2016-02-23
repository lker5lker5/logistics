<?php
/**
 * Created by PhpStorm.
 * User: hongjizhou
 * Date: 06/09/15
 * Time: 13:15
 */

function getRoute($startPoint, $endPoint)
{

    $curl_google_map_api = "https://maps.googleapis.com/maps/api/directions/json?origin=$startPoint&destination=$endPoint&alternatives=true&key=%20AIzaSyAkHqtKMHETFG7CDxojnbDdZinDU0lR940";
    $ch = curl_init($curl_google_map_api);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $result = curl_exec($ch);
//    echo $result;
    return $result;

}


function getSteps($json_String,$index)
{

    $route_obj = json_decode($json_String);

    $routes = $route_obj->routes;

    $leg = $routes[$index]->legs;

    $steps = $leg[0]->steps;
//    var_dump($steps);


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