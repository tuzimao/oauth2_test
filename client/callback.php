<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

file_put_contents(__DIR__ . '/callback_debug.log', "[" . date('Y-m-d H:i:s') . "] Called with code: " . ($_GET['code'] ?? 'none') . "\n", FILE_APPEND);

if (!isset($_GET['code'])) {
    die('No auth code received');
}

echo "<h3>Callback triggered with code: " . htmlspecialchars($_GET['code']) . "</h3>";

// 模拟 POST 请求来替代 curl
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [
  'grant_type' => 'authorization_code',
  'code' => $_GET['code'],
  'redirect_uri' => 'http://localhost/oauth2_test/client/callback.php',
  'client_id' => 'testclient',
  'client_secret' => 'testsecret'
];

// 直接调用 token.php 逻辑
include '../token.php';
exit;
