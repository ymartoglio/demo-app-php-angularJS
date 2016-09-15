<?php

//Cities data list import
include_once 'src/city.php';
$fCities = file_get_contents("data/city.list.json");
$jsonCities = explode(PHP_EOL, $fCities);

$countCities = count($jsonCities);


/**
 * Loop through the cities and insert each in the DB
 */
for($i = 0; $i < $countCities ; $i++){
    if(trim($jsonCities[$i]) != ""){
        $owmCity = json_decode($jsonCities[$i]);

        $city = new CityModel();
        $city->id = $owmCity->_id;
        $city->country = $owmCity->country;
        $city->name = addslashes($owmCity->name);
        $city->longitude = $owmCity->coord->lon;
        $city->latitude = $owmCity->coord->lat;

        try{
            DBWeather::addCity($city);
        }catch(Exception $e){
            var_dump($city);
            var_dump($e);
            exit();
        }
        echo ($i+1), "/$countCities imported cities\r";
    }
}
echo "\n";
