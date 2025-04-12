<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

file_put_contents('debug.log', "[" . date('Y-m-d H:i:s') . "] Entered authorize.php\n", FILE_APPEND);

require 'server.php';

session_start();
$_SESSION['user_id'] = 'alice'; // 模拟用户登录

$request = OAuth2\Request::createFromGlobals();
$response = new OAuth2\Response();

// 检查授权请求是否合法
if (!$server->validateAuthorizeRequest($request, $response)) {
    $response->send();
    file_put_contents('debug.log', "[" . date('Y-m-d H:i:s') . "] Invalid authorize request\n", FILE_APPEND);
    exit;
}

file_put_contents('debug.log', "[" . date('Y-m-d H:i:s') . "] Authorization valid, issuing code...\n", FILE_APPEND);

$isAuthorized = true;

$server->handleAuthorizeRequest($request, $response, $isAuthorized, $_SESSION['user_id']);
$response->send();

file_put_contents('debug.log', "[" . date('Y-m-d H:i:s') . "] Authorization complete and response sent\n", FILE_APPEND);
exit;
