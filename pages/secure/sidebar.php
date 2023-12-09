<?php
    require_once __DIR__ . '../../../middlewares/middleware-user.php';
    @require_once __DIR__ . '/../../validations/session.php';
    $user = user();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KK94CHFLLe+nY2dmCWGMq91rCGa5gtU4mk92HdvYe+M/SXH301p5ILy+dN9+nJOZ" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@300;400;500&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ENjdO4Dr2bkBIFxQpeoTz1HIcje39Wm4jDKdf19U8gI4ddQ3GYNS7NTKfAdVQSZe" crossorigin="anonymous"></script>
</head>

<style>
    html,body{
    height: 100%;
    font-family: 'Ubuntu', sans-serif;
  }
  
  .mynav{
    color: #fff;
  }
  
  .mynav li a {
    color: #fff;
    text-decoration: none;
    width: 100%;
    display: block;
    border-radius: 5px;
    padding: 8px 5px;
  }
  
  .mynav li a:hover{
    background: rgba(255,255,255,0.2);
  }
  
  .mynav li a i{
    width: 25px;
    text-align: center;
  }
</style>

<body>
    <div class="container-fluid p-0 d-flex h-100">
        <div id="bdSidebar" class="d-flex flex-column flex-shrink-0 p-3 bg-black text-white offcanvas-md offcanvas-start" style="width: 280px;">
            <a href="#" class="navbar-brand">
                <h5 class="h6" style="color:blueviolet"><i class="fa-solid fa-wallet me-2" style="font-size: 18px;"></i>EXPENSE FLOW</h5>
            </a>
            <hr>
            <ul class="mynav nav nav-pills flex-column mb-auto">
                <li class="nav-item mb-3">
                    <a href="./dashboard.php" class="active">
                        <i class="fa-solid fa-home"></i>
                        DashBoard
                    </a>
                </li>
                <li class="nav-item mb-3">
                    <a href="./expense.php" class="">
                        <i class="fa-solid fa-chart-simple"></i>
                        Expenses
                    </a>
                </li>
                <li class="nav-item mb-3">
                    <a href="#" class="">
                        <i class="fa-solid fa-credit-card"></i>
                        Methods
                    </a>
                </li>
                <li class="nav-item mb-2">
                    <a href="./profile.php" class="">
                        <i class="fa-solid fa-user"></i>
                        Profile
                    </a>
                </li>
                <li class="nav-item py-1 mask <?= $user['admin'] ? '' : 'd-none'; ?>">
                    <a href="./display-users.php" class="nav-link text-white">
                        <i class="fa-solid fa-money-check-dollar" title="Users"></i>
                        <span class="fs-6 ms-1 d-none d-sm-inline">Users</span>
                    </a>
                </li>
            </ul>
            <hr>
            <div class="d-flex">
                <?php if (!empty($user['avatar'])): ?>
                    <?php
                        $avatarData = base64_decode($user['avatar']);
                        $avatarSrc = 'data:image/jpeg;base64,' . base64_encode($avatarData);
                    ?>
                    <img src="<?= $avatarSrc ?>" alt="avatar" class="img-fluid rounded-circle me-2" width="50px">
                <?php else: ?>
                    <img src="../resources/assets/logo.png" alt="default avatar" class="img-fluid rounded me-2" width="50px">
                <?php endif;?>

                <span>
                    <h6 class="mt-2 mb-0">Hello <?= $user['first_name'] ?? 'Guest' ?>!</h6>
                    <small><?= $user['email'] ?? null ?></small>
                </span>
            </div>
        </div>

        <div class="bg-light flex-fill">
            <div class="p-2 d-md-none d-flex text-white bg-black">
                <a href="#" class="text-white" data-bs-toggle="offcanvas" data-bs-target="#bdSidebar">
                    <i class="fa-solid fa-bars"></i>
                </a>
                <span class="ms-3" style="font-size: 16px; color: blueviolet">EXPENSE FLOW</span>
            </div>