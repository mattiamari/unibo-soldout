<?php

function hashPassword($password, $salt) {
    return hash('sha256', $salt . $password);
}
