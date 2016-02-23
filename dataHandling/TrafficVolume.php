<?php

/**
 * Created by PhpStorm.
 * User: hongjizhou
 * Date: 24/08/15
 * Time: 20:51
 */
class TrafficVolume
{
    public  $roadName;
    public  $roadNameURLkey;
    public  $minpnt_lat;
    public  $minpnt_lng;
    public  $volume;
    public  $dayVolumeArray;
    public  $hmgns_id;

    /**
     * @return mixed
     */
    public function getDayVolumeArray()
    {
        return $this->dayVolumeArray;
    }

    /**
     * @param mixed $dayVolumeArray
     */
    public function setDayVolumeArray($dayVolumeArray)
    {
        $this->dayVolumeArray = $dayVolumeArray;
    }

    /**
     * @return mixed
     */
    public function getRoadName()
    {
        return $this->roadName;
    }

    /**
     * @param mixed $roadName
     */
    public function setRoadName($roadName)
    {
        $this->roadName = $roadName;
    }

    /**
     * @return mixed
     */
    public function getRoadNameURLkey()
    {
        return $this->roadNameURLkey;
    }

    /**
     * @param mixed $roadNameURLkey
     */
    public function setRoadNameURLkey($roadNameURLkey)
    {
        $this->roadNameURLkey = $roadNameURLkey;
    }

    /**
     * @return mixed
     */
    public function getMinpntLat()
    {
        return $this->minpnt_lat;
    }

    /**
     * @param mixed $minpnt_lat
     */
    public function setMinpntLat($minpnt_lat)
    {
        $this->minpnt_lat = $minpnt_lat;
    }

    /**
     * @return mixed
     */
    public function getMinpntLng()
    {
        return $this->minpnt_lng;
    }

    /**
     * @param mixed $minpnt_lng
     */
    public function setMinpntLng($minpnt_lng)
    {
        $this->minpnt_lng = $minpnt_lng;
    }

    /**
     * @return mixed
     */
    public function getVolume()
    {
        return $this->volume;
    }

    /**
     * @param mixed $volume
     */
    public function setVolume($volume)
    {
        $this->volume = $volume;
    }

    /**
     * @return mixed
     */
    public function getHmgnsId()
    {
        return $this->hmgns_id;
    }

    /**
     * @param mixed $hmgns_id
     */
    public function setHmgnsId($hmgns_id)
    {
        $this->hmgns_id = $hmgns_id;
    }



}