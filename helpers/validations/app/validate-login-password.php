<?php

function isLoginValid($req)
{
    foreach ($req as $key => $value) {
        $req[$key] =  trim($req[$key]);
    }

    if (!filter_var($req['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'The Email field cannot be empty and must have the email format, for example: nome@example.com.';
    }

    if (empty($req['password']) || strlen($req['password']) < 6) {
        $errors['password'] = 'The Password field cannot be empty and must be at least 6 characters long.';
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
            $errors['email'] = 'Wrong email or password.';
        }

        if (!password_verify($req['password'], $user['password'])) {
            $errors['password'] = 'Wrong email or password.';
        }

        if (isset($errors)) {
            return ['invalid' => $errors];
        }

        return $user;
    }
}
