<?php
    require_once __DIR__ . '../../../middlewares/middleware-user.php';
    @require_once __DIR__ . '/../../validations/session.php';
    $user = user();

    $sideBTN = FALSE;
    include_once __DIR__ . '/../templates/header.php';
?>

<main>
    <div class="row align-items-md-stretch">
        <div class="mt-5 col-md-6">
            <div class="h-100 p-5 text-bg-dark rounded-3">
                <a href="/php-project/pages/secure/user/profile.php"><button class="btn px-3 mx-3"
                        type="button">Change</button></a>
                <a href="../public/dashboard.php"><button class="btn"
                        type="button">Go Back</button></a>
            </div>
            <form class="mx-5 d-flex justify-content-start" action="../../controllers/auth/signin.php" method="post">                
                <button class="btn w-10 btn-danger btn-lg" type="submit" name="user" value="logout">Logout</button>
            </form>
        </div>
    </div>
</main>