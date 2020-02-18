<?php

require_once __DIR__ . '/idgen.php';

function hashPassword($password, $salt) {
    return hash('sha256', $salt . $password);
}

function generateSalt() {
    return generateKey(24); // 32 chars
}

function generateApiKey() {
    $key = generateKey(24);
    $expiry = new DateTime();
    $expiry->add(new DateInterval('P1M')); // 1 month
    return ['key' => $key, 'expiry' => $expiry->format('Y-m-d H:i:s')];
}

function parseAuthHeader($header) {
    $auth = base64_decode($header);
    // [0] = user_id, [1] = api_key
    return explode(':', $auth);
}