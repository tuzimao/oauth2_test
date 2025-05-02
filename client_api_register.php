<?php
// client_api_register.php
// 用于处理客户端的注册请求（安全的服务端接口）

ini_set('display_errors', 1);
error_reporting(E_ALL);

file_put_contents('debug.log', "[" . date('Y-m-d H:i:s') . "] client_api_register.php 被访问\n", FILE_APPEND);

header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['error' => 'Method not allowed']);
    exit;
}

$appName = trim($_POST['app_name'] ?? '');
$redirectUri = trim($_POST['redirect_uri'] ?? '');

if (empty($appName) || empty($redirectUri)) {
    http_response_code(400);
    echo json_encode(['error' => 'Missing app_name or redirect_uri']);
    exit;
}

if (!filter_var($redirectUri, FILTER_VALIDATE_URL)) {
    http_response_code(400);
    echo json_encode(['error' => 'Invalid redirect_uri format']);
    exit;
}

try {
    $dbPath = __DIR__ . '/oauth.sqlite';
    file_put_contents('debug.log', "[" . date('Y-m-d H:i:s') . "] DB 路径: $dbPath\n", FILE_APPEND);

    if (!file_exists($dbPath)) {
        http_response_code(500);
        echo json_encode(['error' => 'Database file not found', 'path' => $dbPath]);
        exit;
    }

    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $clientId = bin2hex(random_bytes(16));
    $clientSecret = bin2hex(random_bytes(32));

    $stmt = $db->prepare('INSERT INTO oauth_clients (client_id, client_secret, redirect_uri) VALUES (:client_id, :client_secret, :redirect_uri)');
    $stmt->execute([
        ':client_id' => $clientId,
        ':client_secret' => $clientSecret,
        ':redirect_uri' => $redirectUri
    ]);

    echo json_encode([
        'client_id' => $clientId,
        'client_secret' => $clientSecret
    ]);

} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(['error' => 'PDO Error', 'message' => $e->getMessage()]);
    file_put_contents('debug.log', "[" . date('Y-m-d H:i:s') . "] PDO 错误: {$e->getMessage()}\n", FILE_APPEND);
    exit;
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => 'General Error', 'message' => $e->getMessage()]);
    file_put_contents('debug.log', "[" . date('Y-m-d H:i:s') . "] 一般错误: {$e->getMessage()}\n", FILE_APPEND);
    exit;
}
