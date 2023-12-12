<?php
require_once __DIR__ . '/../../repositories/expense.php';
@require_once __DIR__ . '/../../validations/session.php';
@require_once __DIR__ . '/../../validations/expenses/validate-expense.php';

if (isset($_POST['user']) && $_POST['user'] == 'add') {
    eadd($_POST);
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

    // Check if validation result is an array (indicating validation success)
    if (is_array($validationResult)) {
        $user = [
            'id' => $_SESSION['id'],
        ];

        $expenseData = [
            'category_id' => $validationResult['category'],
            'description' => $validationResult['description'],
            'payment_id' => $validationResult['method'],
            'amount' => $validationResult['amount'],
            'date' => $validationResult['date'],
            'receipt_img' => null,
            'payed' => isset($validationResult['payed']) ? 1 : 0,
            'note' => $validationResult['note'],
            'user_id' => $user['id'],
        ];

        $result = createExpense($expenseData);

        if ($result) {
            $_SESSION['success'] = 'Expense created successfully.';
        }

        $params = '?' . http_build_query($postData);
        header('location: /php-project/pages/secure/expense.php' . $params);
    }
}

?>
