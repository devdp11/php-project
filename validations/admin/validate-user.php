<?php

function validatedUser($req)
{
    foreach ($req as $key => $value) {
        $req[$key] =  trim($req[$key]);
    }

    if (empty($req['name']) || strlen($req['name']) < 3 || strlen($req['name']) > 255) {
        $errors['name'] = 'The Name field cannot be empty and must be between 3 and 255 characters';
    }

    if (empty($req['lastname']) || strlen($req['lastname']) < 3 || strlen($req['lastname']) > 255) {
        $errors['lastname'] = 'The Last Name field cannot be empty and must be between 3 and 255 characters';
    }

    if (!filter_var($req['phoneNumber'], FILTER_VALIDATE_INT) || strlen($req['phoneNumber']) != 9) {
        $errors['phoneNumber'] = 'The Mobile phone field cannot be empty and must have 9 numbers.';
    }

    if (!filter_var($req['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'The Email field cannot be empty and must have the email format, for example: nome@example.com.';
    }

    if (getByEmail($req['email'])) {
        $errors['email'] = 'Email already registered in our system.';
        return ['invalid' => $errors];
    }

    if (!empty($req['password']) && strlen($req['password']) < 6) {
        $errors['password'] = 'The Password field cannot be empty and must be at least 6 characters long.';
    }

    if (!empty($req['confirm_password']) && ($req['confirm_password']) != $req['password']) {
        $errors['confirm_password'] = 'The Confirm Password field must not be empty and must be the same as the Password field.';
    }

    $req['administrator'] = !empty($req['administrator']) == 'on' ? true : false;

    if (isset($errors)) {
        return ['invalid' => $errors];
    }
    return $req;
}
?>