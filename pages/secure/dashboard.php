<?php
    require_once __DIR__ . '../../../middlewares/middleware-user.php';
    @require_once __DIR__ . '/../../validations/session.php';
    @require_once __DIR__ . '/../../repositories/expense.php';
    $user = user();

    $expensesCount = getExpensesCountById($user['id']);
    $paidExpensesCount = getPaidExpensesCountById($user['id']);
    $sharedExpensesCountByMe = getSharedExpensesCountBySharerId($user['id']);
    $sharedExpensesCountToMe = getSharedExpensesCountByReceiverId($user['id']);
    $sumExpensesAmount = getAmountExpensesById($user['id']);
    $sumSharedExpensesAmount = getAmountSharedExpensesById($user['id']);

    $futureExpenses = getFutureExpensesCountById($user['id']);
    $futureExpensesDetails = getFutureExpensesDetailsById($user['id']);
?>

<?php include __DIR__ . '/sidebar.php'; ?>

<style>
.style {
    background-color: white;
    background: linear-gradient(to right, #8a5bff, #e35bff);
    border-color: darkviolet;
    transition: box-shadow 0.3s ease;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    border-radius: 10px;
}

.style:hover {
    background-color: blueviolet;
    color: white;

}

.style:hover .card-title {
    color: white;
}
</style>

<div class="p-4 overflow-auto h-100">
    <nav style="--bs-breadcrumb-divider:'>';font-size:14px">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><i class="fa-solid fa-house"></i></li>
            <li class="breadcrumb-item">Dashboard</li>
        </ol>
    </nav>

    <div class="row row-cols-1 row-cols-md-3 g-2">
        <div class="col">
            <div class="card style">
                <div class="card-body">
                    <h6 class="card-title">Number of expenses: <?php echo $expensesCount; ?></h6>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card style">
                <div class="card-body">
                    <h6 class="card-title">Number of shared expenses: <?php echo $sharedExpensesCountByMe; ?></h6>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card style">
                <div class="card-body">
                    <h6 class="card-title">Number of expenses shared with you: <?php echo $sharedExpensesCountToMe; ?>
                    </h6>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card style">
                <div class="card-body">
                    <?php
            $displayAmount = isset($sumExpensesAmount) && $sumExpensesAmount !== '' ? $sumExpensesAmount : '0';
            ?>
                    <h6 class="card-title">Amount spent: <?php echo $displayAmount; ?>€</h6>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card style">
                <div class="card-body">
                    <h6 class="card-title">Amount of shared expenses: <?php echo $sumSharedExpensesAmount; ?>€</h6>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card style">
                <div class="card-body">
                    <h6 class="card-title">Number of paid expenses: <?php echo $paidExpensesCount; ?></h6>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="card style"
                <?php if ($futureExpenses > 0) echo 'data-bs-toggle="modal" data-bs-target="#futureDetails" style="cursor:pointer"'; ?>>
                <div class="card-body">
                    <?php if ($futureExpenses > 0): ?>
                    <p class="my-2">You have <?php echo $futureExpenses; ?> future expenses. Check them out!</p>
                    <?php else: ?>
                    <p class="my-2">No future expenses</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

    </div>

    <div class="modal fade" id="futureDetails" tabindex="-1" aria-labelledby="futureDetails" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="futureDetails">Future Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if (!empty($futureExpensesDetails)): ?>
                    <?php foreach ($futureExpensesDetails as $expense): ?>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title"><?php echo $expense['description']; ?></h5>
                            <p class="card-text">Amount: <?php echo $expense['amount']; ?>€</p>
                            <p class="card-text">Category: <?php echo $expense['category_description']; ?></p>
                            <p class="card-text">Date: <?php echo $expense['date']; ?></p>
                            <p class="card-text">Created At: <?php echo $expense['created_at']; ?></p>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php else: ?>
                    <p>No future expenses</p>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>