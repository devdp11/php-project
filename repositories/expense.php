<?php
require_once __DIR__ . '../../db/connection.php';

/* EXPENSES COMBO BOX */
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

/* EXPENSES */
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

function getExpenseById($expenseId)
{
    global $pdo;
    try {
        $query = 'SELECT expenses.*, categories.description AS category_description, methods.description AS payment_description ';
        $query .= 'FROM expenses ';
        $query .= 'LEFT JOIN categories ON expenses.category_id = categories.id ';
        $query .= 'LEFT JOIN methods ON expenses.payment_id = methods.id ';
        $query .= 'WHERE expenses.expense_id = :expenseId';

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
            payed = :payed,
            note = :note,
            user_id = :user_id,
            updated_at = CURRENT_TIMESTAMP
        WHERE
            expense_id = :expense_id";

        $PDOStatement = $GLOBALS['pdo']->prepare($sqlUpdate);

       $receiptImg = !empty($expenseData['receipt_img']) ? $expenseData['receipt_img'] : null;

        if (!empty($expenseData['receipt_img'])) {
            updateRcptImg($expenseId, $receiptImg);
        }

        $params = [
            ':category_id' => $expenseData['category_id'],
            ':description' => $expenseData['description'],
            ':payment_id' => $expenseData['payment_id'],
            ':amount' => $expenseData['amount'],
            ':date' => $expenseData['date'],
            ':payed' => $expenseData['payed'],
            ':note' => $expenseData['note'],
            ':user_id' => $expenseData['user_id'],
            ':expense_id' => $expenseId,
        ];

        $params = array_filter($params, function ($value) {
            return $value !== '';
        });

        return $PDOStatement->execute($params);
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

function updateRcptImg($expenseId, $rcpImg)
{
    $expenses['updated_at'] = date('Y-m-d H:i:s');

    $sqlUpdate = "UPDATE expenses SET
        receipt_img = :receipt_img,
        updated_at = :updated_at
        WHERE expense_id = :id";

    $PDOStatement = $GLOBALS['pdo']->prepare($sqlUpdate);

    $bindParams = [
        ':id' => $expenseId,
        ':receipt_img' => $rcpImg,
        ':updated_at' => $expenses['updated_at'],
    ];

    $success = $PDOStatement->execute($bindParams);

    return $success;
}

function softDeleteExpense($expenseId)
{
    $pdo = $GLOBALS['pdo'];

    $pdo->beginTransaction();

    try {
        $sqlUpdate = "UPDATE expenses SET deleted_at = NOW() WHERE expense_id = :expenseId";
        $updateStatement = $pdo->prepare($sqlUpdate);
        $updateStatement->execute([
            ':expenseId' => $expenseId,
        ]);

        $updateSuccess = $updateStatement->rowCount() > 0;

        if ($updateSuccess) {
            $sqlSharedUpdate = "
                UPDATE shared_expenses
                SET deleted_at = NOW()
                WHERE expense_id = :expenseId
                  AND deleted_at IS NULL
                  AND EXISTS (
                      SELECT 1
                      FROM expenses
                      WHERE expense_id = :expenseId
                  )
            ";
            $sharedUpdateStatement = $pdo->prepare($sqlSharedUpdate);
            $sharedUpdateStatement->execute([
                ':expenseId' => $expenseId,
            ]);

            $pdo->commit();

            return true;
        } else {
            $pdo->rollBack();

            return false;
        }
    } catch (PDOException $e) {
        error_log('Error deleting expense: ' . $e->getMessage());
        $pdo->rollBack();
        return false;
    }
}

function deleteExpensesByUserId($userId)
{
    $sqlDeleteExpenses = "UPDATE expenses SET deleted_at = NOW() WHERE user_id = :userId";
    $deleteStatement = $GLOBALS['pdo']->prepare($sqlDeleteExpenses);
    $deleteStatement->execute([':userId' => $userId]);
}
?>