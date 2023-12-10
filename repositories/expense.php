<?php
require_once __DIR__ . '../../db/connection.php';

function getAllExpensesById($userId)
{
    try {
        $query = 'SELECT expenses.*, categories.description AS category_description, methods.description AS payment_description ';
        $query .= 'FROM expenses ';
        $query .= 'LEFT JOIN categories ON expenses.category_id = categories.id ';
        $query .= 'LEFT JOIN methods ON expenses.payment_id = methods.id ';
        $query .= 'WHERE expenses.user_id = :userId';

        $PDOStatement = $GLOBALS['pdo']->prepare($query);
        $PDOStatement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $PDOStatement->execute();

        $expenses = [];

        while ($expensesList = $PDOStatement->fetch(PDO::FETCH_ASSOC)) {
            $expenses[] = $expensesList;
        }

        return $expenses;
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

function getExpensesByCategory($categoryId)
{
    $query = "SELECT * FROM expenses WHERE category_id = :categoryId;";
    $PDOStatement = $GLOBALS['pdo']->prepare($query);
    $PDOStatement->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
    $PDOStatement->execute();
    $expenses = [];
    while ($expense = $PDOStatement->fetch()) {
        $expenses[] = $expense;
    }
    return $expenses;
}

function getExpensesByMaxValue()
{
    $query = "SELECT * FROM expenses ORDER BY amount DESC LIMIT 1;";
    $PDOStatement = $GLOBALS['pdo']->query($query);
    $expense = $PDOStatement->fetch();
    return $expense;
}

function getExpensesByPaymentMethod($paymentMethodId)
{
    $query = "SELECT * FROM expenses WHERE payment_id = :paymentMethodId;";
    $PDOStatement = $GLOBALS['pdo']->prepare($query);
    $PDOStatement->bindValue(':paymentMethodId', $paymentMethodId, PDO::PARAM_INT);
    $PDOStatement->execute();
    $expenses = [];
    while ($expense = $PDOStatement->fetch()) {
        $expenses[] = $expense;
    }
    return $expenses;
}

function getAllCategories()
{
    $stmt = $GLOBALS['pdo']->prepare('SELECT * FROM categories WHERE deleted_at IS NULL;');
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getAllMethods()
{
    $stmt = $GLOBALS['pdo']->prepare('SELECT * FROM methods WHERE deleted_at IS NULL;');
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

?>