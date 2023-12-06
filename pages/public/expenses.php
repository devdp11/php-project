<?php
    require_once __DIR__ . '../../../middlewares/middleware-user.php';
    @require_once __DIR__ . '../../../validations/session.php';
    $user = user();
    $title = 'ExpenseS';

    $sideBTN = FALSE;
    include_once __DIR__ . '/../templates/header.php';
?>

<!-- ADD / EDIT / DELETE -->

<script> document.title = "EFlow - <?= $title ?? '' ?>"; </script>

