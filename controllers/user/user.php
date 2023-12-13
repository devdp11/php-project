<?php
require_once __DIR__ . '/../../repositories/user.php';
require_once __DIR__ . '/../../validations/admin/validate-user.php';
@require_once __DIR__ . '/../../validations/session.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $validatedData = validatedUser($_POST);
    
    if (isset($validatedData['invalid'])) {
        $_SESSION['errors'] = $validatedData['invalid'];
        
        header('Location: ../../pages/secure/profile.php');
        exit();
    }

    $user = [
        'id' => $_SESSION['id'], 
        'first_name' => $validatedData['first_name'],
        'last_name' => $validatedData['last_name'],
        'email' => $validatedData['email'],
        'country' => $validatedData['country'],
        'birthdate' => $validatedData['birthdate'],
        'updated_at' => date('Y-m-d H:i:s'),
    ];

    $updateSuccess = updateUser($user);

    if ($updateSuccess) {
        $_SESSION['success'] = 'Profile updated successfully.';
        
        header('Location: ../../pages/secure/profile.php');
        exit();
    } else {
        $_SESSION['errors'] = ['update' => 'Error updating profile.'];
        
        header('Location: ../../pages/secure/profile.php');
        exit();
    }
} else {
    echo 'Invalid request method.';
}
?>
