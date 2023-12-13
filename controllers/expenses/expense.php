<?php
require_once __DIR__ . '/../../repositories/expense.php';
require_once __DIR__ . '/../../repositories/user.php';
@require_once __DIR__ . '/../../validations/session.php';
@require_once __DIR__ . '/../../validations/expenses/validate-expense.php';

if (isset($_POST['user'])) {
    $action = $_POST['user'];

    if ($action == 'add') {
        eadd($_POST);
    } elseif ($action == 'edit') {
        $expenseId = $_POST['expense_id'];
        eedit($expenseId, $_POST);
    } elseif ($action == 'delete') {
        $expenseId = $_POST['expense_id'];
        edelete($expenseId);
    } elseif ($action == 'share') {
        $expenseId = $_POST['expense_id'];
        $email = $_POST['email'];
        eshare($expenseId, $email);
    }
}

function eadd($postData)
{
    if (!isset($_SESSION['id'])) {
        $_SESSION['errors'][] = 'User ID not set in the session.';
        $params = '?' . http_build_query($postData);
        header('location: /php-project/pages/secure/expense.php' . $params);
    }

    $validationResult = isExpenseValid($postData);

    if (isset($validationResult['invalid'])) {
        $_SESSION['errors'] = $validationResult['invalid'];
        $params = '?' . http_build_query($postData);
        header('location: /php-project/pages/secure/expense.php' . $params);
    }

    if (is_array($validationResult)) {
        $user = [
            'id' => $_SESSION['id'],
        ];
    
        $expenseData = [
            'category_id' => $validationResult['category'],
            'description' => $validationResult['description'],
            'amount' => $validationResult['amount'],
            'date' => $validationResult['date'],
            'receipt_img' => null,
            'note' => $validationResult['note'],
            'user_id' => $user['id'],
        ];
    
        $expenseData['payed'] = isset($validationResult['payed']) ? ($validationResult['payed'] ? 1 : 0) : 0;
    
        $expenseData['payment_id'] = $expenseData['payed'] ? $validationResult['method'] : getMethodByDescription('None')['id'];
    
        $result = createExpense($expenseData);
    
        if ($result) {
            $_SESSION['success'] = 'Expense created successfully.';
        } else {
            error_log("Error creating expense: " . implode(" - ", $result->errorInfo()));
        }
    
        $params = '?' . http_build_query($postData);
        header('location: /php-project/pages/secure/expense.php' . $params);
    }
}

function edelete($expenseId)
{
    if (!isset($_SESSION['id'])) {
        $_SESSION['errors'][] = 'User ID not set in the session.';
        header('location: /php-project/pages/secure/expense.php');
        exit();
    }

    $deleteSuccess = softDeleteExpense($expenseId);

    if ($deleteSuccess) {
        $_SESSION['success'] = 'Expense deleted successfully.';
    } else {
        $_SESSION['errors'][] = 'Error deleting expense.';
        error_log("Error deleting expense with ID $expenseId: " . implode(" - ", $GLOBALS['pdo']->errorInfo()));
    }

    header('location: /php-project/pages/secure/expense.php');
    exit();
}

function eedit($expenseId, $postData)
{   
    if (!isset($_SESSION['id'])) {
        $_SESSION['errors'][] = 'User ID not set in the session.';
        header('location: /php-project/pages/secure/expense.php');
        exit();
    }

    $existingExpense = getExpensesById($expenseId); 

    // Combine existing data with the new data from the form
    $expenseData = [
        'category_id' => isset($postData['category_id']) ? $postData['category_id'] : $existingExpense['category_id'],
        'description' => isset($postData['description']) ? $postData['description'] : $existingExpense['description'],
        'payment_id' => isset($postData['payment_id']) ? $postData['payment_id'] : $existingExpense['payment_id'],
        'amount' => isset($postData['amount']) ? $postData['amount'] : $existingExpense['amount'],
        'date' => isset($postData['date']) ? $postData['date'] : $existingExpense['date'],
        'receipt_img' => isset($postData['receipt_img']) ? $postData['receipt_img'] : $existingExpense['receipt_img'],
        'payed' => isset($postData['payed']) ? $postData['payed'] : $existingExpense['payed'],
        'note' => isset($postData['note']) ? $postData['note'] : $existingExpense['note'],
        'user_id' => isset($postData['user_id']) ? $postData['user_id'] : $existingExpense['user_id'],
    ];

    $editSuccess = updateExpense(
        $expenseId,
        $expenseData['description'],
        $expenseData['category_id'],
        $expenseData['payment_id'],
        $expenseData['amount'],
        $expenseData['date'],
        $expenseData['receipt_img'],
        $expenseData['payed'],
        $expenseData['note'],
        $expenseData['user_id']
    );

    if ($editSuccess) {
        $_SESSION['success'] = 'Expense updated successfully.';
    } else {
        $_SESSION['errors'][] = 'Error updating expense.';
        error_log("Error updating expense with ID $expenseId: " . implode(" - ", $GLOBALS['pdo']->errorInfo()));
    }

    // Redirect or handle success/failure as needed
    header('location: /php-project/pages/secure/expense.php');
    exit();
    
}

function eshare($expenseId, $email) 
{
    try {
        $receiverUserId = getIdByEmail($email);

        if (!$receiverUserId) {
            $_SESSION['errors'][] = 'User with the email "' . $email . '" not found.';
            header('location: /php-project/pages/secure/expense.php');
            exit();
        }

        $sharerUserId = $_SESSION['id'];

        $shareSuccess = shareExpense($expenseId, $sharerUserId, $receiverUserId);

        if ($shareSuccess) {
            $_SESSION['success'] = 'Expense shared successfully.';
        } else {
            $_SESSION['errors'][] = 'Error sharing expense.';
            error_log("Error sharing expense with ID $expenseId: " . implode(" - ", $GLOBALS['pdo']->errorInfo()));
        }

        header('location: /php-project/pages/secure/expense.php');
        exit();
    } catch (PDOException $e) {
        $_SESSION['errors'][] = 'Error: ' . $e->getMessage();
        header('location: /php-project/pages/secure/expense.php');
        exit();
    }
}

function shareExpense($expenseId, $sharerUserId, $receiverUserId) 
{
    try {
        $isAlreadyShared = isExpenseShared($expenseId, $sharerUserId, $receiverUserId);

        if ($isAlreadyShared) {
            $_SESSION['errors'][] = 'Expense is already shared with the specified user.';
            return false;
        }

        $sharedExpense = [
            'receiver_user_id' => $receiverUserId,
            'sharer_user_id' => $sharerUserId,
            'expense_id' => $expenseId,
        ];

        return createSharedExpense($sharedExpense);
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

?>