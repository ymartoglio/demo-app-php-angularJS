<?php

function __autoload($class_name) {
    include strtolower(str_replace('Controller','',$class_name)) . '.php';
}
/*
 * Provide controller method called by "Reflection"
 */
class RequestHandler{
    protected $controller = null;
    protected $method = null;
    protected $paramsType = INPUT_GET;

    function __construct($paramsType = INPUT_GET){
        $this->paramsType = $paramsType;
        $this->extractControllerRequestParams();
    }

    function __destruct() {
        $this->method     = null;
        $this->controller = null;
    }

    /*
     * Call the requested method
     * @return bool | object | array
     */
    public function perform(){
        try{
            return $this->method->invokeArgs($this->controller->newInstance(),$this->extractRequestParams());
        } catch(ReflectionException $e){
            throw $e;
        }
    }

    /*
     * Extract request inputs for Controller&Method instanciation
     */
    private function extractControllerRequestParams(){
        try{
            $controllerName   = filter_input($this->paramsType,'controller');
            $actionName       = filter_input($this->paramsType,'action');
            $controllerClass  = $this->normalizeAsCamelCase($controllerName).'Controller';
            $controllerMethod = $this->normalizeAsCamelCase($actionName);
            $this->controller = new ReflectionClass($controllerClass);
            $this->method     = new ReflectionMethod($controllerClass,$controllerMethod);
        }catch(ReflectionException $e){
            throw $e;
        }
    }

    /*
     * Extract request inputs for Method parameters
     */
    private function extractRequestParams(){
        $paramsAction = $this->method->getParameters();
        $paramsRequest = array();

        foreach ($paramsAction as $param) {
            $reqGetParam = filter_input($this->paramsType,$param->getName());
            if($reqGetParam != null){
                $paramsRequest[] = $reqGetParam;
            }
        }

        return $paramsRequest;
    }

    /**
     * Transform call_web_service to CallWebService
     * @param $name - string
     * @return string
     */
    private function normalizeAsCamelCase($name){
        if(PHP_MAJOR_VERSION >= 5 && PHP_MINOR_VERSION >= 5 && PHP_RELEASE_VERSION >= 16){
                return str_replace('_','',ucwords($name,"_"));
        }else{
                return str_replace(' ','',ucwords(str_replace('_',' ',$name)));
        }
    }
}
