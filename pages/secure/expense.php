    <?php
    require_once __DIR__ . '../../../middlewares/middleware-user.php';
    require_once __DIR__ . '/../../repositories/expense.php';
    @require_once __DIR__ . '/../../validations/session.php';
    $user = user();
    ?>

    <?php include __DIR__ . '/sidebar.php'; ?>

        <div class="p-4">
            <nav style="--bs-breadcrumb-divider:'>';font-size:14px">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><i class="fa-solid fa-house"></i></li>
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Expenses</li>
                </ol>
            </nav>
            
            <button class="btn btn-blueviolet my-2" data-bs-toggle="modal" data-bs-target="#reg-modal">
                Add Expense
            </button>
            
            <div class="row row-cols-1 row-cols-md-3 g-2">
                <?php 
                $expenses = getAllExpensesById($user['id']); 
                ?>
                <?php foreach ($expenses as $expense) : ?>
                    <div class="col">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $expense['description']; ?></h5>
                                <p class="card-text"><strong>Category:</strong> <?php echo $expense['category_description']; ?></p>
                                <p class="card-text"><strong>Payment Method:</strong> <?php echo $expense['payment_description']; ?></p>
                                <p class="card-text"><strong>Amount:</strong> <?php echo $expense['amount']; ?></p>
                                <p class="card-text"><strong>Payed:</strong> <?php echo ($expense['payed'] == 1) ? 'Yes' : 'No'; ?></p>
                                <p class="card-text"><strong>Date:</strong> <?php echo $expense['date']; ?></p>
                                <button class='btn btn-blueviolet-reverse px-1'><a>Update</a></button>
                                <button class='btn btn-danger px-2 my-2'><a>Delete</a></button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- MODAL HEHEHE -->
            <div class="modal fade" id="reg-modal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" style="max-width: 500px;">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title" id="modal-title">Add an expense</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body" style="max-height: 400px; overflow-y: auto;">
                            AQUI VAI FICAR O FORM
                        </div>
                    </div>
                </div>
            </div>
        </div>