<?php
require_once __DIR__ . '../../db/connection.php';
require_once __DIR__ . '/expense.php';
require_once __DIR__ . '/shared-expense.php';

date_default_timezone_set('Europe/Lisbon');

/* EMAIL EXISTING VALIDATIONS */

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
?>