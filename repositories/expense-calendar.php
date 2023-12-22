<?php
require_once __DIR__ . '../../db/connection.php';

function getExpensesToCalendar($userId) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT expense_id, description, date FROM expenses WHERE user_id = :user_id AND deleted_at IS NULL");
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>