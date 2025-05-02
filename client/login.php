<?php
$params = http_build_query([
    'response_type' => 'code',
    'client_id' => 'testclient',
    'redirect_uri' => 'http://localhost/oauth2_test/client/callback.php',
    'state' => 'xyz'
]);

header('Location: http://localhost/oauth2_test/authorize.php?' . $params);
exit;
