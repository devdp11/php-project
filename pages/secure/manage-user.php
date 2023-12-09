<?php
    require_once __DIR__ . '../../../middlewares/middleware-user.php';
    @require_once __DIR__ . '/../../validations/session.php';
    require_once __DIR__ . '/../../db/connection.php';
    $user = user();
    $countriesJson = file_get_contents('../templates/countries.json');
    $countries = json_decode($countriesJson, true);

    try {
        
        if (isset($_POST['submit'])) {
            $first_name = $_POST['firstname'];
            $last_name = $_POST['lastname'];
            $password = $_POST['password'];
            $email = $_POST['email'];
            $admin = isset($_POST['admin']) ? 1 : 0;

            $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, password, email, admin, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
            $stmt->execute([$first_name, $last_name, $password, $email, $admin]);

        }
    } catch (PDOException $e) {
        echo "Error: " . $e->getMessage();
        exit();
    }
?>

<?php include __DIR__ . '/sidebar.php'; ?>

    <div class="p-4">
            <nav style="--bs-breadcrumb-divider:'>';font-size:14px">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><i class="fa-solid fa-house"></i></li>
                    <li class="breadcrumb-item">Dashboard</li>
                    <li class="breadcrumb-item">Admin</li>
                    <li class="breadcrumb-item">Users</li>
                </ol>
            </nav>
            
            <div  class="container">
                <form method="post">
                    <div class="form-group mt-3">
                        <label>First Name</label>
                        <input autocomplete="off" type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name">
                    </div>
                    <div class="form-group mt-3">
                        <label>Last Name</label>
                        <input autocomplete="off" type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name">
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
                        <label class="form-check-label">
                            Admin?
                        </label>
                    </div>
                    <button name="submit" type="submit" class="btn btn-primary">Create User</button>
                </form>
            </div>