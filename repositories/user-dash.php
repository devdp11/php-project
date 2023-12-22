<?php
require_once __DIR__ . '../../db/connection.php';

function getExpensesCountById($userId) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT COUNT(*) AS expenseCount FROM expenses WHERE user_id = :user_id AND payed = 0 AND deleted_at IS NULL");
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['expenseCount'];
}

function getPaidExpensesCountById($userId) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT COUNT(*) AS paidExpenseCount FROM expenses WHERE user_id = :user_id AND payed = 1 AND deleted_at IS NULL");
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['paidExpenseCount'];
}

function getAmountExpensesById($userId) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT SUM(amount) AS expenseAmount FROM expenses WHERE user_id = :user_id AND deleted_at IS NULL AND payed = 0");
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['expenseAmount'];
}

function getSharedExpensesCountBySharerId($userId) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT COUNT(*) AS sharedExpenseCount FROM shared_expenses WHERE sharer_user_id = :user_id AND deleted_at IS NULL");
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['sharedExpenseCount'];
}

function getSharedExpensesCountByReceiverId($userId) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT COUNT(*) AS sharedExpenseCount FROM shared_expenses WHERE receiver_user_id = :user_id AND deleted_at IS NULL");
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['sharedExpenseCount'];
}

function getAmountSharedExpensesById($userId) {
    global $pdo;

    try {
        $query = "SELECT SUM(amount) AS sharedExpenseAmount 
                  FROM shared_expenses 
                  INNER JOIN expenses ON shared_expenses.expense_id = expenses.expense_id
                  WHERE shared_expenses.receiver_user_id = :user_id 
                    AND shared_expenses.deleted_at IS NULL 
                    AND expenses.deleted_at IS NULL";

        $stmt = $pdo->prepare($query);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['sharedExpenseAmount'] ?? 0;
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return 0;
    }
}

function getFutureExpensesCountById($userId)
{
    global $pdo;

    $stmt = $pdo->prepare("SELECT COUNT(*) AS futureExpenseCount FROM expenses WHERE user_id = :user_id AND date > NOW() AND payed = 0 AND deleted_at IS NULL");
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    return $result['futureExpenseCount'];
}

function getFutureExpensesDetailsById($userId)
{
    global $pdo;

    $query = "SELECT e.*, c.description as category_description
              FROM expenses e
              JOIN categories c ON e.category_id = c.id
              WHERE e.user_id = :user_id
                AND e.date > NOW()
                AND e.deleted_at IS NULL
                AND e.payed = 0";

    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetchAll(PDO::FETCH_ASSOC);

    return $result;
}
?>