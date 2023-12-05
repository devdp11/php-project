<?php
    require_once __DIR__ . '../../../middlewares/middleware-user.php';
    @require_once __DIR__ . '../../../validations/session.php';
    $user = user();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="../resources/styles/dashboard.css">
    <script src="../resources/scripts/home.js"></script>
</head>

<body>
    <header class="position-fixed w-100" style="background-color: rgb(231, 231, 231);">
        <div class="navBar px-4 mx-auto d-flex align-items-center">
            <div class="toogle-btn align-items-center my-3 border-0">
                <button type="button" class="btn" onclick="toggleMenu()">
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                        class="bi bi-list" viewBox="0 0 16 16">
                        <path fill-rule="evenodd"
                            d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z" />
                    </svg>
                </button>
                <a class="logo mr-auto pb-1" href="#"><img src="../resources/assets/logo.png" alt="logo"></a>
            </div>
            <a class="fw-bold text-decoration-none ms-auto mt-1" href="../secure" style="color: blueviolet">Hello <?= $user['name'] ?? null ?>! </a>
        </div>
    </header>

