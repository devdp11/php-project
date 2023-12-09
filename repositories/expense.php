<?php
require_once __DIR__ . '../../db/connection.php';

function getAll()
{
    $PDOStatement = $GLOBALS['pdo']->query('SELECT * FROM expenses;');
    $expenses = [];
    while ($expensesList = $PDOStatement->fetch()) {
        $expenses[] = $expensesList;
    }
    return $expenses;
}

function getExpensesByPayStatus($isPaid)
{
    $query = "SELECT * FROM expenses WHERE paid = :isPaid;";
    $PDOStatement = $GLOBALS['pdo']->prepare($query);
    $PDOStatement->bindValue(':isPaid', $isPaid, PDO::PARAM_INT);
    $PDOStatement->execute();
    $expenses = [];
    while ($expense = $PDOStatement->fetch()) {
        $expenses[] = $expense;
    }
    return $expenses;
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

?>