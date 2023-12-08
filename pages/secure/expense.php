<?php
    require_once __DIR__ . '../../../middlewares/middleware-user.php';
    @require_once __DIR__ . '/../../validations/session.php';
    $user = user();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./fontawesome-free-6.2.1-web/css/all.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css"
        integrity="sha512-z3gLpd7yknf1YoNbCzqRKc4qyor8gaKU1qmn+CShxbuBusANI9QpRohGBreCFkKxLhei6S9CQXFEbbKuqLg0DA=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../resources/styles/sidebar.css">
</head>
<body>
    <div class="container-fluid">
        <div class="row flex-nowrap">
            <div class="bg-dark col-auto min-vh-100 d-flex flex-column">
                <div class="bg-dark p-2">
                    <a class="d-flex text-decoration-none mt-1 align-items-center text-white">
                        <span class="fs-4 d-sm-inline">
                            <a class="d-flex justify-content-center" href="./dashboard.php"><img class="fs-4 d-none d-sm-inline" style="max-width: 125px" src="../resources/assets/logo.png" alt="logo"></a>
                        </span>
                    </a>
                    <ul class="nav nav-pills flex-column mt-4">
                        <li class="nav-item py-3">
                            <a href="./dashboard.php" class="nav-link text-white">
                                <i class="fa-solid fa-house" title="Dashboard"></i>
                                <span class="fs-6 ms-3 d-none d-sm-inline">Dashboard</span>
                            </a>
                        </li>
                        <li class="nav-item py-3">
                            <a href="#" class="nav-link text-white">
                                <i class="fa-solid fa-table-list" title="Expenses"></i>
                                <span class="fs-6 ms-3 d-none d-sm-inline">Expenses</span>
                            </a>
                        </li>
                        <li class="nav-item py-3 mask">
                            <a href="#" class="nav-link text-white">
                                <i class="fa-solid fa-money-check-dollar" title="Payments"></i>
                                <span class="fs-6 ms-3 d-none d-sm-inline">Payments</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <div class="bg-dark mb-3 mt-auto d-flex justify-content-center">
                    <a class="btn btn-danger" href="./profile.php">
                        <i class="fa-solid fa-user"></i>
                        <h6 class="fw-bold ms-3  d-none d-sm-inline">User: <?= $user['first_name'] ?? null ?>!</h6>
                    </a>
                </div>
            </div>
        </div>
    </div>

</body>
</html>

