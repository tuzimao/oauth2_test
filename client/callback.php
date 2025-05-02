<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

file_put_contents(__DIR__ . '/callback_debug.log', "[" . date('Y-m-d H:i:s') . "] Called with code: " . ($_GET['code'] ?? 'none') . "\n", FILE_APPEND);

if (!isset($_GET['code'])) {
    die('No auth code received');
}

$code = $_GET['code'];
echo "<h3>Callback triggered with code: " . htmlspecialchars($code) . "</h3>";

// 使用 file_get_contents 向 token.php 发送 POST 请求
$tokenUrl = 'http://localhost/oauth2_test/token.php';
$postData = http_build_query([
    'grant_type' => 'authorization_code',
    'code' => $code,
    'redirect_uri' => 'http://localhost/oauth2_test/client/callback.php',
    'client_id' => 'testclient',
    'client_secret' => 'testsecret'
]);

$options = [
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/x-www-form-urlencoded\r\n",
        'content' => $postData,
        'ignore_errors' => true
    ]
];

$context = stream_context_create($options);
$response = file_get_contents($tokenUrl, false, $context);

if ($response === false) {
    echo "<p style='color:red'>无法获取 access_token</p>";
    exit;
}

echo "<h3>Access Token Response:</h3><pre>" . htmlspecialchars($response) . "</pre>";
