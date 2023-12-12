<?php
require_once __DIR__ . '/../../validations/user/validate-update-password.php';
require_once __DIR__ . '/../../repositories/user.php';
@require_once __DIR__ . '/../../validations/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_SESSION['id'])) {
        $errors[] = 'User ID not set in the session.';
    }

    $user = [
        'id' => $_SESSION['id'],
        'current_password' => $_POST['current_password'],
        'new_password' => $_POST['new_password'],
        'repeat_password' => $_POST['repeat_password'],
    ];

    $validationResult = validatePasswordUpdate($user['id'], $user['current_password'], $user['new_password'], $user['repeat_password']);

    if ($validationResult !== true) {
        $errors[] = 'Validation failed: ' . $validationResult;
    }

    if (empty($errors)) {
        $hashedPassword = password_hash($user['new_password'], PASSWORD_DEFAULT);

        $updateSuccess = updatePassword($user['id'], $hashedPassword);

        if ($updateSuccess) {
            $successMessage = 'Password updated successfully.';
        } else {
            $errors[] = 'Error updating password.';
        }
    }
} else {
    $errors[] = 'Invalid request method.';
}

if (!empty($errors)) {
    $_SESSION['errors'] = $errors;
    header('Location: ../../pages/secure/profile.php');
    exit();
}

if ($successMessage) {
    $_SESSION['success'] = $successMessage;
    header('Location: ../../pages/secure/profile.php');
    exit();
}
?>
