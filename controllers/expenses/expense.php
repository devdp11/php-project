<?php
require_once __DIR__ . '/../../repositories/user.php';
@require_once __DIR__ . '/../../validations/session.php';


if (isset($_POST['user'])) {
    if ($_POST['user'] == 'add') {
        eadd($_POST);
    }

    if ($_POST['user'] == 'edit') {
        eedit();
    }
    if ($_POST['user'] == 'delete') {
        edelete();
    }
}

function eadd()
{
    if (!isset($_SESSION['id'])) {
        echo 'User ID not set in the session.';
        exit();
    }

    $user = [
        'id' => $_SESSION['id'],
    ];
}

function eedit()
{
    if (!isset($_SESSION['id'])) {
        echo 'User ID not set in the session.';
        exit();
    }

    $user = [
        'id' => $_SESSION['id'],
    ];
}

function edelete()
{
    if (!isset($_SESSION['id'])) {
        echo 'User ID not set in the session.';
        exit();
    }

    $user = [
        'id' => $_SESSION['id'],
    ];
}