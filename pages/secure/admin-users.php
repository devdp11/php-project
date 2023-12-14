<?php
    require_once __DIR__ . '../../../middlewares/middleware-user.php';
    @require_once __DIR__ . '/../../validations/session.php';
    require_once __DIR__ . '/../../db/connection.php';
    require_once __DIR__ . '/../../repositories/user.php';
?>

<?php include __DIR__ . '/sidebar.php'; ?>
<link rel="stylesheet" href="../resources/styles/global.css">

    <style>
        .style {
            background-color: blueviolet;
            color: white;
            border-color: blueviolet;
        }

        .style:hover {
            
            background-color: white;
            color: blueviolet;
            border-color: darkviolet;
        }

        .style:focus {
            box-shadow: 0 0 0 0.2rem rgba(138, 43, 226, 0.25);
        }
    </style>

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

    <div class="p-4 overflow-auto h-100">
        <nav style="--bs-breadcrumb-divider:'>';font-size:14px">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><i class="fa-solid fa-house"></i></li>
                <li class="breadcrumb-item">Dashboard</li>
                <li class="breadcrumb-item">Admin</li>
                <li class="breadcrumb-item">Users</li>
            </ol>
        </nav>

        <div class="container">
            <button class="btn btn-blueviolet mt-5 mb-2" data-bs-toggle="modal" data-bs-target="#add-user">
                Add User
            </button>


            <?php
            if (isset($_GET['success']) && $_GET['success'] == 1) {
                echo '<div id="success-alert" class="alert alert-danger" role="alert">';
                echo 'User removed successfully!';
                echo '</div>';
            }
            ?>

            <div class="row row-cols-1 row-cols-md-3 g-2">
                <?php
                $users = getAll();

                foreach ($users as $user) {
                    echo "<div class='col'>";
                    echo "<div class='card style'>";
                    echo "<div class='card-body' onclick='updateUser({$user['id']})'>";
                    echo "<button class='btn btn-danger btn-sm float-end' style='z-index:999' onclick='deleteUser({$user['id']}, event)'><i class='fas fa-trash-alt'></i></button>";
                    echo "<h5 class='card-title'>{$user['first_name']} {$user['last_name']}</h5>";
                    echo "<p class='card-text'><strong>Email:</strong> {$user['email']}</p>";
                    echo "<p class='card-text'><strong>Birthdate:</strong> " . ($user['birthdate'] ? $user['birthdate'] : "No Birthdate Found") . "</p>";
                    echo "<p class='card-text'><strong>Admin:</strong> " . ($user['admin'] == 1 ? 'Yes' : 'No') . "</p>";
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                }
                ?>
            </div>
        </div>
    </div>

    <!-- MODAL ADD USER -->
    <div class="modal fade" id="add-user" tabindex="-1" aria-labelledby="modal-title" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modal-title"> Add a User </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body pt-0">
                    <form action="../../controllers/admin/user.php" method="post">
                        <div class="form-group mt-3">
                            <label>First Name</label>
                            <input autocomplete="off" type="text" class="form-control" id="first_name" name="first_name" placeholder="First Name">
                        </div>
                        <div class="form-group mt-3">
                            <label>Last Name</label>
                            <input autocomplete="off" type="text" class="form-control" id="last_name" name="last_name" placeholder="Last Name">
                        </div>
                        <div class="form-group mt-3">
                            <label>Email</label>
                            <input autocomplete="off" type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter email">
                        </div>
                        <div class="form-group mt-3">
                            <label>Password</label>
                            <input autocomplete="off" type="password" class="form-control" id="password" name="password" placeholder="Password">
                        </div>
                        <div class="form-check mt-3">
                            <input autocomplete="off" class="form-check-input" type="checkbox" name="admin" id="admin">
                            <label class="form-check-label">Admin?</label>
                        </div>
                        <button type="submit" class="btn btn-blueviolet mt-3" name="user" value="create">Create</button>
                    </form>
                </div>
            </div>
        </div>
    </div>


    <script>
        setTimeout(function() {
            var successAlert = document.getElementById('success-alert');
            if (successAlert) {
                successAlert.style.display = 'none';
            }
        }, 2000);

        function updateUser(userId) {
            window.location.href = "update-user.php?id=" + userId;
        }
        
        function deleteUser(userId, event) {
            event.stopPropagation();    
            if (confirm("Are you sure you want to delete this user?")) {
                window.location.href = "/php-project/repositories/user.php?action=softDeleteUser&id=" + userId;
            }
        }
    </script>