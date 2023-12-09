<?php
    require_once __DIR__ . '../../../middlewares/middleware-user.php';
    @require_once __DIR__ . '/../../validations/session.php';
    require_once __DIR__ . '/../../db/connection.php';
    require_once __DIR__ . '/../../repositories/user.php';
?>

<?php include __DIR__ . '/sidebar.php'; ?>

    <link rel="stylesheet" href="../resources/styles/global.css">
    <div class="p-4">
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
            <table class="table table-striped">
                <thead>
                    <tr>
                    <th scope="col">First Name</th>
                    <th scope="col">Last Name</th>
                    <th scope="col">Email</th>
                    <th scope="col">Birthdate</th>
                    <th scope="col">Admin</th>
                    <th scope="col">Operations</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $users = getAll();

                    foreach ($users as $user) {
                        echo "<tr>";
                        echo "<td>{$user['first_name']}</td>";
                        echo "<td>{$user['last_name']}</td>";
                        echo "<td>{$user['email']}</td>";
                        echo "<td>";
                        echo $user['birthdate'] ? $user['birthdate'] : "No Birthdate Found";
                        echo "</td>";
                    
                        echo "<td>";
                        echo $user['admin'] == 1 ? "Yes" : "No";
                        echo "</td>";
                    
                        echo "<td>";
                        echo "<button class='btn btn-blueviolet-reverse px-2 mx-2'><a onclick='updateUser({$user['id']})'>Update</a></button>";
                        echo "<button class='btn btn-danger mx-2'><a onclick='deleteUser({$user['id']})'>Delete</a></button>";
                        echo "</td>";
                    
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
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
                window.location.href = "delete-user.php?id=" + userId;
            }
        }
    </script>