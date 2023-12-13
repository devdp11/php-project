<?php
require_once __DIR__ . '../../db/connection.php';

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

function getExpenseById($expenseId, $pdo)
{
    try {
        $query = 'SELECT expenses.*, categories.description AS category_description, methods.description AS payment_description ';
        $query .= 'FROM expenses ';
        $query .= 'LEFT JOIN categories ON expenses.category_id = categories.id ';
        $query .= 'LEFT JOIN methods ON expenses.payment_id = methods.id ';
        $query .= 'WHERE expenses.id = :expenseId';

        $PDOStatement = $pdo->prepare($query);
        $PDOStatement->bindParam(':expenseId', $expenseId, PDO::PARAM_INT);
        $PDOStatement->execute();

        return $PDOStatement->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        throw new Exception('Error fetching expense details: ' . $e->getMessage());
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

function createSharedExpense($sharedExpense)
{
    try {
        $sqlCreate = "INSERT INTO shared_expenses (
            receiver_user_id,
            sharer_user_id,
            expense_id,
            created_at,
            updated_at
        ) VALUES (
            :receiver_user_id,
            :sharer_user_id,
            :expense_id,
            NOW(),
            NOW()
        )";

        $PDOStatement = $GLOBALS['pdo']->prepare($sqlCreate);

        $success = $PDOStatement->execute([
            ':receiver_user_id' => $sharedExpense['receiver_user_id'],
            ':sharer_user_id' => $sharedExpense['sharer_user_id'],
            ':expense_id' => $sharedExpense['expense_id'],
        ]);

        return $success;
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

function isExpenseShared($expenseId, $sharerUserId, $receiverUserId)
{
    try {
        $sql = 'SELECT COUNT(*) FROM shared_expenses WHERE expense_id = :expense_id AND sharer_user_id = :sharer_user_id AND receiver_user_id = :receiver_user_id AND deleted_at IS NULL';

        $PDOStatement = $GLOBALS['pdo']->prepare($sql);
        $PDOStatement->bindParam(':expense_id', $expenseId, PDO::PARAM_INT);
        $PDOStatement->bindParam(':sharer_user_id', $sharerUserId, PDO::PARAM_INT);
        $PDOStatement->bindParam(':receiver_user_id', $receiverUserId, PDO::PARAM_INT);
        $PDOStatement->execute();

        return $PDOStatement->fetchColumn() > 0;
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

function getAllSharedExpensesById($userId)
{
    try {
        $query = 'SELECT expenses.*, categories.description AS category_description, methods.description AS payment_description, ';
        $query .= 'shared_expenses.receiver_user_id, users.first_name AS sharer_first_name, users.last_name AS sharer_last_name ';
        $query .= 'FROM expenses ';
        $query .= 'INNER JOIN shared_expenses ON expenses.expense_id = shared_expenses.expense_id ';
        $query .= 'LEFT JOIN categories ON expenses.category_id = categories.id ';
        $query .= 'LEFT JOIN methods ON expenses.payment_id = methods.id ';
        $query .= 'LEFT JOIN users ON shared_expenses.sharer_user_id = users.id ';
        $query .= 'WHERE shared_expenses.receiver_user_id = :userId AND expenses.deleted_at IS NULL AND shared_expenses.deleted_at IS NULL';

        $PDOStatement = $GLOBALS['pdo']->prepare($query);
        $PDOStatement->bindParam(':userId', $userId, PDO::PARAM_INT);
        $PDOStatement->execute();

        return $PDOStatement->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return [];
    }
}

function removeSharedExpense($expenseId, $UserId)
{
    try {
        $stmt = $GLOBALS['pdo']->prepare('
            UPDATE shared_expenses
            SET deleted_at = NOW()
            WHERE expense_id = :expense_id
              AND receiver_user_id = :receiver_user_id
              AND deleted_at IS NULL
        ');

        $stmt->bindParam(':expense_id', $expenseId, PDO::PARAM_INT);
        $stmt->bindParam(':receiver_user_id', $UserId, PDO::PARAM_INT);

        return $stmt->execute();
    } catch (PDOException $e) {
        error_log('Error removing shared expense: ' . $e->getMessage());
        return false;
    }
}

?>