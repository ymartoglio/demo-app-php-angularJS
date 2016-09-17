<?php
include_once '../city.php';

/**
 * Class DashboardModel
 * Model for dashboard data
 */
class DashboardModel{
    public $id;
    public $name;
}

/**
 * Class DashboardController
 * Dashboard Controller exposes method to get, create, delete and populate (with city) dashboard
 */
class DashboardController{

    function __contruct(){}

    /*
     * expose create
     */
    public function Create($dashboardName){
        return DBWeather::addDashboard($dashboardName);
    }

    /*
     * expose delete
     */
    public function Delete($dashboardId){
        return DBWeather::removeDashboard($dashboardId);
    }

    /*
     * expose all
     */
    public function All(){
        return DBWeather::getDashboards();
    }

    /*
     * expose cities
     */
    public function cities($dashboardId){
        return DBWeather::getDashboardCities($dashboardId);
    }

    /*
     * expose city_by_id
     */
    public function CityById($id){
        return DBWeather::getCityById($id);
    }

    /*
     * expose add_city
     */
    public function AddCity($cityId,$dashboardId){
        return DBWeather::addCityToDashboard($cityId,$dashboardId);
    }

    /*
     * expose delete_city
     */
    public function DeleteCity($cityId,$dashboardId){
        return DBWeather::removeCityFromDashboard($cityId, $dashboardId);
    }

}
