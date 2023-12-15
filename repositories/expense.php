<?php
require_once __DIR__ . '../../db/connection.php';

/* QUERIES FOR DATA FILTER */

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

/* SHARED EXPENSES */

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

/* CALENDAR */

function getExpensesToCalendar($userId) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT expense_id, description, date FROM expenses WHERE user_id = :user_id AND deleted_at IS NULL");
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();
    
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

/* AFTER DELETING A USER */

function deleteExpensesByUserId($userId)
{
    $sqlDeleteExpenses = "UPDATE expenses SET deleted_at = NOW() WHERE user_id = :userId";
    $deleteStatement = $GLOBALS['pdo']->prepare($sqlDeleteExpenses);
    $deleteStatement->execute([':userId' => $userId]);
}

function deleteSharedExpensesByUserId($userId)
{
    $sqlDeleteExpenses = "UPDATE shared_expenses SET deleted_at = NOW() WHERE receiver_user_id = :userId OR sharer_user_id = :userId";
    $deleteStatement = $GLOBALS['pdo']->prepare($sqlDeleteExpenses);
    $deleteStatement->execute([':userId' => $userId]);
}

/* DASHBOARD */

function getExpensesCountById($userId) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT COUNT(*) AS expenseCount FROM expenses WHERE user_id = :user_id AND deleted_at IS NULL");
    $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
    $stmt->execute();

    $result = $stmt->fetch(PDO::FETCH_ASSOC);
    return $result['expenseCount'];
}

function getAmountExpensesById($userId) {
    global $pdo;

    $stmt = $pdo->prepare("SELECT SUM(amount) AS expenseAmount FROM expenses WHERE user_id = :user_id AND deleted_at IS NULL");
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

function getFutureExpenses($userId, $limit = 10)
{
    try {
        $currentDate = date('Y-m-d');
        $query = 'SELECT expenses.*, categories.description AS category_description, methods.description AS payment_description ';
        $query .= 'FROM expenses ';
        $query .= 'LEFT JOIN categories ON expenses.category_id = categories.id ';
        $query .= 'LEFT JOIN methods ON expenses.payment_id = methods.id ';
        $query .= 'WHERE user_id = :user_id AND date >= :current_date AND expenses.payed = 0 AND expenses.deleted_at IS NULL ORDER BY date LIMIT :limit';

        $PDOStatement = $GLOBALS['pdo']->prepare($query);
        $PDOStatement->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $PDOStatement->bindParam(':current_date', $currentDate);
        $PDOStatement->bindParam(':limit', $limit, PDO::PARAM_INT);
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