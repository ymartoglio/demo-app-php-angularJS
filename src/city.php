<?php
include_once "dbweather.php";

/**
 * Class CityModel
 * Model for city data
 */
class CityModel{
    public $country;
    public $id;
    public $name;
    public $longitude;
    public $latitude;
}

/**
 * Class CityController
 * City Controller exposes method to get and search cities
 */
class CityController{    
    function __construct(){}
    
    /*
     * expose by_id
     */
    public function ById($id){
        return DBWeather::getCityById($id);
    }
    
    /*
     * expose by_name
     */
    public function ByName($name){
        return DBWeather::getCityByName($name);
    }
    
    /*
     * expose all
     */
    public function All(){
        return DBWeather::getCities();
    }
    
    /*
     * expose start_with
     */
    public function StartWith($word){
        return DBWeather::cityStartWith($word);
    }
}

