<?php

require_once __DIR__ . '/../../repositories/admin-dash.php';
require_once __DIR__ . '/../../validations/session.php';

if (isset($_POST['user'])) {
    if ($_POST['user'] == 'add-method') {
        add_method($_POST);
    }

    if ($_POST['user'] == 'add-category') {
        add_category($_POST);
    }
}

function add_method($postData)
{
    $description = $postData['paymentDescription'];

    if (!paymentMethodExists($description)) {
        $paymentData = ['description' => $description];
        if (createPaymentMethod($paymentData)) {
            $_SESSION['success'] = "Payment method added successfully!";
        } else {
            $_SESSION['errors'][] = "Failed to add payment method!";
        }
    } else {
        $_SESSION['errors'][] = "Payment method already exists!";
    }

    header("Location: /php-project/pages/secure/admin-stats.php");
    exit();
}

function add_category($postData)
{
    $description = $postData['categoryDescription'];

    if (!categoryExists($description)) {
        $categoryData = ['description' => $description];
        if (createCategory($categoryData)) {
            $_SESSION['success'] = "Category added successfully!";
        } else {
            $_SESSION['errors'][] = "Failed to add category!";
        }
    } else {
        $_SESSION['errors'][] = "Category already exists!";
    }

    header("Location: /php-project/pages/secure/admin-stats.php");
    exit();
}
?>
