<?php

function isSignUpValid($req)
{
    foreach ($req as $key => $value) {
        $req[$key] =  trim($req[$key]);
    }

    if (empty($req['first_name']) || strlen($req['first_name']) < 3 || strlen($req['first_name']) > 255) {
        $errors['first_name'] = 'The First Name field cannot be empty and must be between 3 and 255 characters.';
    }
    
    if (empty($req['last_name']) || strlen($req['last_name']) < 3 || strlen($req['last_name']) > 255) {
        $errors['last_name'] = 'The Last Name field cannot be empty and must be between 3 and 255 characters.';
    }

    if (!filter_var($req['email'], FILTER_VALIDATE_EMAIL)) {
        $errors['email'] = 'The Email field must not be empty and must have an email format, such as: name@example.com.';
    }

    if (getByEmail($req['email'])) {
        $errors['email'] = 'Email already registered in our system!';
        return ['invalid' => $errors];
    }

    if (empty($req['password']) || !preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^a-zA-Z\d])\S{8,}$/', $req['password'])) {
        $errors['password'] = 'Password must be at least 8 characters long and contain at least one digit, one uppercase letter, one lowercase letter, and one special character!';
    }    

    if ($req['confirm_password'] != $req['password']) {
        $errors['confirm_password'] = 'The Confirm Password field must not be empty and must be the same as the Password field!';
    }

    if (isset($errors)) {
        return ['invalid' => $errors];
    }

    return $req;
}

?>