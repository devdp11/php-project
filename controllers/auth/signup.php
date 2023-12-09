<?php
session_start();

require_once __DIR__ . '/../../repositories/user.php';
require_once __DIR__ . '/../../validations/app/validate-sign-up.php';

if (isset($_POST['user'])) {
    if ($_POST['user'] == 'signUp') {
        signUp($_POST);
    }
}

function signUp($req)
{
    $data = isSignUpValid($req);

    if (isset($data['invalid'])) {
        $_SESSION['errors'] = $data['invalid'];
        $params = '?' . http_build_query($req);
        header('location: /php-project/pages/public/signup.php' . $params);
    } else {
        $user = registerUser($data);

        if ($user) {
            if (!$user['deleted_at']) {
                $_SESSION['id'] = $user['id'];
                $_SESSION['first_name'] = $user['first_name'];

                setcookie("id", $data['id'], time() + (60 * 60 * 24 * 30), "/");
                setcookie("first_name", $data['first_name'], time() + (60 * 60 * 24 * 30), "/");
                header('location: /php-project/pages/secure/dashboard.php');
            }
        }
    }
}

