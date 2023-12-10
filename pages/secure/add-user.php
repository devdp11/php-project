    <?php
        require_once __DIR__ . '/../../middlewares/middleware-user.php';
        @require_once __DIR__ . '/../../validations/session.php';
        require_once __DIR__ . '/../../validations/admin/validate-password.php';
        require_once __DIR__ . '/../../db/connection.php';
        $user = user();
        $countriesJson = file_get_contents('../templates/countries.json');
        $countries = json_decode($countriesJson, true);

        try {
            $errors = [];

            if (isset($_POST['submit'])) {
                $password = $_POST['password'];

                // Validação da senha
                if (empty($password) || !preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^a-zA-Z\d])\S{8,}$/', $password)) {
                    $errors['password']='Password must be at least 8 characters long and contain at least one digit, one uppercase letter, one lowercase letter, and one special character!';
                }

                if (empty($errors)) {
                    $first_name = $_POST['firstname'];
                    $last_name = $_POST['lastname'];
                    $email = $_POST['email'];
                    $admin = isset($_POST['admin']) ? 1 : 0;

                    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                    $stmt = $pdo->prepare("INSERT INTO users (first_name, last_name, password, email, admin, created_at, updated_at) VALUES (?, ?, ?, ?, ?, NOW(), NOW())");
                    $stmt->execute([$first_name, $last_name, $hashedPassword, $email, $admin]);

                    echo '<script>';
                    echo 'setTimeout(function() {';
                    echo '  window.location.href = "php-project/pages/secure/display-users.php";';
                    echo '}, 1500);';
                    echo '</script>';
                }
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
                        <li class="breadcrumb-item">Create User</li>
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
                        <button name="submit" type="submit" class="btn btn-blueviolet mt-3">Create User</button>
                        <?php if (isset($_POST['submit']) && empty($errors)): ?>
                            
                            <div class="alert alert-success mt-3" role="alert">
                                User created successfully!
                            </div>
                        <?php endif; ?>
                    </form>

                    <?php if (!empty($errors)): ?>
                        
                        <div class="alert alert-danger mt-3" role="alert">
                            <ul>
                                <?php foreach ($errors as $error): ?>
                                    <li><?php echo $error; ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>
                </div>