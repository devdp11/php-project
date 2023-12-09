<?php
    require_once __DIR__ . '/../../db/connection.php';

    if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Defina a data/hora atual como deleted_at
    $stmt = $pdo->prepare("UPDATE users SET deleted_at = NOW() WHERE id = ?");
    $stmt->execute([$id]);

    // Redirecione de volta à página de usuários após a exclusão
    header("Location: display-users.php?success=1");
    exit();
}
?>