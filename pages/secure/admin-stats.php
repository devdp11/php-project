<?php
require_once __DIR__ . '../../../middlewares/middleware-user.php';
@require_once __DIR__ . '/../../validations/session.php';
@require_once __DIR__ . '/../../repositories/user.php';
@require_once __DIR__ . '/../../repositories/expense.php';
$user = user();


$deletedCount = getDeletedUsersCount();
$activeCount = getActiveUsersCount();
$usersWithExpensesCount = getUsersWithExpensesCount();
$usersWithSharedExpensesCount = getUsersWithSharedExpensesCount();
$usersByCountryCount = getUsersByCountryCount();

/* $mostUsedCategory = getMostUsedExpenseCategory(); */
?>

<?php include __DIR__ . '/sidebar.php'; ?>

<div class="p-4 overflow-auto h-100">
    <nav style="--bs-breadcrumb-divider:'>';font-size:14px">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><i class="fa-solid fa-house"></i></li>
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item">Admin</li>
            <li class="breadcrumb-item">Dashboard</li>
        </ol>
    </nav>

    <div class="row">
        <div class="col-md-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Deleted Users</h5>
                    <p class="card-text"><?php echo $deletedCount; ?></p>
                </div>
            </div>
        </div>

        <div class="col-md-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Active Users</h5>
                    <p class="card-text"><?php echo $activeCount; ?></p>
                </div>
            </div>
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
                            <li class="list-group-item"><?php echo $countryCount['country'] . ': ' . $countryCount['user_count']; ?></li>
                        <?php endforeach; ?>
                    </ul>
                </div>
            </div>
        </div>

        <!-- <div class="col-md-6 col-lg-6 mb-4">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Most used category</h5>
                    <?php
                    if ($mostUsedCategory) {
                        echo '<p class="card-text">The most used category is: ' . $mostUsedCategory['category_description'] . ' with ' . $mostUsedCategory['category_count'] . ' expenses.</p>';
                    } else {
                        echo '<p class="card-text">Nenhuma despesa encontrada.</p>';
                    }
                    ?>
                </div>
            </div>
        </div> -->
    </div>
</div>