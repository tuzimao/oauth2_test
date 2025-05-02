<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 模拟客户端发出 HTTP POST 请求给 OAuth2 Server 的注册接口
    $appName = trim($_POST['app_name'] ?? '');
    $redirectUri = trim($_POST['redirect_uri'] ?? '');

    if (empty($appName) || empty($redirectUri)) {
        $error = "应用名称和回调地址不能为空！";
    } else {
        // 模拟 HTTP 请求：注册到 Server 的 client 注册 API
        $clientRegisterUrl = 'http://localhost/oauth2_test/client_api_register.php';

        $postData = http_build_query([
            'app_name' => $appName,
            'redirect_uri' => $redirectUri
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
        $response = file_get_contents($clientRegisterUrl, false, $context);
        if ($response === false) {
            echo "<p style='color:red'>file_get_contents 失败了</p>";
        }

        if ($response !== false) {
            $data = json_decode($response, true);
            if (isset($data['client_id'], $data['client_secret'])) {
                $clientId = $data['client_id'];
                $clientSecret = $data['client_secret'];
                $success = true;
            } else {
                $error = "注册失败：" . ($data['error'] ?? '未知错误');
            }
        } else {
            $error = "无法连接到授权服务器，请稍后重试。";
        }
    }
}
?>

<h2>注册你的应用</h2>

<?php if (!empty($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>

<?php if (!empty($success)): ?>
    <h3>注册成功！请妥善保存以下信息：</h3>
    <p><b>Client ID:</b> <code><?= htmlspecialchars($clientId) ?></code></p>
    <p><b>Client Secret:</b> <code><?= htmlspecialchars($clientSecret) ?></code></p>
    <p style="color:red;"><b>注意：Client Secret 只显示一次，请妥善保存！</b></p>
<?php else: ?>
    <form method="post">
        <label>应用名称：</label><br>
        <input type="text" name="app_name" required><br><br>

        <label>回调地址 (Redirect URI)：</label><br>
        <input type="url" name="redirect_uri" required><br><br>

        <button type="submit">提交注册</button>
    </form>
<?php endif; ?>
