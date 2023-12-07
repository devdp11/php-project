<?php
    require_once __DIR__ . '../../../middlewares/middleware-user.php';
    @require_once __DIR__ . '/../../validations/session.php';
    $user = user();
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

<main>
    <div class="p-5 mb-4 bg-body-tertiary rounded-3">
        <div class="container-fluid py-5">
            <h1 class="display-5 fw-bold">Hello <?= $user['first_name'] ?? null ?>! </h1>
            <p class="col-md-8 fs-4">Ready for today?</p>
            <div class="d-flex justify-content">
                <form action="../../controllers/auth/signin.php" method="post">
                    <button class="btn btn-danger btn-lg px-4" type="submit" name="user" value="logout">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <div class="row align-items-md-stretch">
        <div class="col-md-6">
            <div class="h-100 p-5 text-bg-dark rounded-3">
                <a href="/php-project/pages/secure/user/profile.php"><button class="btn px-5" type="button">CHANGE</button></a>
                <a href="../public/dashboard.php">BACK</a>
            </div>
        </div>

        <?php
        if (isAuthenticated() && $user['admin']) {
            echo '<div class="col-md-6">
                    <div class="h-100 p-5 bg-body-tertiary border rounded-3">
                        <h2>Admin</h2>
                        <a href="/php-project/pages/secure/admin/"><button class="btn btn-outline-success" type="button">Admin</button></a>
                    </div>
                </div>';
        }
        ?>
    </div>
</main>