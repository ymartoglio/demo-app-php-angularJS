<?php

abstract class DBAccess{
    /**
     * @var null
     */
    protected static $pdo = null;

    /**
     * Helper method made for opening a PDO connection and initialize the $pdo descriptor
     */
    protected static function connect(){
        self::$pdo = new PDO('mysql:host='.DB_HOST.';dbname='.DB_NAME,
            DB_USER,
            DB_PASS);
        self::$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
        self::$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ );
    }

    /**
     * Helper method, closes the connection by setting the $pdo descriptor to null
     */
    protected static function close(){
        self::$pdo = null;
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
    protected static function execute($sqlQuery,$queryParams = null,$fetchClassModel = null){
        try{
            self::connect();
            $preparedQuery = self::$pdo->prepare($sqlQuery, array(PDO::ATTR_CURSOR => PDO::CURSOR_FWDONLY));
            $data = $preparedQuery->execute($queryParams);
            if($fetchClassModel != null){
                $data = $preparedQuery->fetchAll(PDO::FETCH_CLASS, $fetchClassModel);
            }
            self::close();
        } catch (PDOException $e){
            throw new Exception($e->getMessage(), (int)$e->getCode());
        }
        return $data;
    }
}