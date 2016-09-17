<?php
include_once "city.php";
include_once "dashboard.php";
include_once "dbaccess.php";

$ini = parse_ini_file('config.ini',true);
define('DB_HOST',$ini['database']['host']);
define('DB_NAME',$ini['database']['name']);
define('DB_USER',$ini['database']['user']);
define('DB_PASS',$ini['database']['password']);


/**
 * Class DBWeather
 * Simple DB access, contains every method for querying data
 * Data access layer
 */
class DBWeather extends DBAccess {

    /**
     * DBWeather constructor. Not allowed because all methods are statics
     * @throws Exception
     */
    function __construct() {
        throw new Exception("Static Access : DBWeather::method");
    }

    /***************************************************************************
     * CITY requests
     **************************************************************************/

    public static function getCities(){
        try{
            $cities = parent::execute('SELECT * FROM city',null,"CityModel");
        } catch (PDOException $e){
            throw new Exception($e->getMessage(), (int)$e->getCode());
        }
        return $cities;
    }


    public static function getCityById($id){
        try{
            $query = 'SELECT * FROM city WHERE id= :id';
            $city = parent::execute($query,array(':id'=>$id),"CityModel");
        } catch (PDOException $e){
            throw new Exception($e->getMessage(), (int)$e->getCode());
        }
        return $city;
    }

    public static function getCityByName($name){
        try{
            $query = 'SELECT * FROM city WHERE name=:name';
            $city = parent::execute($query,array(':name'=>$name),"CityModel");
        } catch (PDOException $e){
            throw new Exception($e->getMessage(), (int)$e->getCode());
        }
        return $city;
    }

    public static function addCity($city){
        try{
            $query = "INSERT INTO city VALUES (:id,:name,:lon,:lat,:country)";
			$params = array(
				':id'=>$city->id,
				':name'=>$city->name,
				':lon'=>$city->longitude,
				':lat'=>$city->latitude,
				':country'=>$city->country
			);
			$success = parent::execute($query,$params);
        } catch (PDOException $e){
            throw new Exception($e->getMessage(), (int)$e->getCode());
        }
        return $success;
    }

    public static function cityStartWith($word){
        try{
            $query = "SELECT * FROM city WHERE name LIKE :word";
            $cities = parent::execute($query,array(':word'=>$word . '%'),"CityModel");
        } catch (PDOException $e){
            throw new Exception($e->getMessage(), (int)$e->getCode());
        }
        return $cities;
    }


    /***************************************************************************
     * DASHBOARD requests
     **************************************************************************/

    public static function getDashboards(){
        try{
            $query = "SELECT * FROM dashboard";
            $dashboards = parent::execute($query,null,"DashboardModel");
        } catch (PDOException $e){
            throw new Exception($e->getMessage(), (int)$e->getCode());
        }
        return $dashboards;
    }

    public static function getDashboardCities($dashboardId){
        try{
            $query = "SELECT * FROM city "
                    . "WHERE id IN (SELECT city_id FROM dashboard_city WHERE dashboard_id = :dashboardId)";
            $cities = parent::execute($query,array(':dashboardId'=>$dashboardId),"CityModel");
        } catch (PDOException $e){
            throw new Exception($e->getMessage(), (int)$e->getCode());
        }
        return $cities;
    }

    public static function addCityToDashboard($cityId,$dashboardId){
        try{
            $query = "INSERT INTO dashboard_city VALUES (:dashboardId,:cityId)";
            $success = parent::execute($query,array(':dashboardId'=>$dashboardId,':cityId'=>$cityId));
        } catch (PDOException $e){
            throw new Exception($e->getMessage(), (int)$e->getCode());
        }
        return $success;
    }

    public static function removeCityFromDashboard($cityId,$dashboardId){
        try{
            $query = "DELETE FROM dashboard_city WHERE dashboard_id = :dashboardId AND city_id = :cityId";
            $success = parent::execute($query,array(':dashboardId'=>$dashboardId,':cityId'=>$cityId));
        } catch (PDOException $e){
            throw new Exception($e->getMessage(), (int)$e->getCode());
        }
        return $success;
    }

    public static function addDashboard($dashboardName){
        try{
            $query = "INSERT INTO dashboard VALUES (null,:dashboardName)";
			$success = parent::execute($query,array(':dashboardName' => $dashboardName));
        } catch (PDOException $e){
            throw new Exception($e->getMessage(), (int)$e->getCode());
        }
        return $success;
    }

    public static function removeDashboard($dashboardId){
        try{
            $query = "DELETE FROM dashboard_city WHERE dashboard_id = :dashboardId";
			$success = parent::execute($query,array(':dashboardId'=>$dashboardId));
            $query = "DELETE FROM dashboard WHERE id = :dashboardId";
			$success = $success && parent::execute($query,array(':dashboardId'=>$dashboardId));
        } catch (PDOException $e){
            throw new Exception($e->getMessage(), (int)$e->getCode());
        }
        return $success;
    }
}
