<?php

function pdo_connect_mysql() {
    $DATABASE_HOST = 'localhost';
    $DATABASE_USER = 'root';
    $DATABASE_PASS = '';
    $DATABASE_NAME = 'expense-db';

    try {
        $pdo = new PDO('mysql:host=' . $DATABASE_HOST . ';dbname=' . $DATABASE_NAME . ';charset=utf8', $DATABASE_USER, $DATABASE_PASS);

        // Ativar mensagens de erro do PDO
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Verificar se a tabela de usuários já existe
        $stmt = $pdo->query("DESCRIBE users");
        if ($stmt === false) {
            // A tabela de usuários não existe, então vamos criá-la
            createUsersTable($pdo);
        }

        echo("Connected!");

        return $pdo;
    } catch (PDOException $exception) {
        exit('Failed to connect to database: ' . $exception->getMessage());
    }
}

function createUsersTable($pdo) {
    try {
        $pdo->exec("
            CREATE TABLE users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                email VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL,
                first_name VARCHAR(255) NOT NULL,
                last_name VARCHAR(255) NOT NULL,
                nationality VARCHAR(255) NOT NULL
            )
        ");

        echo("Users table created successfully!");
    } catch (PDOException $exception) {
        exit('Failed to create users table: ' . $exception->getMessage());
    }
}
?>
