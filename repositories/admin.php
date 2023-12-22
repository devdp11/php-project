<?php
require_once __DIR__ . '../../db/connection.php';

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

        if (empty($userData['birthdate'])) {
            $userData['birthdate'] = null;
        }

        $params = [
            ':first_name' => $userData['first_name'],
            ':last_name' => $userData['last_name'],
            ':email' => $userData['email'],
            ':country' => $userData['country'],
            ':birthdate' => $userData['birthdate'],
            ':admin' => $userData['admin'],
            ':user_id' => $userId,
        ];

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

function getUsersByName($name)
{
    try {
        $query = 'SELECT * FROM users WHERE first_name LIKE :name OR last_name LIKE :name';
        $nameParam = "%{$name}%";

        $PDOStatement = $GLOBALS['pdo']->prepare($query);
        $PDOStatement->bindParam(':name', $nameParam, PDO::PARAM_STR);
        $PDOStatement->execute();

        $users = [];

        while ($user = $PDOStatement->fetch(PDO::FETCH_ASSOC)) {
            $users[] = $user;
        }

        return $users;
    } catch (PDOException $e) {
        echo 'Error: ' . $e->getMessage();
        return false;
    }
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
?>