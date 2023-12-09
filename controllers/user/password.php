<?php
require_once __DIR__ . '/../../validations/user/validate-update-password.php';
require_once __DIR__ . '/../../repositories/user.php';
@require_once __DIR__ . '/../../validations/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['id'])) {
        echo 'User ID not set in the session.';
        exit();
    }

    $user = [
        'id' => $_SESSION['id'],
        'current_password' => $_POST['current_password'],
        'new_password' => $_POST['new_password'],
        'repeat_password' => $_POST['repeat_password'],
    ];

    $validationResult = validatePasswordUpdate($user['id'], $user['current_password'], $user['new_password'], $user['repeat_password']);

    if ($validationResult !== true) {
        echo 'Validation failed: ' . $validationResult;
        exit();
    }

    $hashedPassword = password_hash($user['new_password'], PASSWORD_DEFAULT);

    $updateSuccess = updatePassword($user['id'], $hashedPassword);

    if ($updateSuccess) {
        header('Location: ../../pages/secure/profile.php');
        exit();
    } else {
        echo 'Error updating password.';
    }
} else {
    echo 'Invalid request method.';
}
?>
