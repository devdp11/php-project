<?php

require_once __DIR__ . '/../../helpers/session.php';

if (isset($_SESSION['id']) || isset($_COOKIE['id'])) {
    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/crud/pages/secure';
    header('Location: ' . $home_url);
}
