<?php
include_once "city.php";
include_once "dashboard.php";

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
class DBWeather{

    private static $pdo = null;

    /**
     * DBWeather constructor. Not allowed because all methods are statics
     * @throws Exception
     */
    function __construct() {
        throw new Exception("Static Access : DBWeather::method");
    }

    /**
     * Helper method made for opening a PDO connection and initialize the $pdo descriptor
     */
    private static function connect(){
        DBWeather::$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME,
                            DB_USER,
                            DB_PASS);
        DBWeather::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        DBWeather::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ );
    }

    /**
     * Helper method, closes the connection by setting the $pdo descriptor to null
     */
    private static function close(){
        DBWeather::$pdo = null;
    }

    /**
     * Helper method performing the '$sqlQuery' with '$queryParams'.
     * If '$fetchClassModel' is given, the method return an instance of the model (or an array of models)
     * @param $sqlQuery
     * @param null $queryParams
     * @param null $fetchClassModel
     * @return mixed
     * @throws Exception
     */
	private static function execute($sqlQuery,$queryParams = null,$fetchClassModel = null){
		try{
			DBWeather::connect();
				$preparedQuery = DBWeather::$pdo->prepare($sqlQuery, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
				$data = $preparedQuery->execute($queryParams);
				if($fetchClassModel != null){
					$data = $preparedQuery->fetchAll(PDO::FETCH_CLASS, $fetchClassModel);
				}
			DBWeather::close();
		} catch (PDOException $e){
            throw new Exception($e->getMessage(), (int)$e->getCode());
        }
		return $data;
	}

    /***************************************************************************
     * CITY requests
     **************************************************************************/

    public static function getCities(){
        try{
            $cities = DBWeather::execute('SELECT * FROM city',null,"CityModel");
        } catch (PDOException $e){
            throw new Exception($e->getMessage(), (int)$e->getCode());
        }
        return $cities;
    }


    public static function getCityById($id){
        try{
            $query = 'SELECT * FROM city WHERE id= :id';
            $city = DBWeather::execute($query,array(':id'=>$id),"CityModel");
        } catch (PDOException $e){
            throw new Exception($e->getMessage(), (int)$e->getCode());
        }
        return $city;
    }

    public static function getCityByName($name){
        try{
            $query = 'SELECT * FROM city WHERE name=:name';
            $city = DBWeather::execute($query,array(':name'=>$name),"CityModel");
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
			$success = DBWeather::execute($query,$params);
        } catch (PDOException $e){
            throw new Exception($e->getMessage(), (int)$e->getCode());
        }
        return $success;
    }

    public static function cityStartWith($word){
        try{
            $query = "SELECT * FROM city WHERE name LIKE :word";
            $cities = DBWeather::execute($query,array(':word'=>$word . '%'),"CityModel");
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
            $dashboards = DBWeather::execute($query,null,"DashboardModel");
        } catch (PDOException $e){
            throw new Exception($e->getMessage(), (int)$e->getCode());
        }
        return $dashboards;
    }

    public static function getDashboardCities($dashboardId){
        try{
            $query = "SELECT * FROM city "
                    . "WHERE id IN (SELECT city_id FROM dashboard_city WHERE dashboard_id = :dashboardId)";
            $cities = DBWeather::execute($query,array(':dashboardId'=>$dashboardId),"CityModel");
        } catch (PDOException $e){
            throw new Exception($e->getMessage(), (int)$e->getCode());
        }
        return $cities;
    }

    public static function addCityToDashboard($cityId,$dashboardId){
        try{
            $query = "INSERT INTO dashboard_city VALUES (:dashboardId,:cityId)";
            $success = DBWeather::execute($query,array(':dashboardId'=>$dashboardId,':cityId'=>$cityId));
        } catch (PDOException $e){
            throw new Exception($e->getMessage(), (int)$e->getCode());
        }
        return $success;
    }

    public static function removeCityFromDashboard($cityId,$dashboardId){
        try{
            $query = "DELETE FROM dashboard_city WHERE dashboard_id = :dashboardId AND city_id = :cityId";
            $success = DBWeather::execute($query,array(':dashboardId'=>$dashboardId,':cityId'=>$cityId));
        } catch (PDOException $e){
            throw new Exception($e->getMessage(), (int)$e->getCode());
        }
        return $success;
    }

    public static function addDashboard($dashboardName){
        try{
            $query = "INSERT INTO dashboard VALUES (null,:dashbordName)";
			$success = DBWeather::execute($query,array(':dashbordName' => $dashboardName));
        } catch (PDOException $e){
            throw new Exception($e->getMessage(), (int)$e->getCode());
        }
        return $success;
    }

    public static function removeDashboard($dashboardId){
        try{
            $query = "DELETE FROM dashboard_city WHERE dashboard_id = :dashboardId";
			$success = DBWeather::execute($query,array(':dashboardId'=>$dashboardId));
            $query = "DELETE FROM dashboard WHERE id = :dashboardId";
			$success = $success && DBWeather::execute($query,array(':dashboardId'=>$dashboardId));
        } catch (PDOException $e){
            throw new Exception($e->getMessage(), (int)$e->getCode());
        }
        return $success;
    }
}
