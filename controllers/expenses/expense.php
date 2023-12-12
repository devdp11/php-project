<?php
require_once __DIR__ . '/../../repositories/expense.php';
@require_once __DIR__ . '/../../validations/session.php';
@require_once __DIR__ . '/../../validations/expenses/validate-expense.php';

if (isset($_POST['user']) && $_POST['user'] == 'add') {
    eadd($_POST);
}

if (isset($_POST['user']) && $_POST['user'] == 'edit') {
    eedit($_POST);
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
?>