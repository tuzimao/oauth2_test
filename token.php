<?php
require 'server.php';

$request = OAuth2\Request::createFromGlobals();
$response = $server->handleTokenRequest($request);
$response->send();
