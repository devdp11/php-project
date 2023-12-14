<?php

function validatedUser($req)
{
    foreach ($req as $key => $value) {
        $req[$key] =  trim($req[$key]);
    }

    if (empty($req['first_name']) || strlen($req['first_name']) < 3 || strlen($req['first_name']) > 255) {
        $errors['first_name'] = 'The Name field cannot be empty and must be between 3 and 255 characters';
    }

    if (empty($req['last_name']) || strlen($req['last_name']) < 3 || strlen($req['last_name']) > 255) {
        $errors['last_name'] = 'The Last Name field cannot be empty and must be between 3 and 255 characters';
    }

    if (!empty($req['email'])) {
        $user = user();
        $existingUser = getByEmail($req['email']);

        if ($existingUser && $existingUser['id'] != $user['id']) {
            $errors['email'] = 'Email already registered in our system.';
        }
    } else {
        $errors['email'] = 'The Email field cannot be empty.';
    }

    if (!filter_var($req['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'The Email field must have a valid email format, for example: name@example.com.';
    }

    if (empty($req['password']) || !preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^a-zA-Z\d])\S{8,}$/', $req['password'])) {
        $errors['password'] = 'Password must be at least 8 characters long and contain at least one digit, one uppercase letter, one lowercase letter, and one special character!';
    }

    if (!empty($req['birthdate'])) {
        $currentDate = new DateTime();
        $birthdate = DateTime::createFromFormat('Y-m-d', $req['birthdate']);

        if ($birthdate > $currentDate) {
            $errors['birthdate'] = 'Birthdate cannot be a future date.';
        }
    }

    $req['admin'] = !empty($req['admin']) ? 1 : 0;

    if (isset($errors)) {
        return ['invalid' => $errors];
    }
    return $req;
}
?>
