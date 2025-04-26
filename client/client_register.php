<?php
session_start();
ini_set('display_errors', 1);
error_reporting(E_ALL);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // 连接数据库
    $db = new PDO('sqlite:' . __DIR__ . '/oauth.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // 接收表单数据
    $appName = trim($_POST['app_name'] ?? '');
    $redirectUri = trim($_POST['redirect_uri'] ?? '');

    if (empty($appName) || empty($redirectUri)) {
        $error = "应用名称和回调地址不能为空！";
    } else {
        // 生成 client_id 和 client_secret
        $clientId = bin2hex(random_bytes(16));
        $clientSecret = bin2hex(random_bytes(32));

        // 插入数据库 oauth_clients 表
        $stmt = $db->prepare('INSERT INTO oauth_clients (client_id, client_secret, redirect_uri) VALUES (:client_id, :client_secret, :redirect_uri)');
        $stmt->execute([
            ':client_id' => $clientId,
            ':client_secret' => $clientSecret,
            ':redirect_uri' => $redirectUri
        ]);

        // 注册成功
        $success = true;
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
