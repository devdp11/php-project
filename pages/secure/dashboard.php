<?php
    require_once __DIR__ . '../../../middlewares/middleware-user.php';
    @require_once __DIR__ . '/../../validations/session.php';
    @require_once __DIR__ . '/../../repositories/expense.php';
    $user = user();
    
    $expensesCount = getExpensesCountById($user['id']);
    $sharedExpensesCountByMe = getSharedExpensesCountBySharerId($user['id']);
    $sharedExpensesCountToMe = getSharedExpensesCountByReceiverId($user['id']);
    $sumExpensesAmount = getAmountExpensesById($user['id']);
    $sumSharedExpensesAmount = getAmountSharedExpensesById($user['id']);
    $futureExpenses = getFutureExpenses($user['id']);
?>


<?php include __DIR__ . '/sidebar.php'; ?>

    <div class="p-4 overflow-auto h-100">
        <nav style="--bs-breadcrumb-divider:'>';font-size:14px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><i class="fa-solid fa-house"></i></li>
                <li class="breadcrumb-item">Dashboard</li>
            </ol>
        </nav>

        <div class="row row-cols-1 row-cols-md-3 g-2">
            <div class="col">
                <div class="card bg-warning">
                    <div class="card-body">
                        <h6 class="card-title">Number of your expenses</h6>
                        <p class="card-text text-muted mt-3">Number of Expenses: <?php echo $expensesCount; ?></p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card bg-info text-white">
                    <div class="card-body">
                        <h6 class="card-title">Number of your expenses you shared</h6>
                        <p class="card-text mt-3">Number of Expenses: <?php echo $sharedExpensesCountByMe; ?></p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h6 class="card-title">Number of your expenses shared with you</h6>
                        <p class="card-text mt-3">Number of Expenses: <?php echo $sharedExpensesCountToMe; ?></p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h6 class="card-title">Amount of your expenses</h6>
                        <?php
                        $displayAmount = isset($sumExpensesAmount) && $sumExpensesAmount !== '' ? $sumExpensesAmount : '0';
                        ?>
                        <p class="card-text mt-3">Expense's Amount: <?php echo $displayAmount; ?>€</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <h6 class="card-title">Amount of shared expenses</h6>
                        <p class="card-text mt-3">Expense's Amount: <?php echo $sumSharedExpensesAmount; ?>€</p>
                    </div>
                </div>
            </div>
            <div class="col">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <h6 class="card-title">Future Expenses</h6>
                        <p class="my-2" data-bs-toggle="modal" data-bs-target="#expenseModal">Check your future expenses</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="expenseModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Future Expenses</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <h5 class="modal-subtitle" style="color:blueviolet" id="exampleModalLabel">Expenses Details</h5>
                        <?php foreach ($futureExpenses as $futureExpense): ?>
                            <div class="row">
                                <div class="col-lg-6">
                                    <p>
                                        <strong>Category:</strong> <?php echo $futureExpense['category_description']; ?>
                                        <strong>Description:</strong> <?php echo $futureExpense['description']; ?><br>
                                        <strong>Amount:</strong> <?php echo $futureExpense['amount']; ?>€<br>
                                        <strong>Date:</strong> <?php echo $futureExpense['date']; ?>
                                    </p>
                                </div>
                                <div class="col-lg-6" style="<?php echo empty($futureExpense['receipt_img']) ? 'display: none;' : ''; ?>">
                                    <?php if (!empty($futureExpense['receipt_img'])): ?>
                                        <?php
                                            $receipt_Data = base64_decode($futureExpense['receipt_img']);
                                            $receipt_Src = 'data:image/jpeg;base64,' . base64_encode($receipt_Data);
                                        ?>
                                        <div class="h-auto w-100">
                                            <img src="<?= $receipt_Src ?>" alt="receipt_img" class="object-fit-cover w-100 img-fluid d-block ui-w-80 mx-auto rounded" style="max-width: 150px;">
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <hr>
                        <?php endforeach; ?>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>

        

        