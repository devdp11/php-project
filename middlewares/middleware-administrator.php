<?php

@require_once __DIR__ . '/../validations/session.php';

if (!administrator()) {
    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/php-project/';
    header('Location: ' . $home_url);
}
