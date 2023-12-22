<?php
require_once __DIR__ . '../../db/connection.php';

function getAllExpensesByUserId($userId)
{
    try {
        $query = 'SELECT expenses.*, categories.description AS category_description, methods.description AS payment_description ';
        $query .= 'FROM expenses ';
        $query .= 'LEFT JOIN categories ON expenses.category_id = categories.id ';
        $query .= 'LEFT JOIN methods ON expenses.payment_id = methods.id ';
        $query .= 'WHERE expenses.user_id = :userId AND expenses.deleted_at IS NULL';

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

function getExpensesByDate($userId, $date)
{
    try {
        $query = 'SELECT expenses.*, categories.description AS category_description, methods.description AS payment_description ';
        $query .= 'FROM expenses ';
        $query .= 'LEFT JOIN categories ON expenses.category_id = categories.id ';
        $query .= 'LEFT JOIN methods ON expenses.payment_id = methods.id ';
        $query .= 'WHERE expenses.user_id = :userId AND DATE(expenses.date) = DATE(:date) AND expenses.deleted_at IS NULL';

        $PDOStatement = $GLOBALS['pdo']->prepare($query);
        $PDOStatement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $PDOStatement->bindParam(':date', $date, PDO::PARAM_STR);
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

function getExpensesByAmount($userId, $amount)
{
    try {
        $query = 'SELECT * FROM expenses ';
        $query .= 'WHERE user_id = :userId AND amount = :amount AND deleted_at IS NULL';

        $PDOStatement = $GLOBALS['pdo']->prepare($query);
        $PDOStatement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $PDOStatement->bindParam(':amount', $amount, PDO::PARAM_STR);
        $PDOStatement->execute();

        $expenses = $PDOStatement->fetchAll(PDO::FETCH_ASSOC);

        return $expenses;
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

function getExpensesByCategoryFromUserId($userId, $categoryId)
{
    try {
        $query = 'SELECT expenses.*, categories.description AS category_description, methods.description AS payment_description ';
        $query .= 'FROM expenses ';
        $query .= 'LEFT JOIN categories ON expenses.category_id = categories.id ';
        $query .= 'LEFT JOIN methods ON expenses.payment_id = methods.id ';
        $query .= 'WHERE expenses.user_id = :userId AND expenses.category_id = :categoryId AND expenses.deleted_at IS NULL';

        $PDOStatement = $GLOBALS['pdo']->prepare($query);
        $PDOStatement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $PDOStatement->bindParam(':categoryId', $categoryId, PDO::PARAM_INT);
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

function getExpensesByPaymentMethodFromUserId($userId, $paymentMethodId)
{
    try {
        $query = 'SELECT expenses.*, categories.description AS category_description, methods.description AS payment_description ';
        $query .= 'FROM expenses ';
        $query .= 'LEFT JOIN categories ON expenses.category_id = categories.id ';
        $query .= 'LEFT JOIN methods ON expenses.payment_id = methods.id ';
        $query .= 'WHERE expenses.user_id = :userId AND expenses.payment_id = :paymentMethodId AND expenses.deleted_at IS NULL';

        $PDOStatement = $GLOBALS['pdo']->prepare($query);
        $PDOStatement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $PDOStatement->bindParam(':paymentMethodId', $paymentMethodId, PDO::PARAM_INT);
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

function getExpensesByDescription($userId, $description)
{
    try {
        $query = 'SELECT expenses.*, categories.description AS category_description, methods.description AS payment_description ';
        $query .= 'FROM expenses ';
        $query .= 'LEFT JOIN categories ON expenses.category_id = categories.id ';
        $query .= 'LEFT JOIN methods ON expenses.payment_id = methods.id ';
        $query .= 'WHERE expenses.user_id = :userId AND expenses.description LIKE :description AND expenses.deleted_at IS NULL';

        $PDOStatement = $GLOBALS['pdo']->prepare($query);
        $descriptionParam = "%{$description}%";
        $PDOStatement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $PDOStatement->bindParam(':description', $descriptionParam, PDO::PARAM_STR);
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

function getExpensesByPaymentStatus($userId, $paymentStatus)
{
    try {
        $query = 'SELECT expenses.*, categories.description AS category_description, methods.description AS payment_description ';
        $query .= 'FROM expenses ';
        $query .= 'LEFT JOIN categories ON expenses.category_id = categories.id ';
        $query .= 'LEFT JOIN methods ON expenses.payment_id = methods.id ';

        if ($paymentStatus === 'Paid' || $paymentStatus === 'Unpaid') {
            $paymentStatusValue = ($paymentStatus === 'Paid') ? 1 : 0;
            $query .= 'WHERE expenses.user_id = :userId AND expenses.payed = :paymentStatusValue AND expenses.deleted_at IS NULL';
        } else {
            $query .= 'WHERE expenses.user_id = :userId AND expenses.deleted_at IS NULL';
        }

        $PDOStatement = $GLOBALS['pdo']->prepare($query);
        $PDOStatement->bindParam(':userId', $userId, PDO::PARAM_INT);

        if ($paymentStatus === 'Paid' || $paymentStatus === 'Unpaid') {
            $PDOStatement->bindParam(':paymentStatusValue', $paymentStatusValue, PDO::PARAM_INT);
        }

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
?>