<?php
include_once '../src/request_handler.php';

$errorCode = 0;

try{
    $requestHandler = new RequestHandler(INPUT_GET);
    $responseData = $requestHandler->perform();
} catch(ReflectionException $e){
    $responseData = $e->getMessage();
    $errorCode = $e->getCode();
} catch(Exception $e){
    $responseData = $e->getMessage();
    $errorCode = $e->getCode();
}

if($errorCode == 0 && $responseData !== null){
    $success = true;
    if(is_bool($responseData)){
        $success = $success && $responseData;
    }
    echo json_encode(payload($success,$responseData));
}else{
    echo json_encode(payload(false,$responseData,$errorCode));
}

function payload($success,$data,$errorCode = null){
    $payload = new stdClass();
    $payload->success = $success;
    $payload->data = $data;
    if($errorCode != null){
        $payload->errorCode = $data;
    }
    return $payload;
}
