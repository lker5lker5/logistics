<?php

/**
 * Created by PhpStorm.
 * User: hongjizhou
 * Date: 24/08/15
 * Time: 17:11
 */
class Step
{
    public $distance_text;
    public $distance_value;
    public $duration_text;
    public $duration_value;
    public $endLocation_lat;
    public $endLocation_lng;
    public $description;
    public $startLocation_lat;
    public $startLocation_lng;
    public $roadName;
    public $hmgns_id;
    public $volume;

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
    public function getDistanceText()
    {
        return $this->distance_text;
    }

    /**
     * @param mixed $distance_text
     */
    public function setDistanceText($distance_text)
    {
        $this->distance_text = $distance_text;
    }

    /**
     * @return mixed
     */
    public function getDistanceValue()
    {
        return $this->distance_value;
    }

    /**
     * @param mixed $distance_value
     */
    public function setDistanceValue($distance_value)
    {
        $this->distance_value = $distance_value;
    }

    /**
     * @return mixed
     */
    public function getDurationText()
    {
        return $this->duration_text;
    }

    /**
     * @param mixed $duration_text
     */
    public function setDurationText($duration_text)
    {
        $this->duration_text = $duration_text;
    }

    /**
     * @return mixed
     */
    public function getDurationValue()
    {
        return $this->duration_value;
    }

    /**
     * @param mixed $duration_value
     */
    public function setDurationValue($duration_value)
    {
        $this->duration_value = $duration_value;
    }

    /**
     * @return mixed
     */
    public function getEndLocationLat()
    {
        return $this->endLocation_lat;
    }

    /**
     * @param mixed $endLocation_lat
     */
    public function setEndLocationLat($endLocation_lat)
    {
        $this->endLocation_lat = $endLocation_lat;
    }

    /**
     * @return mixed
     */
    public function getEndLocationLng()
    {
        return $this->endLocation_lng;
    }

    /**
     * @param mixed $endLocation_lng
     */
    public function setEndLocationLng($endLocation_lng)
    {
        $this->endLocation_lng = $endLocation_lng;
    }

    /**
     * @return mixed
     */
    public function getStartLocationLat()
    {
        return $this->startLocation_lat;
    }

    /**
     * @param mixed $startLocation_lat
     */
    public function setStartLocationLat($startLocation_lat)
    {
        $this->startLocation_lat = $startLocation_lat;
    }

    /**
     * @return mixed
     */
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * @param mixed $description
     */
    public function setDescription($description)
    {
        $this->description = $description;
    }

    /**
     * @return mixed
     */
    public function getStartLocationLng()
    {
        return $this->startLocation_lng;
    }

    /**
     * @param mixed $startLocation_lng
     */
    public function setStartLocationLng($startLocation_lng)
    {
        $this->startLocation_lng = $startLocation_lng;
    }
}