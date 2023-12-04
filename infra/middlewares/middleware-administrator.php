<?php

@require_once __DIR__ . '/../../helpers/session.php';

if (!administrator()) {
    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/crud/';
    header('Location: ' . $home_url);
}
