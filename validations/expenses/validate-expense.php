<?php

function isExpenseValid($req)
{
    foreach ($req as $key => $value) {
        $req[$key] = trim($req[$key]);
    }

    $errors = [];

    if (!is_numeric($req['amount']) || $req['amount'] < 0) {
        $errors['amount'] = 'The Amount field must be a non-negative numeric value.';
    }

    if (strlen($req['note']) > 255) {
        $errors['note'] = 'The Note field cannot exceed 255 characters.';
    }

    $currentDate = date('Y-m-d');
    if (empty($req['date']) || strtotime($req['date']) === false || $req['date'] < $currentDate) {
        $errors['date'] = 'The Date field must be a valid date and cannot be in the past.';
    }

    if (!empty($errors)) {
        return ['invalid' => $errors];
    }

    return $req;
}
?>
