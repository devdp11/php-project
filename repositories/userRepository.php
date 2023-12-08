<?php
require_once __DIR__ . '../../db/connection.php';

date_default_timezone_set('Europe/Lisbon');

function createUser($user)
{
    $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
    $sqlCreate = "INSERT INTO 
    users (
        first_name,
        last_name, 
        password, 
        email,
        avatar, 
        admin,
        created_at, 
        updated_at
    ) 
    VALUES (
        :first_name,
        :last_name, 
        :password, 
        :email,
        :avatar,
        :admin,
        NOW(), 
        NOW()
    )";

    $PDOStatement = $GLOBALS['pdo']->prepare($sqlCreate);

    $success = $PDOStatement->execute([
        ':first_name' => $user['first_name'],
        ':last_name' => $user['last_name'],
        ':password' => $user['password'],
        ':email' => $user['email'],
        ':admin' => $user['admin'],
    ]);

    if ($success) {
        $user['id'] = $GLOBALS['pdo']->lastInsertId();
    }
    return $success;
}

function getById($id)
{
    $PDOStatement = $GLOBALS['pdo']->prepare('SELECT * FROM users WHERE id = ?;');
    $PDOStatement->bindValue(1, $id, PDO::PARAM_INT);
    $PDOStatement->execute();
    return $PDOStatement->fetch();
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

function getByEmail($email)
{
    $PDOStatement = $GLOBALS['pdo']->prepare('SELECT * FROM users WHERE email = ? LIMIT 1;');
    $PDOStatement->bindValue(1, $email);
    $PDOStatement->execute();
    return $PDOStatement->fetch();
}

function getAll()
{
    $PDOStatement = $GLOBALS['pdo']->query('SELECT * FROM users;');
    $users = [];
    while ($listaDeusers = $PDOStatement->fetch()) {
        $users[] = $listaDeusers;
    }
    return $users;
}

function updateUser($user)
{
    $passwordUpdate = '';
    if (isset($user['password']) && !empty($user['password'])) {
        $passwordUpdate = ', password = :password';
        $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
    }

    $user['updated_at'] = date('Y-m-d H:i:s');

    $sqlUpdate = "UPDATE users SET
        first_name = :first_name,
        last_name = :last_name,
        email = :email,
        country = :country,
        birthdate = :birthdate,
        updated_at = :updated_at
        $passwordUpdate
        WHERE id = :id";

    $PDOStatement = $GLOBALS['pdo']->prepare($sqlUpdate);

    $bindParams = [
        ':id' => $user['id'],
        ':first_name' => $user['first_name'],
        ':last_name' => $user['last_name'],
        ':email' => $user['email'],
        ':country' => $user['country'],
        ':birthdate' => $user['birthdate'],
        ':updated_at' => $user['updated_at'],
    ];

    if (!empty($passwordUpdate)) {
        $bindParams[':password'] = $user['password'];
    }

    $success = $PDOStatement->execute($bindParams);

    return $success;
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
    $sqlUpdate = "UPDATE  
        users SET
            deleted_at = NOW()
        WHERE id = :id;";

    $PDOStatement = $GLOBALS['pdo']->prepare($sqlUpdate);

    return $PDOStatement->execute([
        ':id' => $id,
    ]);
}

function updateUserAvatar($userId, $avatar)
{
    $sqlUpdate = "UPDATE users SET
        avatar = :avatar
        WHERE id = :id";

    $PDOStatement = $GLOBALS['pdo']->prepare($sqlUpdate);

    $bindParams = [
        ':id' => $userId,
        ':avatar' => $avatar,
    ];

    $success = $PDOStatement->execute($bindParams);

    return $success;
}

function createNewUser($user)
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

?>