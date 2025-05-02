<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

$clientId = 'e5a215f06dfdb686542b029aa61fb439';
$clientSecret = '379ac8b8f40d1d3e100cb47a7bcbac4de11042502aa5a83f9ed60e7fa598310a';

$data = http_build_query([
    'grant_type' => 'client_credentials',
    'client_id' => $clientId,
    'client_secret' => $clientSecret
]);

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/x-www-form-urlencoded\r\n" .
                    "Content-Length: " . strlen($data) . "\r\n",
        'content' => $data,
        'ignore_errors' => true // 关键：即使 400 也能返回内容
    ]
]);

$url = 'http://localhost/oauth2_test/token.php';

$response = file_get_contents($url, false, $context);

if ($response === false) {
    echo "<h2 style='color:red'>❌ 请求失败</h2>";
    exit;
}

echo "<h2>响应头：</h2><pre>";
print_r($http_response_header);
echo "</pre>";

echo "<h2>响应内容：</h2><pre>";
echo htmlspecialchars($response);
echo "</pre>";
