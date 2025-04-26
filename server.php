<?php
require 'vendor/autoload.php'; // 使用 composer 安装 bshaffer/oauth2-server-php

$dsn = 'sqlite:' . __DIR__ . '/oauth.sqlite';
$storage = new OAuth2\Storage\Pdo(['dsn' => $dsn]);

$server = new OAuth2\Server($storage, [
    'access_lifetime' => 3600,
    'enforce_state' => true,
    'allow_implicit' => false
]);

// 支持的授权类型
$server->addGrantType(new OAuth2\GrantType\AuthorizationCode($storage));
$server->addGrantType(new OAuth2\GrantType\ClientCredentials($storage));
$server->addGrantType(new OAuth2\GrantType\UserCredentials($storage));
$server->addGrantType(new OAuth2\GrantType\RefreshToken($storage));


