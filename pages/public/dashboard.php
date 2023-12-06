<?php
    require_once __DIR__ . '../../../middlewares/middleware-user.php';
    @require_once __DIR__ . '../../../validations/session.php';
    include_once __DIR__ . '/../templates/header.php';
    $user = user();
    $title = 'Dashboard';
?>

<?php 
    $customButtons = '

        <a class="h6 d-block mx-3 my-2 p-3 text-decoration-none dropdown-toggle" href="#" role="button" id="expensesDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="color: black;"> Expenses </a>

        <ul class="dropdown-menu" aria-labelledby="expensesDropdown">
            <li><a class="dropdown-item my-2" href="#">Manage Expenses</a></li>
            <li><a class="dropdown-item my-2" href="#">List Expenses</a></li>
            <li><a class="dropdown-item my-2" href="#">Share Expenses</a></li>
        </ul>

        <a class="h6 d-block mx-3 my-2 p-3 text-decoration-none" style="color: black;" href="#" onclick="untoggleMenu()">Payment Methods</a>
        <a class="h6 d-block mx-3 my-2 p-3 text-decoration-none" style="color: black;" href="#" onclick="untoggleMenu()">Expense Categories</a>
    ';

include_once __DIR__ . '/../templates/sidebar.php'; 
?>

<script> document.title = "EFlow - <?= $title ?? '' ?>"; </script>