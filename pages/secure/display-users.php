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
            <button class="btn btn-blueviolet my-5">
                <a href="./manage-user.php">Add User</a>
            </button>

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
                        echo "<button class='btn btn-primary px-2 mx-2' onclick='updateUser({$user['id']})'>Update</button>";
                        echo "<button class='btn btn-danger mx-2' onclick='deleteUser({$user['id']})'>Delete</button>";
                        echo "</td>";
                    
                        echo "</tr>";
                    }
                    ?>
                </tbody>
                </table>
        </div>
    </div>

    <script>
        function updateUser(userId) {
            alert("Update user with ID " + userId);
        }

        function deleteUser(userId) {
            if (confirm("Are you sure you want to delete this user?")) {
                alert("Delete user with ID " + userId);
            }
        }
    </script>