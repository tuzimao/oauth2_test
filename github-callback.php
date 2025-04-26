<?php
session_start();

$clientId = '';
$clientSecret = '';

if (!isset($_GET['code'])) {
    die('No code provided');
}

// 1. 换 access_token
$tokenResponse = file_get_contents('https://github.com/login/oauth/access_token?' . http_build_query([
    'client_id' => $clientId,
    'client_secret' => $clientSecret,
    'code' => $_GET['code']
]));

parse_str($tokenResponse, $result);

if (!isset($result['access_token'])) {
    die('Error fetching access token: ' . $tokenResponse);
}

$accessToken = $result['access_token'];

// 2. 拉取用户信息
$opts = [
    'http' => [
        'method' => 'GET',
        'header' => [
            'Authorization: token ' . $accessToken,
            'User-Agent: DemoApp'
        ]
    ]
];

$context = stream_context_create($opts);
$userJson = file_get_contents('https://api.github.com/user', false, $context);
$user = json_decode($userJson, true);

// 3. 写入数据库（连接现有 oauth.sqlite）
$db = new PDO('sqlite:' . __DIR__ . '/oauth.sqlite');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

// 尝试插入或更新
$stmt = $db->prepare("
    INSERT INTO github_users (github_id, login, name, email, avatar_url)
    VALUES (:id, :login, :name, :email, :avatar)
    ON CONFLICT(github_id) DO UPDATE SET
        login = excluded.login,
        name = excluded.name,
        email = excluded.email,
        avatar_url = excluded.avatar_url
");
$stmt->execute([
    ':id' => $user['id'],
    ':login' => $user['login'],
    ':name' => $user['name'] ?? '',
    ':email' => $user['email'] ?? '',
    ':avatar' => $user['avatar_url'] ?? ''
]);

// 4. 设置登录 session
$_SESSION['github_user'] = [
    'id' => $user['id'],
    'login' => $user['login'],
    'name' => $user['name'] ?? '',
    'email' => $user['email'] ?? '',
    'avatar' => $user['avatar_url'] ?? ''
];

// 5. 展示欢迎页
echo "<h2>登录成功，欢迎你 " . htmlspecialchars($user['login']) . "!</h2>";
echo "<img src='" . htmlspecialchars($user['avatar_url']) . "' width='100'>";
echo "<pre>" . htmlspecialchars(json_encode($user, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) . "</pre>";
