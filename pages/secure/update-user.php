<?php
require_once __DIR__ . '/../../middlewares/middleware-user.php';
@require_once __DIR__ . '/../../validations/session.php';
require_once __DIR__ . '/../../db/connection.php';
require_once __DIR__ . '/../../repositories/user.php';

$user = [];

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $userId = (int)$_GET['id'];

    // Busca os dados do usuário pelo ID
    $user = getById($userId);

    // Verifica se o usuário foi encontrado
    if (!$user) {
        echo "User not found.";
        // Você pode redirecionar para a página de exibição de usuários ou mostrar uma mensagem de erro.
        exit();
    }
} else {
    echo "Invalid user ID.";
    // Você pode redirecionar para a página de exibição de usuários ou mostrar uma mensagem de erro.
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Certifica-se de que o ID do formulário corresponde ao ID do usuário
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

    // Verifica se uma nova senha foi fornecida
    if (!empty($_POST['password'])) {
        $password = $_POST['password'];

        if (empty($password) || !preg_match('/^(?=.*\d)(?=.*[A-Z])(?=.*[a-z])(?=.*[^a-zA-Z\d])\S{8,}$/', $password)) {
            $errors['password'] = 'Password must be at least 8 characters long and contain at least one digit, one uppercase letter, one lowercase letter, and one special character!';
        } else {
            // Hash da nova senha
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            // Atualiza a senha no banco de dados
            updatePassword($userId, $hashedPassword);
        }
    }

    // Atualiza os dados do usuário no banco de dados
    updateUser([
        'id' => $userId,
        'first_name' => $first_name,
        'last_name' => $last_name,
        'email' => $email,
        'admin' => $admin,
    ]);

    // Redireciona para a página de exibição de usuários
    header("Location: display-users.php");
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
                <input type="text" class="form-control" id="firstname" name="firstname" placeholder="First Name" value="<?php echo ($user['first_name']); ?>">
            </div>

            <div class="form-group mt-3">
                <label for="lastname">Last Name</label>
                <input type="text" class="form-control" id="lastname" name="lastname" placeholder="Last Name" value="<?php echo ($user['last_name']); ?>">
            </div>

            <div class="form-group mt-3">
                <label for="email">Email</label>
                <input type="email" class="form-control" id="email" name="email" aria-describedby="emailHelp" placeholder="Enter email" value="<?php echo ($user['email']); ?>">
            </div>

            <div class="form-group mt-3">
                <label for="password">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
                <?php if (isset($errors['password'])): ?>
                    <div class="text-danger mt-2"><?php echo $errors['password']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-check mt-3">
                <input class="form-check-input" type="checkbox" name="admin" id="admin" <?php echo $user['admin'] ? 'checked' : ''; ?>>
                <label class="form-check-label" for="admin">
                    Admin?
                </label>
            </div>

            <button name="submit" type="submit" class="btn btn-blueviolet mt-3">Update</button>
        </form>
    </div>
</div>
