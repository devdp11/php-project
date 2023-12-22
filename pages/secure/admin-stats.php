<?php
require_once __DIR__ . '../../../middlewares/middleware-user.php';
@require_once __DIR__ . '/../../validations/session.php';
@require_once __DIR__ . '/../../repositories/admin-dash.php';
$user = user();


$deletedCount = getDeletedUsersCount();
$activeCount = getActiveUsersCount();
$usersWithExpensesCount = getUsersWithExpensesCount();
$usersWithSharedExpensesCount = getUsersWithSharedExpensesCount();
$usersByCountryCount = getUsersByCountryCount();

$expensesCountByCategory = getExpensesCountByCategory();
$expensesCountByPayment = getExpensesCountByPayment();
$totalExpensesCount = getTotalExpensesCount();
$totalExpensesAmount = getTotalExpensesAmount();
?>

<?php include __DIR__ . '/sidebar.php'; ?>

<div class="p-4 overflow-auto h-100">
    <nav style="--bs-breadcrumb-divider:'>';font-size:14px">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><i class="fa-solid fa-house"></i></li>
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item">Admin</li>
        </ol>
    </nav>

    <section class="py-3 px-5">
        <?php
        if (isset($_SESSION['success'])) {
            echo '<div class="alert alert-success alert-dismissible fade show" role="alert">';
            echo $_SESSION['success'] . '<br>';
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button></div>';
            unset($_SESSION['success']);
        }
        if (isset($_SESSION['errors'])) {
            echo '<div class="alert alert-danger alert-dismissible fade show" role="alert">';
            foreach ($_SESSION['errors'] as $error) {
                echo $error . '<br>';
            }
            echo '<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>';
            unset($_SESSION['errors']);
        }
        ?>
    </section>

    <div class="row">
        <div class="col-md-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Add Payment Method</h5>
                    <form method="post" action="../../controllers/admin/admin.php">
                        <div class="mb-3">
                            <label for="paymentDescription" class="form-label">Description</label>
                            <input type="text" class="form-control" id="paymentDescription" name="paymentDescription" required>
                        </div>
                        <button type="submit" name="user" value="add-method" class="btn btn-blueviolet">Add Payment Method</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Add Category</h5>
                    <form method="post" action="../../controllers/admin/admin.php">
                        <div class="mb-3">
                            <label for="categoryDescription" class="form-label">Description</label>
                            <input type="text" class="form-control" id="categoryDescription" name="categoryDescription" required>
                        </div>
                        <button type="submit" name="user" value="add-category" class="btn btn-blueviolet">Add Category</button>
                    </form>
                </div>
            </div>
        </div>

        <hr class="w-100">

        <div class="col-md-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Deleted Users</h5>
                    <p class="card-text"><?php echo $deletedCount; ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 mb-4">
            <a class="text-decoration-none" href="./admin-users.php">
                <div class="card" style="cursor:pointer">
                    <div class="card-body">
                        <h5 class="card-title">Active Users</h5>
                        <p class="card-text"><?php echo $activeCount; ?></p>
                    </div>
                </div>
            </a>
            
        </div>

        <div class="col-md-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Users with expenses</h5>
                    <p class="card-text"><?php echo $usersWithExpensesCount; ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Users with shared expenses</h5>
                    <p class="card-text"><?php echo $usersWithSharedExpensesCount; ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Users by country</h5>
                    <ul class="list-group">
                        <?php foreach ($usersByCountryCount as $countryCount) : ?>
                            <?php
                            $country = ($countryCount['country'] !== null) ? $countryCount['country'] : 'No country detected';
                            ?>
                            <li class="list-group-item"><?php echo $country . ': ' . $countryCount['user_count']; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>
        
        <hr class="w-100">

        <div class="col-md-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Expenses Count by Category</h5>
                    <ul class="list-group">
                        <?php foreach ($expensesCountByCategory as $categoryCount) : ?>
                            <?php if ($categoryCount['expense_count'] > 0) : ?>
                                <li class="list-group-item"><?php echo $categoryCount['category'] . ': ' . $categoryCount['expense_count']; ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Expenses Count by Payment Method</h5>
                    <ul class="list-group">
                        <?php foreach ($expensesCountByPayment as $methodCount) : ?>
                            <?php if ($methodCount['expense_count'] > 0) : ?>
                                <li class="list-group-item"><?php echo $methodCount['payment'] . ': ' . $methodCount['expense_count']; ?></li>
                            <?php endif; ?>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Active Expenses Count</h5>
                    <p class="card-text"><?php echo $totalExpensesCount; ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Total Expenses Amount</h5>
                    <p class="card-text"><?php echo $totalExpensesAmount; ?></p>
                </div>
            </div>
        </div>
    </div>
</div>