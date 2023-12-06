<?php
    require_once __DIR__ . '../../../middlewares/middleware-user.php';
    @require_once __DIR__ . '/../../validations/session.php';
    include_once __DIR__ . '/../templates/header.php';
    $user = user();
?>

<main>

<?php
if (isAuthenticated()) {
    $adminButtons = $user['admin'] ? '<a class="h6 d-block mx-3 my-2 p-3 text-decoration-none dropdown-toggle" role="button" id="expensesDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="color: black;">Admin</a>
    
    <ul class="dropdown-menu" aria-labelledby="expensesDropdown">
        <li><a class="dropdown-item my-2" href="/php-project/pages/secure/admin/">Manage Users</a></li>
        <li><a class="dropdown-item my-2" href="#">Manage Expenses</a></li>
        <li><a class="dropdown-item my-2" href="#">Manage Payments</a></li>
    </ul>
    ' : '';
    $customButtons = '

        <a class="h6 d-block mx-3 my-2 p-3 text-decoration-none dropdown-toggle" href="#" role="button" id="expensesDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="color: black;"> Expenses </a>

        <ul class="dropdown-menu" aria-labelledby="expensesDropdown">
            <li><a class="dropdown-item my-2" href="#">Manage Expenses</a></li>
            <li><a class="dropdown-item my-2" href="#">List Expenses</a></li>
            <li><a class="dropdown-item my-2" href="#">Share Expenses</a></li>
        </ul>

        <a class="h6 d-block mx-3 my-2 p-3 text-decoration-none" style="color: black;" href="#" onclick="untoggleMenu()">Payment Methods</a>
        <a class="h6 d-block mx-3 my-2 p-3 text-decoration-none" style="color: black;" href="#" onclick="untoggleMenu()">Expense Categories</a>

    ' . $adminButtons;
}

include_once __DIR__ . '/../templates/sidebar.php'; 
?>
    OLA
    <div class="row align-items-md-stretch">
        <div class="col-md-6">
            <div class="h-100 p-5 text-bg-dark rounded-3">
                <h2>Profile</h2>
                <a href="/php-project/pages/secure/user/profile.php"><button class="btn px-5"
                        type="button">Change</button></a>
            </div>
            <div class="h-100 p-5 text-bg-dark rounded-3">
                <a href="../public/dashboard.php"><button class="btn"
                        type="button">Go Back</button></a>
            </div>
        </div>
    </div>
    <form class="d-flex justify-content-center" action="../../controllers/auth/signin.php" method="post">                
        <button class="btn w-50 btn-danger btn-lg" type="submit" name="user" value="logout">Logout</button>
    </form>
</main>