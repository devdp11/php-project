<?php
require_once __DIR__ . '../../db/connection.php';
require_once __DIR__ . '/expense.php';

date_default_timezone_set('Europe/Lisbon');

/* ADMIN QUERY / SETUP QUERY  */

function createUser($user)
{
    $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);

    $sqlCreate = "INSERT INTO 
    users (
        first_name,
        last_name,
        birthdate, 
        password, 
        email,
        admin,
        created_at, 
        updated_at
    ) 
    VALUES (
        :first_name,
        :last_name, 
        :birthdate, 
        :password, 
        :email,
        :admin,
        NOW(), 
        NOW()
    )";

    $PDOStatement = $GLOBALS['pdo']->prepare($sqlCreate);

    $success = $PDOStatement->execute([
        ':first_name' => $user['first_name'],
        ':last_name' => $user['last_name'],
        ':birthdate' => $user['birthdate'],
        ':password' => $user['password'],
        ':email' => $user['email'],
        ':admin' => $user['admin'],
    ]);

    if ($success) {
        $user['id'] = $GLOBALS['pdo']->lastInsertId();
    }

    return $success;
}

function updateAdminUser($userId, $userData)
{
    try {
        $sqlUpdate = "UPDATE users SET
            first_name = :first_name,
            last_name = :last_name,
            email = :email,
            country = :country,
            birthdate = :birthdate,
            admin = :admin,
            updated_at = CURRENT_TIMESTAMP";

        if (!empty($userData['password'])) {
            $sqlUpdate .= ', password = :password';
            $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);
        }

        $sqlUpdate .= " WHERE id = :user_id";

        $PDOStatement = $GLOBALS['pdo']->prepare($sqlUpdate);

        $params = [
            ':first_name' => $userData['first_name'],
            ':last_name' => $userData['last_name'],
            ':email' => $userData['email'],
            ':country' => $userData['country'],
            ':birthdate' => $userData['birthdate'],
            ':admin' => $userData['admin'],
            ':user_id' => $userId,
        ];

        // Adicionar a senha aos parâmetros se ela estiver presente
        if (!empty($userData['password'])) {
            $params[':password'] = $userData['password'];
        }

        $params = array_filter($params, function ($value) {
            return $value !== '';
        });

        return $PDOStatement->execute($params);
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

function getAll()
{
    $stmt = $GLOBALS['pdo']->prepare('SELECT * FROM users WHERE deleted_at IS NULL;');
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getById($id)
{
    try {
        $PDOStatement = $GLOBALS['pdo']->prepare('SELECT * FROM users WHERE id = :id');
        $PDOStatement->bindParam(':id', $id, PDO::PARAM_INT);
        $PDOStatement->execute();

        return $PDOStatement->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
}

/* ACHO QUE AQUI PODEMOS APAGAR O getIdByEmail PORQUE O GETBYEMAIL JA TRAZ TUDO E NÃO APENAS O ID */

function getByEmail($email)
{
    $PDOStatement = $GLOBALS['pdo']->prepare('SELECT * FROM users WHERE email = ? LIMIT 1;');
    $PDOStatement->bindValue(1, $email);
    $PDOStatement->execute();
    return $PDOStatement->fetch();
}

function getIdByEmail($email)
{
    try {
        $sql = 'SELECT id FROM users WHERE email = :email AND deleted_at IS NULL';
        $PDOStatement = $GLOBALS['pdo']->prepare($sql);
        $PDOStatement->bindParam(':email', $email, PDO::PARAM_STR);
        $PDOStatement->execute();

        $result = $PDOStatement->fetch(PDO::FETCH_ASSOC);

        echo '<script>console.log(' . json_encode($result) . ')</script>';

        return ($result) ? $result['id'] : null;
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return null;
    }
}


/* USER QUERIES */

function registerUser($user)
{
    $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
    $user['admin'] = false;

    $sqlCreate = "INSERT INTO 
    users (
        first_name,
        last_name, 
        email, 
        password,
        admin,
        created_at,
        updated_at
    ) 
    VALUES (
        :first_name,
        :last_name, 
        :email, 
        :password,
        :admin,
        NOW(), 
        NOW()
    )";

    $PDOStatement = $GLOBALS['pdo']->prepare($sqlCreate);
    $success = $PDOStatement->execute([
        ':first_name' => $user['first_name'],
        ':last_name' => $user['last_name'],
        ':email' => $user['email'],
        ':password' => $user['password'],
        ':admin' => $user['admin'],
    ]);

    if ($success) {
        $user['id'] = $GLOBALS['pdo']->lastInsertId();
        return $user;
    }

    return false;
}

function updateAvatar($userId, $avatar)
{
    $user['updated_at'] = date('Y-m-d H:i:s');

    $sqlUpdate = "UPDATE users SET
        avatar = :avatar,
        updated_at = :updated_at
        WHERE id = :id";

    $PDOStatement = $GLOBALS['pdo']->prepare($sqlUpdate);

    $bindParams = [
        ':id' => $userId,
        ':avatar' => $avatar,
        ':updated_at' => $user['updated_at'],
    ];

    $success = $PDOStatement->execute($bindParams);

    return $success;
}

function updateUser($user)
{
    $passwordUpdate = '';
    $updateFields = [];

    if (isset($user['password']) && !empty($user['password'])) {
        $passwordUpdate = ', password = :password';
        $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
    }

    $user['updated_at'] = date('Y-m-d H:i:s');

    $sqlUpdate = "UPDATE users SET
        updated_at = :updated_at";

    $bindParams = [
        ':id' => $user['id'],
        ':updated_at' => $user['updated_at'],
    ];

    if (isset($user['first_name'])) {
        $sqlUpdate .= ', first_name = :first_name';
        $bindParams[':first_name'] = $user['first_name'];
    }

    if (isset($user['last_name'])) {
        $sqlUpdate .= ', last_name = :last_name';
        $bindParams[':last_name'] = $user['last_name'];
    }

    if (isset($user['email'])) {
        $sqlUpdate .= ', email = :email';
        $bindParams[':email'] = $user['email'];
    }

    if (isset($user['country'])) {
        $sqlUpdate .= ', country = :country';
        $bindParams[':country'] = $user['country'];
    }

    if (isset($user['birthdate'])) {
        $sqlUpdate .= ', birthdate = :birthdate';
        $bindParams[':birthdate'] = $user['birthdate'];
    }

    if (isset($user['admin'])) {
        $sqlUpdate .= ', admin = :admin';
        $bindParams[':admin'] = $user['admin'];
    }

    $sqlUpdate .= $passwordUpdate;
    $sqlUpdate .= ' WHERE id = :id';

    $PDOStatement = $GLOBALS['pdo']->prepare($sqlUpdate);

    $success = $PDOStatement->execute($bindParams);

    return $success;
}

function getHashedPasswordById($id)
{
    
    $PDOStatement = $GLOBALS['pdo']->prepare('SELECT password FROM users WHERE id = ?;');
        
    $PDOStatement->bindValue(1, $id, PDO::PARAM_INT);
        
    $PDOStatement->execute();
        
    $userData = $PDOStatement->fetch(PDO::FETCH_ASSOC);
   
    if (!$userData) {
        return false;
    }

    return $userData['password'];
}

function updatePassword($id, $hashedPassword)
{
    $sqlUpdatePassword = "UPDATE users SET
        password = :password,
        updated_at = :updated_at
        WHERE id = :id";

    $PDOStatement = $GLOBALS['pdo']->prepare($sqlUpdatePassword);

    $bindParams = [
        ':id' => $id,
        ':password' => $hashedPassword,
        ':updated_at' => $user['updated_at'],
    ];

    $success = $PDOStatement->execute($bindParams);

    return $success;
}

function softDeleteUser($id)
{
    $sqlSelectEmail = "SELECT email FROM users WHERE id = :id";
    $selectStatement = $GLOBALS['pdo']->prepare($sqlSelectEmail);
    $selectStatement->execute([':id' => $id]);
    $userEmail = $selectStatement->fetchColumn();

    $newEmail = 'deleted_' . $userEmail;

    $sqlUpdate = "UPDATE users SET
                    email = :newEmail,
                    deleted_at = NOW()
                  WHERE id = :id;";
    $updateStatement = $GLOBALS['pdo']->prepare($sqlUpdate);
    $updateSuccess = $updateStatement->execute([
        ':id' => $id,
        ':newEmail' => $newEmail,
    ]);

    if ($updateSuccess) {
        deleteExpensesByUserId($id);
        deleteSharedExpensesByUserId($id);
    }

    return $userEmail;
}

/* ADMIN DASHBOARD QUERIES */

function getDeletedUsersCount() {
    $stmt = $GLOBALS['pdo']->prepare('SELECT COUNT(*) as deleted_count FROM users WHERE deleted_at IS NOT NULL;');
    $stmt->execute();
    $deletedCount = $stmt->fetch(PDO::FETCH_ASSOC)['deleted_count'];
    return $deletedCount;
}

function getActiveUsersCount() {
    $stmt = $GLOBALS['pdo']->prepare('SELECT COUNT(*) as active_count FROM users WHERE deleted_at IS NULL;');
    $stmt->execute();
    $activeCount = $stmt->fetch(PDO::FETCH_ASSOC)['active_count'];
    return $activeCount;
}

function getUsersByCountryCount() {
    $stmt = $GLOBALS['pdo']->prepare('SELECT country, COUNT(*) as user_count FROM users WHERE deleted_at IS NULL GROUP BY country;');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function getUsersWithSharedExpensesCount() {
    $stmt = $GLOBALS['pdo']->prepare('SELECT COUNT(DISTINCT receiver_user_id) as users_with_shared_expenses_count 
                                      FROM shared_expenses 
                                      WHERE deleted_at IS NULL;');
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['users_with_shared_expenses_count'];
}

function getUsersWithExpensesCount() {
    $stmt = $GLOBALS['pdo']->prepare('SELECT COUNT(DISTINCT user_id) as users_with_expenses_count 
                                      FROM expenses 
                                      WHERE deleted_at IS NULL;');
    $stmt->execute();
    return $stmt->fetch(PDO::FETCH_ASSOC)['users_with_expenses_count'];
}
?>