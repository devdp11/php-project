<?php
require_once __DIR__ . '../../db/connection.php';

function getAllExpensesById($userId)
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

function getExpensesById($expenseId)
{
    try {
        $query = 'SELECT expenses.*, categories.description AS category_description, methods.description AS payment_description ';
        $query .= 'FROM expenses ';
        $query .= 'LEFT JOIN categories ON expenses.category_id = categories.id ';
        $query .= 'LEFT JOIN methods ON expenses.payment_id = methods.id ';
        $query .= 'WHERE expenses.expense_id = :expenseId AND expenses.deleted_at IS NULL';

        $PDOStatement = $GLOBALS['pdo']->prepare($query);
        $PDOStatement->bindParam(':expenseId', $expenseId, PDO::PARAM_INT);
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
    $query = "SELECT * FROM expenses WHERE category_id = :categoryId AND deleted_at IS NULL;";
    $PDOStatement = $GLOBALS['pdo']->prepare($query);
    $PDOStatement->bindValue(':categoryId', $categoryId, PDO::PARAM_INT);
    $PDOStatement->execute();
    $expenses = [];
    while ($expense = $PDOStatement->fetch()) {
        $expenses[] = $expense;
    }
    return $expenses;
}

function getExpensesByPaymentMethod($paymentMethodId)
{
    $query = "SELECT * FROM expenses WHERE payment_id = :paymentMethodId AND deleted_at IS NULL;";
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
    $stmt = $GLOBALS['pdo']->prepare('SELECT * FROM methods WHERE deleted_at IS NULL AND description <> "None";');
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getMethodByDescription($description)
{
    global $pdo;

    $query = "SELECT * FROM methods WHERE description = :description AND deleted_at IS NULL";
    $statement = $pdo->prepare($query);
    $statement->execute([':description' => $description]);

    $result = $statement->fetch(PDO::FETCH_ASSOC);

    return $result;
}

function createExpense($expense)
{
    try {
        $sqlCreate = "INSERT INTO expenses (
            category_id,
            description,
            payment_id,
            amount,
            date,
            receipt_img,
            payed,
            note,
            user_id,
            created_at,
            updated_at
        ) VALUES (
            :category_id,
            :description,
            :payment_id,
            :amount,
            :date,
            :receipt_img,
            :payed,
            :note,
            :user_id,
            NOW(),
            NOW()
        )";

        $PDOStatement = $GLOBALS['pdo']->prepare($sqlCreate);

        $success = $PDOStatement->execute([
            ':category_id' => $expense['category_id'],
            ':description' => $expense['description'],
            ':payment_id' => $expense['payment_id'],
            ':amount' => $expense['amount'],
            ':date' => $expense['date'],
            ':receipt_img' => $expense['receipt_img'],
            ':payed' => $expense['payed'],
            ':note' => $expense['note'],
            ':user_id' => $expense['user_id'],
        ]);

        if ($success) {
            $expense['expense_id'] = $GLOBALS['pdo']->lastInsertId();
        }

        return $success;
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

function updateExpense($expenseId, $expenseData)
{
    try {
        $sqlUpdate = "UPDATE expenses SET
            category_id = :category_id,
            description = :description,
            payment_id = :payment_id,
            amount = :amount,
            date = :date,
            receipt_img = :receipt_img,
            payed = :payed,
            note = :note,
            user_id = :user_id,
            updated_at = NOW()
        WHERE
            id = :expense_id";

        $PDOStatement = $GLOBALS['pdo']->prepare($sqlUpdate);

        return $PDOStatement->execute([
            ':category_id' => $expenseData['category_id'],
            ':description' => $expenseData['description'],
            ':payment_id' => $expenseData['payment_id'],
            ':amount' => $expenseData['amount'],
            ':date' => $expenseData['date'],
            ':receipt_img' => $expenseData['receipt_img'],
            ':payed' => $expenseData['payed'],
            ':note' => $expenseData['note'],
            ':user_id' => $expenseData['user_id'],
            ':expense_id' => $expenseId,
        ]);
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

function softDeleteExpense($expenseId)
{
    $sqlUpdate = "UPDATE expenses SET
                    deleted_at = NOW()
                  WHERE expense_id = :expenseId;";
    $updateStatement = $GLOBALS['pdo']->prepare($sqlUpdate);
    $updateSuccess = $updateStatement->execute([
        ':expenseId' => $expenseId,
    ]);

    return $updateSuccess;
}

?>