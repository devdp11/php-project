<?php
require_once __DIR__ . '../../db/connection.php';

function getDeletedUsersCount() {
    $stmt = $GLOBALS['pdo']->prepare('SELECT COUNT(*) as deleted_count FROM users WHERE deleted_at IS NOT NULL;');
    $stmt->execute();
    $deletedCount = $stmt->fetch(PDO::FETCH_ASSOC)['deleted_count'];
    return $deletedCount;
}

function getActiveUsersCount() {
    $stmt = $GLOBALS['pdo']->prepare('SELECT COUNT(*) as active_count FROM users WHERE deleted_at IS NULL;');
    $stmt->execute();
    $activeCount = $stmt->fetch(PDO::FETCH_ASSOC)['active_count'];
    return $activeCount;
}

function getUsersByCountryCount() {
    $stmt = $GLOBALS['pdo']->prepare('SELECT country, COUNT(*) as user_count FROM users WHERE deleted_at IS NULL GROUP BY country;');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUsersWithSharedExpensesCount() {
    $stmt = $GLOBALS['pdo']->prepare('SELECT COUNT(DISTINCT receiver_user_id) as users_with_shared_expenses_count 
                                      FROM shared_expenses 
                                      WHERE deleted_at IS NULL;');
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['users_with_shared_expenses_count'];
}

function getUsersWithExpensesCount() {
    $stmt = $GLOBALS['pdo']->prepare('SELECT COUNT(DISTINCT user_id) as users_with_expenses_count 
                                      FROM expenses 
                                      WHERE deleted_at IS NULL;');
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['users_with_expenses_count'];
}

?>