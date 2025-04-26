<?php


// github-login.php

$clientId = 'Ov23liPQrN3TeGhrbjkj';
$redirectUri = 'http://localhost:8000/github-callback.php';
$scope = 'read:user user:email';

$params = [
    'client_id' => $clientId,
    'redirect_uri' => $redirectUri,
    'scope' => $scope,
    'state' => bin2hex(random_bytes(8))  // 防 CSRF
];

$url = 'https://github.com/login/oauth/authorize?' . http_build_query($params);
header("Location: $url");
exit;
