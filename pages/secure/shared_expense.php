<?php
require_once __DIR__ . '../../../middlewares/middleware-user.php';
require_once __DIR__ . '/../../repositories/expense.php';
require_once __DIR__ . '/../../repositories/shared-expense.php';
@require_once __DIR__ . '/../../validations/session.php';
$user = user();

$filterSharerName = isset($_POST['$filterSharerName']) ? $_POST['$filterSharerName'] : '';

    if (!empty($filterSharerName)) {
        $expenses = getSharedExpensesBySharer($filterSharerName);
    } else {
        $expenses = getAllSharedExpensesById($user['id']);
    }
?>

<?php include __DIR__ . '/sidebar.php'; ?>

<link rel="stylesheet" href="../resources/styles/card.css">

<div class="p-4 overflow-auto h-100">
    <nav style="--bs-breadcrumb-divider:'>';font-size:14px">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><i class="fa-solid fa-house"></i></li>
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item">Expenses</li>
            <li class="breadcrumb-item">Shared</li>
        </ol>
    </nav>

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

    <div class="col-12 col-md-12 my-2">
        <form id="searchForm" class="d-flex" method="post" action="">
            <div class="form-group me-2 flex-grow-1">
                <input type="text" class="form-control" id="$filterSharerName" name="$filterSharerName"
                    placeholder="Search by sharer name" value="<?php echo $filterSharerName; ?>">
            </div>
        </form>
    </div>

    <div class="row row-cols-1 row-cols-md-3 g-3">
        <div class="d-flex justify-content-center w-100">
            <?php if (empty($expenses)) : ?>
            <strong>
                <p class="mt-3" style="color: red">No shared expenses found.</p>
            </strong>
            <?php endif; ?>
        </div>
        <?php foreach ($expenses as $expense) : ?>
        <div class="col">
            <div class="card style" id="expense-card-<?php echo $expense['expense_id']; ?>">
                <div class="row">
                    <div class="col m-2">
                        <h5 class="card-title"><?php echo $expense['description']; ?></h5>
                    </div>
                    <div class="col">
                        <div class="justify-content-end align-items-center mt-2 mx-2">
                            <button type="button" class='btn btn-danger btn-sm float-end m-1' data-bs-toggle="modal"
                                data-bs-target="#delete-expense<?= $expense['expense_id']; ?>"><i
                                    class="fas fa-trash-alt"></i></button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col justify-content-center">
                            <p class="card-text"><strong>Category:</strong>
                                <?php echo $expense['category_description']; ?></p>
                            <?php if ($expense['payed'] == 1) : ?>
                            <p class="card-text"><strong>Payment Method:</strong>
                                <?php echo $expense['payment_description']; ?></p>
                            <?php endif; ?>
                            <p class="card-text"><strong>Amount:</strong> <?php echo $expense['amount']; ?></p>
                            <p class="card-text"><strong>Payed:</strong>
                                <?php echo ($expense['payed'] == 1) ? 'Yes' : 'No'; ?></p>
                            <p class="card-text"><strong>Date:</strong> <?php echo $expense['date']; ?></p>
                        </div>
                        <div class="my-3" style="<?php echo empty($expense['receipt_img']) ? 'display: none;' : ''; ?>">
                            <?php if (!empty($expense['receipt_img'])): ?>
                            <?php
                                        $receipt_Data = base64_decode($expense['receipt_img']);
                                        $receipt_Src = 'data:image/jpeg;base64,' . base64_encode($receipt_Data);
                                    ?>
                            <div class="h-auto w-100">
                                <img src="<?= $receipt_Src ?>" alt="receipt_img"
                                    class="object-fit-cover w-100 img-fluid d-block ui-w-80 mx-auto rounded"
                                    style="max-width: 150px;">
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <p class="card-text mt-1" style="color: blueviolet"><strong>Expense shared by:</strong>
                        <?php echo $expense['sharer_first_name'] . ' ' . $expense['sharer_last_name']; ?></p>
                </div>
            </div>
        </div>

        <!-- MODAL DELETE -->
        <div class="modal fade" id="delete-expense<?= $expense['expense_id']; ?>" tabindex="-1"
            aria-labelledby="delete-expense<?= $expense['expense_id']; ?>" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Delete Shared Expense</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form action="../../controllers/expenses/expense.php" method="post">
                            <input type="hidden" name="expense_id" id="expense_id"
                                value="<?php echo $expense['expense_id']; ?>">
                            <div class="mb-3">
                                Do you want to proceed removing the shared expense?
                            </div>
                            <button type="submit" name="user" value="remove-shared"
                                class="btn btn-danger">Remove</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php endforeach; ?>
    </div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        var form = document.getElementById("searchForm");
        var inputElement = document.getElementById("$filterSharerName");

        setupDebouncer(inputElement, form);
    });
</script>