<?php
require 'server.php';

$request = OAuth2\Request::createFromGlobals();
$response = new OAuth2\Response();

// 处理 token 请求
$response = $server->handleTokenRequest($request, $response);


$statusCode = $response->getStatusCode();       
$headers = $response->getHttpHeaders();          
$body = $response->getResponseBody();            


http_response_code($statusCode);

foreach ($headers as $key => $value) {
    header("$key: $value");
}

echo $response->getResponseBody(); 

