<?php
require_once __DIR__ . '../../db/connection.php';

function createUser($user)
{
    $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);
    $sqlCreate = "INSERT INTO 
    users (
        first_name,
        last_name, 
        password, 
        email, 
        admin,
        created_at, 
        updated_at
    ) 
    VALUES (
        :first_name,
        :last_name, 
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

    $sqlUpdate = "UPDATE users SET
        first_name = :first_name,
        last_name = :last_name,
        email = :email,
        country = :country,
        birthdate = :birthdate
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
    ];

    if (!empty($passwordUpdate)) {
        $bindParams[':password'] = $user['password'];
    }

    $success = $PDOStatement->execute($bindParams);

    return $success;
}

function updatePassword($user)
{
    if (isset($user['password']) && !empty($user['password'])) {
        $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);

        $sqlUpdate = "UPDATE  
        users SET
            password = :password,
            updated_at = NOW()
        WHERE id = :id;";

        $PDOStatement = $GLOBALS['pdo']->prepare($sqlUpdate);

        return $PDOStatement->execute([
            ':id' => $user['id'],
            ':password' => $user['password'],
        ]);
    }
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
        admin
    ) 
    VALUES (
        :first_name,
        :last_name, 
        :email, 
        :password,
        :admin
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