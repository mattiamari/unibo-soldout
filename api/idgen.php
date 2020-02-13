<?php

function generateId() {
    $id = base64_encode(random_bytes(8));
    $id = str_replace("+", "-", $id);
    $id = str_replace("/", "_", $id);
    $id = str_replace("=", "", $id);
    return $id;
}

function generateOrderRef() {
    return random_int(1, 9) * 1000
        + random_int(0, 999);
}
