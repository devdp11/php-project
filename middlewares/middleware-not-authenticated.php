<?php

require_once __DIR__ . '/../validations/session.php';

if (isset($_SESSION['id']) || isset($_COOKIE['id'])) {
    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/php-project/pages/secure';
    header('Location: ' . $home_url);
}
