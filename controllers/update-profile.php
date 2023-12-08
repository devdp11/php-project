<?php
require_once __DIR__ . '/../middlewares/middleware-user.php';
@require_once __DIR__ . '/../validations/session.php';
require_once __DIR__ . '/../db/connection.php';
require_once __DIR__ . '/../repositories/userRepository.php';  // Certifique-se de incluir o caminho correto para o seu repositório de usuários

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $user = user();

    // Coletar dados do formulário
    $user['first_name'] = $_POST['first_name'];
    $user['last_name'] = $_POST['last_name'];
    $user['email'] = $_POST['email'];
    $user['country'] = $_POST['country'];
    $user['birthdate'] = $_POST['birthdate'];

    // Chamar a função updateUser para atualizar o perfil do usuário
    $success = updateUser($user);


    if ($success) {
        $_SESSION['success'] = 'Profile updated successfully.';
    } else {
        $_SESSION['error'] = 'Failed to update profile.';
    }

    // Redirecionar para a página de perfil
    header('Location: ../pages/secure/profile.php');
    exit();
} else {
    // Se a solicitação não for do tipo POST ou o parâmetro 'update_profile' não estiver presente, redirecione para a página de perfil
    header('Location: ../pages/secure/profile.php');
    exit();
}
?>