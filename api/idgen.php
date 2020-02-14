<?php

function generateKey($bytes) {
    $id = base64_encode(random_bytes($bytes));
    $id = str_replace("+", "-", $id);
    $id = str_replace("/", "_", $id);
    $id = str_replace("=", "", $id);
    return $id;
}

function generateOrderRef() {
    return random_int(1, 9) * 1000
        + random_int(0, 999);
}

function generateId() {
    return generateKey(8); // 11 chars
}

function generateApiKey() {
    return generateKey(24); // 32 chars
}

function generateSalt() {
    return generateKey(24); // 32 chars
}