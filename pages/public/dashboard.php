<?php
require_once __DIR__ . '/../../middlewares/middleware-authenticated.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Expense Flow - Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <link rel="stylesheet" href="../resources/styles/dashboard.css">
    <script src="../resources/scripts/home.js"></script>
</head>
<body>
<header class="position-fixed w-100" style="background-color: white">
    <div class="navBar px-3 mx-auto">
        <div class="toogle-btn align-items-center my-3 border-0">
            <button type="button" class="btn mx-2" onclick="toggleMenu()">
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                    class="bi bi-list" viewBox="0 0 16 16">
                    <path fill-rule="evenodd"
                        d="M2.5 12a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5zm0-4a.5.5 0 0 1 .5-.5h10a.5.5 0 0 1 0 1H3a.5.5 0 0 1-.5-.5z" />
                </svg>
            </button>
            <a class="logo mr-auto pb-1" href="#home"><img src="../resources/assets/logo.png" alt="logo"></a>
        </div>
    </div>
</header>

<div class="position-fixed h-100 w-25 sidebar" style="background-color: white" id="sidebar">
    <a class="h5 d-block mx-3 my-2 p-3 text-decoration-none" style="color: black;" href="#home"
        onclick="untoggleMenu()">Expenses</a>
    <a class="h5 d-block mx-3 my-2 p-3 text-decoration-none" style="color: black;" href="#workflow"
        onclick="untoggleMenu()">Payment Methods</a>
    <a class="h5 d-block mx-3 my-2 p-3 text-decoration-none" style="color: black;" href="#aboutus"
        onclick="untoggleMenu()">Categories</a>
</div>

</body>
</html>