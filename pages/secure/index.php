<?php
require_once __DIR__ . '../../../infra/middlewares/middleware-user.php';
@require_once __DIR__ . '/../../helpers/session.php';
include_once __DIR__ . '../../../templates/header.php';

$user = user();
$title = '- App';
?>

<main>
    <header class="pb-3 mb-4 border-bottom">
        <a href="/" class="d-flex align-items-center text-dark text-decoration-none"><img
                src="/crud/assets/images/logo-estg.svg" alt="ESTG" class="mw-100"></a>
    </header>
    <div class="p-5 mb-4 bg-body-tertiary rounded-3">
        <div class="container-fluid py-5">
            <h1 class="display-5 fw-bold">Hello
                <?= $user['name'] ?? null ?>!
            </h1>
            <p class="col-md-8 fs-4">Ready for today?</p>
            <div class="d-flex justify-content">
                <form action="/crud/controllers/auth/signin.php" method="post">
                    <button class="btn btn-danger btn-lg px-4" type="submit" name="user" value="logout">Logout</button>
                </form>
            </div>
        </div>
    </div>

    <div class="row align-items-md-stretch">
        <div class="col-md-6">
            <div class="h-100 p-5 text-bg-dark rounded-3">
                <h2>Profile</h2>
                <a href="/crud/pages/secure/user/profile.php"><button class="btn btn-outline-light px-5"
                        type="button">Change</button></a>
            </div>
        </div>

        <?php
        if (isAuthenticated() && $user['administrator']) {
            echo '<div class="col-md-6">
                    <div class="h-100 p-5 bg-body-tertiary border rounded-3">
                        <h2>Admin</h2>
                        <a href="/crud/pages/secure/admin/"><button class="btn btn-outline-success" type="button">Admin</button></a>
                    </div>
                </div>';
        }
        ?>
    </div>
</main>

<?php
include_once __DIR__ . '../../../templates/footer.php';
?>