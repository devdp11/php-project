<?php
    require_once __DIR__ . '../../../middlewares/middleware-user.php';
    @require_once __DIR__ . '/../../validations/session.php';
    require_once __DIR__ . '/../../db/connection.php';
    require_once __DIR__ . '/../../repositories/user.php';
?>

<?php include __DIR__ . '/sidebar.php'; ?>

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
            box-shadow: 0 0 0 0.2rem rgba(138, 43, 226, 0.25); /* Adiciona uma sombra sutil ao focar */
        }
    </style>

    <link rel="stylesheet" href="../resources/styles/global.css">
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
            <button class="btn btn-blueviolet mt-5 mb-2">
                <a href="add-user.php" class="text-decoration-none">Add User</a>
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
                    echo "<button class='btn btn-danger btn-sm float-end' onclick='deleteUser({$user['id']})'><i class='fas fa-trash-alt'></i></button>";
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
        
        function deleteUser(userId) {
            if (confirm("Are you sure you want to delete this user?")) {
                // Redirecionar para user.php com o ID do usuário na URL
                window.location.href = "/php-project/repositories/user.php?action=softDeleteUser&id=" + userId;
            }
        }
    </script>