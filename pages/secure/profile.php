<?php
    require_once __DIR__ . '../../../middlewares/middleware-user.php';
    @require_once __DIR__ . '/../../validations/session.php';
    $user = user();
    $countriesJson = file_get_contents('../templates/countries.json');
    $countries = json_decode($countriesJson, true);
?>

<!--Website: wwww.codingdung.com-->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="./fontawesome-free-6.2.1-web/css/all.css">
    <link rel="stylesheet" href="../resources/styles/profile.css">
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
                            <a href="./expense.php" class="nav-link text-white">
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
            <div class="container light-style flex-grow-1 container-p-y">
                <h4 class="font-weight-bold py-3 mb-1">
                    Account settings
                </h4>
                <div class="card overflow-hidden">
                    <div class="row no-gutters row-bordered row-border-light">
                        <div class="col-md-2 pt-0 d-flex flex-column">
                            <div class="list-group list-group-flush account-settings-links">
                                <a class="list-group-item list-group-item-action" data-toggle="list"
                                    href="#account-general">Profile</a>
                                <a class="list-group-item list-group-item-action" data-toggle="list"
                                    href="#account-change-password">Change password</a>
                                <form action="../../controllers/auth/signin.php" method="post">
                                    <button class="btn btn-danger mx-4" type="submit" name="user" value="logout">Logout</button>
                                </form>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="tab-content">
                                <div class="tab-pane fade active show" id="account-general">
                                    <div class="card-body">
                                        <div class="d-flex justify-content-center">
                                            <img src="https://bootdey.com/img/Content/avatar/avatar1.png" alt
                                                class="d-block ui-w-80">
                                        </div>
                                        <div class="d-flex justify-content-center my-2">
                                            <form action="../../controllers/update-profile.php" method="post" enctype="multipart/form-data">
                                                <label class="mt-2 px-3 btn btn-outline-primary">
                                                    Upload
                                                    <input type="file" class="account-settings-fileinput" name="avatar">
                                                </label> &nbsp;
                                                <label class="mt-2 px-4 btn btn-danger md-btn-flat">
                                                    Reset
                                                </label>
                                            </form>
                                        </div>
                                    </div>
                                    <div class="card-body d-flex justify-content-center">
                                        <form action="../../controllers/user/user.php" method="post">
                                            <div class="row">
                                                <div class="col-auto form-group">
                                                    <label class="form-label">First Name</label>
                                                    <input type="text" class="form-control mb-1" name="first_name" value="<?= $user['first_name'] ?>">
                                                </div>
                                                <div class="col-auto form-group">
                                                    <label class="form-label">Last Name</label>
                                                    <input type="text" class="form-control mb-1" name="last_name" value="<?= $user['last_name'] ?>">
                                                </div>
                                            </div>
                                            
                                            <div class="form-group">
                                                <label class="form-label">Email</label>
                                                <input type="text" class="form-control mb-1" name="email" value="<?= $user['email'] ?>">
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Country</label>
                                                <select class="form-select mb-1" name="country">
                                                    <?php foreach ($countries as $country): ?>
                                                        <option value="<?= $country['name'] ?>" <?= ($user['country'] == $country['name']) ? 'selected' : '' ?>>
                                                            <?= $country['name'] ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div>
                                            <div class="form-group">
                                                <label class="form-label">Birth Date</label>
                                                <input type="date" class="form-control mb-1" name="birthdate" value="<?= $user['birthdate'] ?>">
                                            </div>

                                            <div class="text-right my-3">
                                                <button type="submit" class="btn btn-primary">Save changes</button>&nbsp;
                                                <button type="button" class="btn btn-default">Cancel</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="account-change-password">
                                    <div class="card-body pb-2">
                                        <div class="form-group">
                                            <label class="form-label">Current password</label>
                                            <input type="password" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">New password</label>
                                            <input type="password" class="form-control">
                                        </div>
                                        <div class="form-group">
                                            <label class="form-label">Repeat new password</label>
                                            <input type="password" class="form-control">
                                        </div>
                                        <div class="text-right my-3">
                                                <button type="submit" class="btn btn-primary">Save changes</button>&nbsp;
                                                <button type="button" class="btn btn-default">Cancel</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script data-cfasync="false" src="/cdn-cgi/scripts/5c5dd728/cloudflare-static/email-decode.min.js"></script>
    <script src="https://code.jquery.com/jquery-1.10.2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.0/dist/js/bootstrap.bundle.min.js"></script>
</body>



</html>