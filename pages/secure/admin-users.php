<?php
    require_once __DIR__ . '../../../middlewares/middleware-user.php';
    @require_once __DIR__ . '/../../validations/session.php';
    require_once __DIR__ . '/../../db/connection.php';
?>

<?php include __DIR__ . '/sidebar.php'; ?>
<link rel="stylesheet" href="../resources/styles/global.css">

<link rel="stylesheet" href="../resources/styles/card.css">

<div class="p-4 overflow-auto h-100">
    <nav style="--bs-breadcrumb-divider:'>';font-size:14px">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><i class="fa-solid fa-house"></i></li>
            <li class="breadcrumb-item">Dashboard</li>
            <li class="breadcrumb-item">Admin</li>
            <li class="breadcrumb-item">Users</li>
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

    <button class="btn btn-blueviolet my-3" data-bs-toggle="modal" data-bs-target="#add-user">
        Add User
    </button>

    <div class="row row-cols-1 row-cols-md-3 g-3">
        <?php
        $users = getAll();

        foreach ($users as $user) {
            echo "<div class='col'>";
            echo "<div class='card style'>";
            echo "<div class='card-body' onclick='updateUser({$user['id']})'>";
            echo "<form action='../../controllers/admin/user.php' method='post' class='float-end' onsubmit='return confirmDelete(event, {$user['id']})'>";
            echo "<input type='hidden' name='user_id' value='{$user['id']}'>";
            echo "<button type='submit' name='user' value='delete' class='btn btn-danger btn-sm m-1'><i class='fas fa-trash-alt'></i></button>";
            echo "</form>";            
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

</div>

    <script>
        function updateUser(userId) {
            window.location.href = "update-user.php?id=" + userId;
        }
        
        function confirmDelete(event, userId) {
            console.log("confirmDelete called for user ID:", userId);
            event.stopPropagation();
            return confirm("Are you sure you want to delete this user?");
        }
    </script>