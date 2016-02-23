<?php
/**
 * Created by PhpStorm.
 * User: hongjizhou
 * Date: 06/09/15
 * Time: 13:14
 */


function getSessionID()
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

function getResult($curl)
{

    $json_result = executeURL($curl);
    $result = json_decode($json_result);
    if($result->error[0]->code == 401){
        getSessionID();
        $json_result = executeURL($curl);
    }elseif($result->error[0]->code == 500){
        $json_result = "There is someting wrong";
    }

    return $json_result;
}

function executeURL($curl){

    $json_sid = file_get_contents('sessionid.json');
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
