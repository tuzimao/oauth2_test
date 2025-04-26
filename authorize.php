<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    $_SESSION['redirect_after_login'] = $_SERVER['REQUEST_URI'];
    header('Location: login.php');
    exit;
}

ini_set('display_errors', 1);
error_reporting(E_ALL);

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

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    ?>
    <form method="post">
        <h2>授权请求</h2>
        <p>应用 <strong><?= htmlspecialchars($_GET['client_id']) ?></strong> 请求访问你的账户。</p>
        <button name="authorized" value="yes" type="submit">同意</button>
        <button name="authorized" value="no" type="submit">拒绝</button>
    </form>
    <?php
    exit;
}

$isAuthorized = true;
$server->handleAuthorizeRequest($request, $response, $isAuthorized, $_SESSION['user_id']);
$response->send();

file_put_contents('debug.log', "[" . date('Y-m-d H:i:s') . "] Authorization complete and response sent\n", FILE_APPEND);
exit;
