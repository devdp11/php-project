<?php

function isLoginValid($req)
{
    foreach ($req as $key => $value) {
        $req[$key] = trim($req[$key]);
    }

    if (empty($req['email'])) {
        $errors['email'] = 'The Email field cannot be empty!';
    } elseif (!filter_var($req['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'Invalid email format. Please use a valid email address.';
    }

    if (empty($req['password'])) {
        $errors['password'] = 'The Password field cannot be empty!';
    }

    if (isset($errors)) {
        return ['invalid' => $errors];
    }

    return $req;
}

function isPasswordValid($req)
{
    if (!isset($_SESSION['id'])) {

        $user = getByEmail($req['email']);

        if (!$user) {
            trigger_error('User not found!', E_USER_WARNING);
            return ['invalid' => ['email' => 'User not found!']];
        }

        if ($user['deleted_at'] !== null) {
            trigger_error('User is deleted!', E_USER_WARNING);
            return ['invalid' => ['email' => 'User is deleted!']];
        }

        if (!password_verify($req['password'], $user['password'])) {
            trigger_error('Wrong email or password!', E_USER_WARNING);
            return ['invalid' => ['email' => 'Wrong email or password!']];
        }

        return $user;
    }
}