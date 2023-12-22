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

function getExpensesCountByCategory() {
    $stmt = $GLOBALS['pdo']->prepare('SELECT c.description AS category, COUNT(e.expense_id) AS expense_count 
    FROM categories c LEFT JOIN expenses e ON c.id = e.category_id AND e.deleted_at IS NULL GROUP BY c.id;');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getExpensesCountByPayment() {
    $stmt = $GLOBALS['pdo']->prepare('SELECT m.description AS payment, COUNT(e.expense_id) AS expense_count 
    FROM methods m LEFT JOIN expenses e ON m.id = e.payment_id AND e.deleted_at IS NULL GROUP BY m.id;');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getTotalExpensesCount() {
    $stmt = $GLOBALS['pdo']->prepare('SELECT COUNT(*) as total_expenses_count FROM expenses WHERE deleted_at IS NULL;');
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['total_expenses_count'];
}

function getTotalExpensesAmount() {
    $stmt = $GLOBALS['pdo']->prepare('SELECT SUM(amount) as total_expenses_amount FROM expenses WHERE deleted_at IS NULL;');
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['total_expenses_amount'];
}

function categoryExists($description) {
    $stmt = $GLOBALS['pdo']->prepare('SELECT COUNT(*) as category_count FROM categories WHERE description = :description AND deleted_at IS NULL;');
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['category_count'] > 0;
}

function paymentMethodExists($description) {
    $stmt = $GLOBALS['pdo']->prepare('SELECT COUNT(*) as payment_count FROM methods WHERE description = :description AND deleted_at IS NULL;');
    $stmt->bindParam(':description', $description, PDO::PARAM_STR);
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['payment_count'] > 0;
}

function createCategory($category)
{
    try {
        $sqlCreate = "INSERT INTO categories ( description, created_at, updated_at ) VALUES ( :description, NOW(), NOW() )";

        $PDOStatement = $GLOBALS['pdo']->prepare($sqlCreate);
        $success = $PDOStatement->execute([
            ':description' => $category['description'],
        ]);

        return $success;
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

function createPaymentMethod($paymentMethod)
{
    try {
        $sqlCreate = "INSERT INTO methods ( description, created_at, updated_at ) VALUES ( :description, NOW(), NOW() )";

        $PDOStatement = $GLOBALS['pdo']->prepare($sqlCreate);
        $success = $PDOStatement->execute([
            ':description' => $paymentMethod['description'],
        ]);

        return $success;
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}


?>