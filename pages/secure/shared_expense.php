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
            <li class="breadcrumb-item">Shared Expenses</li>
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
    
    <div class="row row-cols-1 row-cols-md-3 g-3">
        <?php $expenses = getAllSharedExpensesById($user['id']); ?>
        <?php foreach ($expenses as $expense) : ?>
            <div class="col">
                <div class="card style" id="expense-card-<?php echo $expense['expense_id']; ?>">
                    <div class="card-body">
                        <form action="../../controllers/expenses/expense.php" method="post" class="float-end" onsubmit="return confirmRemoval(event)">
                            <input type="hidden" name="expense_id" value="<?php echo $expense['expense_id']; ?>">
                            <button type="submit" name="user" value="remove-shared" class='btn btn-danger btn-sm'><i class="fas fa-trash-alt"></i></button>
                        </form>
                        <h5 class="card-title"><?php echo $expense['description']; ?></h5>
                        <p class="card-text"><strong>Category:</strong> <?php echo $expense['category_description']; ?></p>
                        <?php if ($expense['payed'] == 1) : ?>
                            <p class="card-text"><strong>Payment Method:</strong> <?php echo $expense['payment_description']; ?></p>
                        <?php endif; ?>
                        <p class="card-text"><strong>Amount:</strong> <?php echo $expense['amount']; ?></p>
                        <p class="card-text"><strong>Payed:</strong> <?php echo ($expense['payed'] == 1) ? 'Yes' : 'No'; ?></p>
                        <p class="card-text"><strong>Date:</strong> <?php echo $expense['date']; ?></p>
                        <p class="card-text"><strong>Expense shared by:</strong> <?php echo $expense['sharer_first_name'] . ' ' . $expense['sharer_last_name']; ?></p>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <script>
    function confirmRemoval(event) {
        console.log("confirmRemoval called");
        event.stopPropagation();
        return confirm("Are you sure you want to remove this shared expense?");
    }
</script>