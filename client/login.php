<?php
$params = http_build_query([
    'response_type' => 'code',
    'client_id' => 'testclient',
    'redirect_uri' => 'http://localhost:8000/client/callback.php',
    'state' => 'xyz'
]);

header('Location: http://localhost:8000/authorize.php?' . $params);
exit;
