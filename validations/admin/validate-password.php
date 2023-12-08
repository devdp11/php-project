<?php

function passwordIsValid($req)
{
    foreach ($req as $key => $value) {
        $req[$key] = trim($req[$key]);
    }

    if (empty($req['first_name']) || strlen($req['first_name']) < 3 || strlen($req['first_name']) > 255) {
        $errors['first_name'] = 'The Name field cannot be empty and must be between 3 and 255 characters';
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
