<?php
    require_once __DIR__ . '../../../middlewares/middleware-user.php';
    @require_once __DIR__ . '/../../validations/session.php';
    include_once __DIR__ . '/../templates/header.php';
    $user = user();
?>

<main>

<?php
    if (isAuthenticated()) {
        $admBTN = $user['admin'] ? '<a class="h6 d-block mx-3 my-2 p-3 text-decoration-none dropdown-toggle" role="button" id="expensesDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="color: black;">Admin</a>
        
        <ul class="dropdown-menu" aria-labelledby="expensesDropdown">
            <li><a class="dropdown-item my-2" href="/php-project/pages/secure/admin/">Manage Users</a></li>
            <li><a class="dropdown-item my-2" href="#">Manage Expenses</a></li>
            <li><a class="dropdown-item my-2" href="#">Manage Payments</a></li>
        </ul>
        ' : '';
        $sideBTN = '

            <a class="h6 d-block mx-3 my-2 p-3 text-decoration-none dropdown-toggle" href="#" role="button" id="expensesDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="color: black;"> Expenses </a>

            <ul class="dropdown-menu" aria-labelledby="expensesDropdown">
                <li><a class="dropdown-item my-2" href="#">Manage Expenses</a></li>
                <li><a class="dropdown-item my-2" href="#">List Expenses</a></li>
                <li><a class="dropdown-item my-2" href="#">Share Expenses</a></li>
            </ul>

            <a class="h6 d-block mx-3 my-2 p-3 text-decoration-none" style="color: black;" href="#" onclick="untoggleMenu()">Payment Methods</a>
            <a class="h6 d-block mx-3 my-2 p-3 text-decoration-none" style="color: black;" href="#" onclick="untoggleMenu()">Expense Categories</a>

        ' . $admBTN;
    }
    include_once __DIR__ . '/../templates/sidebar.php'; 
?>
    <div class="row align-items-md-stretch">
        <div class="mt-5 col-md-6">
            <div class="h-100 p-5 text-bg-dark rounded-3">
                <a href="/php-project/pages/secure/user/profile.php"><button class="btn px-3 mx-3"
                        type="button">Change</button></a>
                <a href="../public/dashboard.php"><button class="btn"
                        type="button">Go Back</button></a>
            </div>
            <form class="mx-5 d-flex justify-content-start" action="../../controllers/auth/signin.php" method="post">                
                <button class="btn w-10 btn-danger btn-lg" type="submit" name="user" value="logout">Logout</button>
            </form>
        </div>
    </div>
</main>