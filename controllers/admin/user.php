<?php

require_once __DIR__ . '/../../repositories/user.php';
require_once __DIR__ . '/../../validations/admin/validate-user.php';
require_once __DIR__ . '/../../validations/admin/validate-update.php';
require_once __DIR__ . '/../../validations/session.php';

if (isset($_POST['user'])) {
    if ($_POST['user'] == 'create') {
        create($_POST);
    }

    if ($_POST['user'] == 'update') {
        $userToEdit = $_POST['user_id'];
        update($userToEdit, $_POST);
    }

    /* if ($_POST['user'] == 'profile') {
        updateProfile($_POST);
    }

    if ($_POST['user'] == 'password') {
        changePassword($_POST);
    } */

    if ($_POST['user'] == 'delete') {
        $userToDelete = $_POST['user_id'];
        softdelete($userToDelete);
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

function update($userId, $postData)
{
    if (!isset($_SESSION['id'])) {
        $_SESSION['errors'][] = 'User ID not set in the session.';
        $params = '?' . http_build_query($postData);
        header('location: /php-project/pages/secure/admin-users.php' . $params);
        return;
    }

    $userData = validatedUpdate($postData);

    if (isset($userData['invalid'])) {
        $_SESSION['errors'] = $userData['invalid'];
        $_SESSION['action'] = 'update';
        $params = '?' . http_build_query($postData);
        header('location: /php-project/pages/secure/admin-users.php' . $params);
        return;
    }

    $userId = $postData['user_id'];

    $success = updateAdminUser($userId, $userData);
    var_dump($userId, $userData);

    if ($success) {
        $_SESSION['success'] = 'User successfully updated!';
        $data['action'] = 'update';
        $params = '?' . http_build_query($data);
        header('location: /php-project/pages/secure/admin-users.php');
    } else {
        $_SESSION['errors'][] = 'Failed to update user. Please try again.';
        header('location: /php-project/pages/secure/admin-users.php');
    }
}

/* function updateProfile($req)
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
} */

function softdelete($userId)
{
    $deleteSuccess = softDeleteUser($userId);

    if ($deleteSuccess) {
        if ($_SESSION['id'] == $userId) {
            session_unset();
            session_destroy();
    
            setcookie(session_name(), '', time() - 3600);
            setcookie('id', '', time() - 3600, "/");
            setcookie('first_name', '', time() - 3600, "/");
        }

        $_SESSION['success'] = 'User deleted successfully.';
        $home_url = 'http://' . $_SERVER['HTTP_HOST'] . '/php-project/pages/secure/admin-users.php';
        header('Location: ' . $home_url);
        exit();
    } else {
        $_SESSION['errors'][] = 'Error deleting user.';
    }
}