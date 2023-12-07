<?php

session_start();

if (!isset($_SESSION['id'])) {
  if (isset($_COOKIE['id']) && isset($_COOKIE['first_name'])) {
    $_SESSION['id'] = $_COOKIE['id'];
    $_SESSION['first_name'] = $_COOKIE['first_name'];
  } else {
    $home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/php-project/';
    header('Location: ' . $home_url);
  }
}