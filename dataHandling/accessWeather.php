<?php
/**
 * Created by PhpStorm.
 * User: hongjizhou
 * Date: 06/09/15
 * Time: 15:03
 */

$weather_weigh = file_get_contents("weather_weigth.json");

echo getWeather($weather_weigh);

function getWeather($weather_weigh){
    $record = json_decode($weather_weigh);
    $array = $record->record;
    $result = array();
    foreach($array as $a){
        $result[$a->id] = $a->weigth;
    }


    return json_encode($result);
}

