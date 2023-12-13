<?php
require_once __DIR__ . '../../../middlewares/middleware-user.php';
require_once __DIR__ . '/../../repositories/expense.php';
require_once __DIR__ . '/../../controllers/expenses/expense.php';
@require_once __DIR__ . '/../../validations/session.php';
$user = user();
?>

<?php include __DIR__ . '/sidebar.php'; ?>

<style>
    .style {
        background-color: white;
        border-color: darkviolet;
        transition: transform 0.5s ease;
    }

    .style:hover {
        transform: scale(1.05);
    }
</style>

<div class="p-4 overflow-auto h-100">
    <nav style="--bs-breadcrumb-divider:'>';font-size:14px">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><i class="fa-solid fa-house"></i></li>
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item">Expenses</li>
            <li class="breadcrumb-item">My Expenses</li>
        </ol>
    </nav>
    
    <button class="btn btn-blueviolet my-2" data-bs-toggle="modal" data-bs-target="#add-expense">
        Add Expense
    </button>
    
    <section class="py-4 px-5">
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
    
    <div class="row row-cols-1 row-cols-md-3 g-3">
        <?php $expenses = getAllExpensesByUserId($user['id']); ?>
            <?php foreach ($expenses as $expense) : ?>
            <div class="col">
                <div class="card style" id="expense-card-<?php echo $expense['expense_id']; ?>">
                    <div class="row">
                        <div class="col m-2">
                            <h5 class="card-title"><?php echo $expense['description']; ?></h5>
                        </div>
                        <div class="col">
                            <div class="justify-content-end align-items-center mt-2 mx-2"> 
                                <form action="../../controllers/expenses/expense.php" method="post" class="float-end" onsubmit="return confirmDelete(event)">
                                    <input type="hidden" name="expense_id" value="<?php echo $expense['expense_id']; ?>">
                                    <button type="submit" name="user" value="delete" class='btn btn-danger btn-sm'><i class="fas fa-trash-alt"></i></button>
                                </form>
                                <button type="button" class='btn btn-blueviolet btn-sm float-end mx-1' onclick="prepareShareModal(<?php echo $expense['expense_id']; ?>)"><i class="fas fa-share"></i></button>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col justify-content-center">
                                <p class="card-text"><strong>Category:</strong> <?php echo $expense['category_description']; ?></p>
                                <?php if ($expense['payed'] == 1) : ?>
                                    <p class="card-text"><strong>Payment Method:</strong> <?php echo $expense['payment_description']; ?></p>
                                <?php endif; ?>
                                <p class="card-text"><strong>Amount:</strong> <?php echo $expense['amount']; ?></p>
                                <p class="card-text"><strong>Payed:</strong> <?php echo ($expense['payed'] == 1) ? 'Yes' : 'No'; ?></p>
                                <p class="card-text"><strong>Date:</strong> <?php echo $expense['date']; ?></p>
                            </div>
                            <div class="col" style="<?php echo empty($expense['receipt_img']) ? 'display: none;' : ''; ?>">
                                <?php if (!empty($expense['receipt_img'])): ?>
                                    <?php
                                        $receipt_Data = base64_decode($expense['receipt_img']);
                                        $receipt_Src = 'data:image/jpeg;base64,' . base64_encode($receipt_Data);
                                    ?>
                                    <img src="<?= $receipt_Src ?>" alt="receipt_img" class="d-block ui-w-80 mx-auto rounded" width="150px">
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- MODAL ADD -->
    <div class="modal fade" id="add-expense" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title"> Add an expense </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <form action="../../controllers/expenses/expense.php" method="post" enctype="multipart/form-data">
                        <div class="form-group mt-3">
                            <label>Description</label>
                            <input type="text" class="form-control" id="description" name="description" placeholder="Expense Description" value="<?= isset($_REQUEST['description']) ? $_REQUEST['description'] : '' ?>" required>
                        </div>
                        <div class="form-group mt-3">
                            <label>Category</label>
                            <select class="form-control" id="category" name="category">
                                <?php
                                    $categories = getAllCategories();
                                    foreach ($categories as $category) {
                                        $selected = isset($_REQUEST['category']) && $_REQUEST['category'] == $category['id'] ? 'selected' : '';
                                        echo "<option value='{$category['id']}' $selected>{$category['description']}</option>";
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="form-group mt-3">
                            <label>Date</label>
                            <input type="date" class="form-control" id="date" name="date" value="<?= isset($_REQUEST['date']) ? $_REQUEST['date'] : '' ?>" required>
                        </div>
                        <div class="form-group mt-3">
                            <label>Amount</label>
                            <input type="text" class="form-control" id="amount" name="amount" placeholder="Expense Amount" value="<?= isset($_REQUEST['amount']) ? $_REQUEST['amount'] : '' ?>" required>
                        </div>
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" name="payed" id="payed" <?= isset($_REQUEST['payed']) && $_REQUEST['payed'] == 'on' ? 'checked' : '' ?>>
                            <label class="form-check-label">Paid?</label>
                        </div>
                        <div class="form-group mt-3" id="paymentBox">
                            <label>Payment Method</label>
                            <select class="form-control" id="method" name="method">
                                <?php
                                    $methods = getAllMethods();
                                    foreach ($methods as $method) {
                                        $selectedMethod = isset($_REQUEST['method']) && $_REQUEST['method'] == $method['id'] ? 'selected' : '';
                                        echo "<option value='{$method['id']}' $selectedMethod>{$method['description']}</option>";
                                    }
                                ?>
                            </select>
                        </div>

                        <div class="form-group mt-3">
                            <label>Receipt Image</label>
                            <input type="file" class="form-control" id="receipt_img" name="receipt_img">
                        </div>

                        <div class="form-group mt-3">
                            <label>Note</label>
                            <textarea class="form-control" id="note" name="note" placeholder="Expense Note"><?= isset($_REQUEST['note']) ? $_REQUEST['note'] : '' ?></textarea>
                        </div>
                        <button type="submit" class="btn btn-blueviolet mt-3" name="user" value="add">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL EDIT -->
    <div class="modal fade" id="edit-expense-modal" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title"> Edit Expense </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <form id="edit-expense-form" action="../../controllers/expenses/expense.php" method="post">
                        <input type="hidden" name="expense_id" id="expense_id" value="<?php echo $existingExpense['expense_id']; ?>">
                        <!-- Description -->
                        <div class="form-group mt-3">
                            <label>Description</label>
                            <input type="text" class="form-control" id="description" name="description" placeholder="Expense Description" value="<?= isset($existingExpense['description']) ? $existingExpense['description'] : '' ?>" required>
                        </div>
                        <!-- Category -->
                        <div class="form-group mt-3">
                            <label>Category</label>
                            <select class="form-control" id="category" name="category">
                                <?php
                                $categories = getAllCategories();
                                foreach ($categories as $category) {
                                    $selected = isset($existingExpense['category_id']) && $existingExpense['category_id'] == $category['id'] ? 'selected' : '';
                                    echo "<option value='{$category['id']}' $selected>{$category['description']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <!-- Date -->
                        <div class="form-group mt-3">
                            <label>Date</label>
                            <input type="date" class="form-control" id="date" name="date" value="<?= isset($existingExpense['date']) ? $existingExpense['date'] : '' ?>" required>
                        </div>
                        <!-- Amount -->
                        <div class="form-group mt-3">
                            <label>Amount</label>
                            <input type="text" class="form-control" id="amount" name="amount" placeholder="Expense Amount" value="<?= isset($existingExpense['amount']) ? $existingExpense['amount'] : '' ?>" required>
                        </div>
                        <!-- Paid Checkbox -->
                        <div class="form-check mt-3">
                            <input class="form-check-input" type="checkbox" name="payed" id="payed" <?= isset($existingExpense['payed']) && $existingExpense['payed'] == 1 ? 'checked' : '' ?>>
                            <label class="form-check-label">Paid?</label>
                        </div>
                        <!-- Payment Method -->
                        <div class="form-group mt-3" id="paymentBox">
                            <label>Payment Method</label>
                            <select class="form-control" id="method" name="method">
                                <?php
                                $methods = getAllMethods();
                                foreach ($methods as $method) {
                                    $selectedMethod = isset($existingExpense['payment_id']) && $existingExpense['payment_id'] == $method['id'] ? 'selected' : '';
                                    echo "<option value='{$method['id']}' $selectedMethod>{$method['description']}</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <!-- Receipt Image -->
                        <div class="form-group mt-3">
                            <label>Receipt Image</label>
                            <input type="file" class="form-control" id="receipt_img" name="receipt_img">
                        </div>
                        <!-- Note -->
                        <div class="form-group mt-3">
                            <label>Note</label>
                            <textarea class="form-control" id="note" name="note" placeholder="Expense Note"><?= isset($existingExpense['note']) ? $existingExpense['note'] : '' ?></textarea>
                        </div>
                        <!-- Update Button -->
                        <button type="submit" class="btn btn-blueviolet mt-3" name="user" value="edit">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- MODAL SHARE -->
    <div class="modal fade" id="shareExpenseModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Share Expense</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="../../controllers/expenses/expense.php" method="post">
                        <input type="hidden" name="expense_id" id="modalExpenseId" value="">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email of the User to Share With:</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <button type="submit" name="user" value="share" class="btn btn-primary">Share</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

</div>

<script>
    function prepareShareModal(expenseId) {
        console.log("Expense ID:", expenseId);

        document.getElementById('modalExpenseId').value = expenseId;

        var myModal = new bootstrap.Modal(document.getElementById('shareExpenseModal'));
        myModal.show();
    }

    function confirmDelete(event) {
        console.log("confirmDelete called");
        event.stopPropagation();
        return confirm("Are you sure you want to delete this expense?");
    }

    document.addEventListener('DOMContentLoaded', function() {
        const payedCheckbox = document.getElementById('payed');
        const paymentBox = document.getElementById('paymentBox');

        paymentBox.style.display = payedCheckbox.checked ? 'block' : 'none';

        payedCheckbox.addEventListener('change', function () {
            paymentBox.style.display = this.checked ? 'block' : 'none';
        });
    });

</script>