<?php
require_once __DIR__ . '../../db/connection.php';

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

function getSharedExpensesBySharer($name)
{
    try {
        $query = 'SELECT se.*, u.first_name AS sharer_first_name, u.last_name AS sharer_last_name, ';
        $query .= 'expenses.*, categories.description AS category_description, methods.description AS payment_description ';
        $query .= 'FROM shared_expenses se ';
        $query .= 'JOIN users u ON se.sharer_user_id = u.id ';
        $query .= 'JOIN expenses ON se.expense_id = expenses.expense_id ';
        $query .= 'LEFT JOIN categories ON expenses.category_id = categories.id ';
        $query .= 'LEFT JOIN methods ON expenses.payment_id = methods.id ';
        $query .= 'WHERE u.first_name LIKE :name OR u.last_name LIKE :name';

        $PDOStatement = $GLOBALS['pdo']->prepare($query);
        $nameParam = "%$name%";
        $PDOStatement->bindParam(':name', $nameParam, PDO::PARAM_STR);
        $PDOStatement->execute();

        $result = $PDOStatement->fetchAll(PDO::FETCH_ASSOC);

        return $result;
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

function deleteSharedExpensesByUserId($userId)
{
    $sqlDeleteExpenses = "UPDATE shared_expenses SET deleted_at = NOW() WHERE receiver_user_id = :userId OR sharer_user_id = :userId";
    $deleteStatement = $GLOBALS['pdo']->prepare($sqlDeleteExpenses);
    $deleteStatement->execute([':userId' => $userId]);
}
?>