<?php
// client_request.php
// 独立运行的 PHP 客户端，用于测试 OAuth2 Server 的 client_credentials 模式

ini_set('display_errors', 1);
error_reporting(E_ALL);

$clientId = 'e5a215f06dfdb686542b029aa61fb439'; // 替换为你注册的 client_id
$clientSecret = '379ac8b8f40d1d3e100cb47a7bcbac4de11042502aa5a83f9ed60e7fa598310a'; // 替换为对应的 client_secret

$tokenUrl = 'http://localhost:8000/token.php';

$postData = http_build_query([
    'grant_type' => 'client_credentials',
    'client_id' => $clientId,
    'client_secret' => $clientSecret
]);

$context = stream_context_create([
    'http' => [
        'method' => 'POST',
        'header' => "Content-Type: application/x-www-form-urlencoded\r\n" .
                    "Content-Length: " . strlen($postData) . "\r\n",
        'content' => $postData,
        'ignore_errors' => true // 保证能取到 400/500 错误响应
    ]
]);

$response = file_get_contents($tokenUrl, false, $context);

if ($response === false) {
    echo "❌ 请求失败\n";
    exit;
}

// 显示响应头和内容
echo "===== 响应头 =====\n";
print_r($http_response_header);

echo "\n===== 响应内容 =====\n";
echo $response . "\n";

// 解析 access_token 并使用它访问受保护接口
$decodedJson = json_decode($response, true);

if (is_string($decodedJson)) {
    $tokenData = json_decode($decodedJson, true);
} else {
    $tokenData = $decodedJson;
}


if (!isset($tokenData['access_token'])) {
    echo "❌ 获取 access_token 失败\n";
    exit;
}

$accessToken = $tokenData['access_token'];

// 使用 access_token 请求受保护资源
$resourceUrl = 'http://localhost:8000/resource.php';

$resourceContext = stream_context_create([
    'http' => [
        'method' => 'GET',
        'header' => "Authorization: Bearer {$accessToken}\r\n",
        'ignore_errors' => true
    ]
]);

$resourceResponse = file_get_contents($resourceUrl, false, $resourceContext);

if ($resourceResponse === false) {
    echo "❌ 访问资源失败\n";
    exit;
}

echo "\n===== 受保护资源响应 =====\n";
echo $resourceResponse . "\n";
