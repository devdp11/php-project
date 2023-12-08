<?php
require_once __DIR__ . '../../../repositories/userRepository.php';

function validatePasswordUpdate($userId, $currentPassword, $newPassword, $repeatPassword)
{
    $hashedUserPassword = getHashedPasswordById($userId);

    if ($hashedUserPassword === false) {
        return 'User not found.';
    }

    if (!password_verify($currentPassword, $hashedUserPassword)) {
        return 'Current password is incorrect.';
    }

    if ($currentPassword === $newPassword) {
        return 'New password must be different from the current password.';
    }

    if ($newPassword !== $repeatPassword) {
        return 'New password and repeated password do not match.';
    }

    if (!preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^a-zA-Z\d])\S{8,}$/', $newPassword)) {
        return 'Password must be at least 8 characters long and contain at least one digit, one uppercase letter, one lowercase letter, and one special character.';
    }

    return true;
}
?>