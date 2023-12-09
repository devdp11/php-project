<?php
require_once __DIR__ . '/../../middlewares/middleware-user.php';
@require_once __DIR__ . '/../../validations/session.php';
require_once __DIR__ . '/../../db/connection.php';
require_once __DIR__ . '/../../repositories/user.php';

$user = [];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = (int)$_GET['id'];

    // Retrieves the user data from the database
    $user = getById($userId);
    $userDetails = $user;

    // Validates the user's input
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $formUserId = (int)$_POST['id'];
        if ($formUserId != $user['id']) {
            echo "Invalid user ID.";
            // Handle the error, maybe redirect or display an error message
            exit();
        }

        $first_name = $_POST['firstname'];
        $last_name = $_POST['lastname'];
        $email = $_POST['email'];
        $admin = isset($_POST['admin']) ? 1 : 0;

        // Updates the user data in the database
        updateUser([
            'id' => $userId,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'admin' => $admin,
        ]);

        // Redirects to the page of the updated user
        header("Location: ./display-users.php?id=$userId");
        exit();
    }
} else {
  echo "Invalid user ID.";
  // You can redirect to the page of displaying users or show a message
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
            <li class="breadcrumb-item">Update User</li>
        </ol>
    </nav>

    <div class="container">
        <form method="post">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">

            <div class="form-group mt-3">
                <label for="firstname">First Name</label>
                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" value="<?php echo ($userDetails['first_name']); ?>">
            </div>

            <div class="form-group mt-3">
                <label for="lastname">Last Name</label>
                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" value="<?php echo ($userDetails['last_name']); ?>">
            </div>

            <div class="form-group mt-3">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter email" value="<?php echo ($userDetails['email']); ?>">
            </div>

            <div class="form-group mt-3">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                <?php if (isset($errors['password'])): ?>
                    <div class="text-danger mt-2"><?php echo $errors['password']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" name="admin" id="admin" <?php echo $userDetails['admin'] ? 'checked' : ''; ?>>
                <label class="form-check-label" for="admin">
                    Admin?
                </label>
            </div>

            <button name="submit" type="submit" class="btn btn-blueviolet mt-3">Update</button>
        </form>
    </div>
</div>
