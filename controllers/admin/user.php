<?php

require_once __DIR__ . '/../../repositories/user.php';
require_once __DIR__ . '/../../validations/admin/validate-user.php';
require_once __DIR__ . '/../../validations/admin/validate-password.php';
require_once __DIR__ . '/../../validations/session.php';

if (isset($_POST['user'])) {
    if ($_POST['user'] == 'create') {
        create($_POST);
    }

    if ($_POST['user'] == 'update') {
        update($_POST);
    }

    if ($_POST['user'] == 'profile') {
        updateProfile($_POST);
    }

    if ($_POST['user'] == 'password') {
        changePassword($_POST);
    }
    if ($_POST['admin'] == 'delete') {
        softdelete($_POST);
    }
}

if (isset($_GET['user'])) {
    if ($_GET['user'] == 'update') {
        $user = getById($_GET['id']);
        $user['action'] = 'update';
        $params = '?' . http_build_query($user);
        header('location: /php-project/pages/secure/admin/user.php' . $params);
    }

    if ($_GET['user'] == 'delete') {
        $user = getById($_GET['id']);
        if ($user['administrator']) {
            $_SESSION['errors'] = ['This user cannot be deleted!'];
            header('location: /php-project/pages/secure/admin/');
            return false;
        }

        $success = delete_user($user);

        if ($success) {
            $_SESSION['success'] = 'User deleted successfully!';
            header('location: /php-project/pages/secure/admin/');
        }
    }
}

function create($postData)
{
    $validationResult = validatedUser($postData);

    if (isset($validationResult['invalid'])) {
        $_SESSION['errors'] = $validationResult['invalid'];
        header('location: /php-project/pages/secure/admin-users.php');
        exit;
    }

    $user = [
        'first_name' => $validationResult['first_name'],
        'last_name' => $validationResult['last_name'],
        'password' => $validationResult['password'],
        'email' => $validationResult['email'],
        'admin' => isset($validationResult['admin']) && $validationResult['admin'] ? 1 : 0,
    ];

    $result = createUser($user);

    if ($result) {
        $_SESSION['success'] = 'User created successfully.';
    } else {
        error_log("Error creating user: " . implode(" - ", $GLOBALS['pdo']->errorInfo()));
    }

    header('location: /php-project/pages/secure/admin-users.php'); // Corrija o caminho conforme necessário
    exit;
}

function update($req)
{
    $data = validatedUser($req);

    if (isset($data['invalid'])) {
        $_SESSION['errors'] = $data['invalid'];
        $_SESSION['action'] = 'update';
        $params = '?' . http_build_query($req);
        header('location: /php-project/pages/secure/admin/user.php' . $params);

        return false;
    }

    $success = updateUser($data);

    if ($success) {
        $_SESSION['success'] = 'User successfully changed!';
        $data['action'] = 'update';
        $params = '?' . http_build_query($data);
        header('location: /php-project/pages/secure/admin/user.php' . $params);
    }
}

function updateProfile($req)
{
    $data = validatedUser($req);

    if (isset($data['invalid'])) {
        $_SESSION['errors'] = $data['invalid'];
        $params = '?' . http_build_query($req);
        header('location: /php-project/pages/secure/user/profile.php' . $params);
        } else {
        $user = user(); 
        $data['id'] = $user['id'];
        $data['administrator'] = $user['administrator'];

        $success = updateUser($data);

        if ($success) {
            $_SESSION['success'] = 'User successfully changed!';
            $_SESSION['action'] = 'update';
            $params = '?' . http_build_query($data);
            header('location: /php-project/pages/secure/user/profile.php' . $params);
        }
    }
}

function changePassword($req)
{
    $data = passwordIsValid($req);
    if (isset($data['invalid'])) {
        $_SESSION['errors'] = $data['invalid'];
        $params = '?' . http_build_query($req);
        header('location: /php-project/pages/secure/user/password.php' . $params);
    } else {
        $data['id'] = userId();
        $success = updatePassword($data);
        if ($success) {
            $_SESSION['success'] = 'Password successfully changed!';
            header('location: /php-project/pages/secure//user/password.php');
        }
    }
}

function softdelete()
{
    if (!isset($_SESSION['id'])) {
        echo 'User ID not set in the session.';
        exit();
    }

    $user = [
        'id' => $_SESSION['id'],
    ];

    $deleteSuccess = softDeleteUser($user['id']);

    if ($deleteSuccess) {
        session_unset();
        session_destroy();

        setcookie(session_name(), '', time() - 3600);
        setcookie('id', '', time() - 3600, "/");
        setcookie('first_name', '', time() - 3600, "/");

        $home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/php-project';
        header('Location: ' . $home_url);
        exit();
    } else {
        echo 'Error deleting user account.';
    }
}