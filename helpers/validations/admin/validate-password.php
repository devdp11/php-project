<?php

function passwordIsValid($req)
{
    foreach ($req as $key => $value) {
        $req[$key] = trim($req[$key]);
    }

    if (empty($req['name']) || strlen($req['name']) < 3 || strlen($req['name']) > 255) {
        $errors['name'] = 'The Name field cannot be empty and must be between 3 and 255 characters';
    }

    if (!empty($req['password']) && strlen($req['password']) < 6) {
        $errors['password'] = 'The Password field cannot be empty and must be at least 6 characters long.';
    }

    if (!empty($req['confirm_password']) && ($req['confirm_password']) != $req['password']) {
        $errors['confirm_password'] = 'The Confirm Password field must not be empty and must be the same as the Password field.';
    }

    if (isset($errors)) {
        return ['invalid' => $errors];
    }

    return $req;
}

?>
