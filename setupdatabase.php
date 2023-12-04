<?php
# EASY DATABASE SETUP
require __DIR__ . '/infra/db/connection.php';

# DROP TABLES (if they exist)
$tablesToDrop = ['expenses', 'attachments', 'categories', 'methods', 'users'];

foreach ($tablesToDrop as $table) {
    $pdo->exec("DROP TABLE IF EXISTS $table;");
}

# CREATE TABLES
$pdo->exec('
    CREATE TABLE users (
        id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        name varchar(255) NOT NULL,
        password varchar(255) NOT NULL,
        email varchar(255) NOT NULL,
        created_at timestamp NULL DEFAULT NULL,
        updated_at timestamp NULL DEFAULT NULL,
        deleted_at timestamp NULL DEFAULT NULL,
        PRIMARY KEY (id),
        UNIQUE KEY users_name_unique (name)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    
    CREATE TABLE methods (
        id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        description varchar(255) NOT NULL,
        created_at timestamp NULL DEFAULT NULL,
        updated_at timestamp NULL DEFAULT NULL,
        deleted_at timestamp NULL DEFAULT NULL,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    
    CREATE TABLE categories (
        id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        description varchar(255) NOT NULL,
        created_at timestamp NULL DEFAULT NULL,
        updated_at timestamp NULL DEFAULT NULL,
        deleted_at timestamp NULL DEFAULT NULL,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    
    CREATE TABLE attachments (
        id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        description varchar(255) NOT NULL,
        created_at timestamp NULL DEFAULT NULL,
        updated_at timestamp NULL DEFAULT NULL,
        deleted_at timestamp NULL DEFAULT NULL,
        PRIMARY KEY (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    
    CREATE TABLE expenses (
        expense_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
        category_id bigint(20) UNSIGNED NOT NULL,
        description varchar(255) NOT NULL,
        payment_id bigint(20) UNSIGNED NOT NULL,
        amount decimal(10,2) NOT NULL,
        paid tinyint(1) NOT NULL,
        date date NOT NULL,
        note varchar(255) DEFAULT NULL,
        attachment_id bigint(20) UNSIGNED NOT NULL,
        user_id bigint(20) UNSIGNED NOT NULL,
        created_at timestamp NULL DEFAULT NULL,
        updated_at timestamp NULL DEFAULT NULL,
        deleted_at timestamp NULL DEFAULT NULL,
        PRIMARY KEY (expense_id),
        KEY expenses_category_id_foreign (category_id),
        KEY expenses_payment_id_foreign (payment_id),
        KEY expenses_attachment_id_foreign (attachment_id),
        KEY expenses_user_id_foreign (user_id),
        CONSTRAINT expenses_attachment_id_foreign FOREIGN KEY (attachment_id) REFERENCES attachments (id),
        CONSTRAINT expenses_category_id_foreign FOREIGN KEY (category_id) REFERENCES categories (id),
        CONSTRAINT expenses_payment_id_foreign FOREIGN KEY (payment_id) REFERENCES methods (id),
        CONSTRAINT expenses_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
');

date_default_timezone_set('Europe/Lisbon');

# DEFAULT USER TO ADD
$user = [
    'name' => 'root',
    'email' => 'root@root',
    'password' => 'root',
    'created_at' => date('Y-m-d H:i:s'),
    'updated_at' => date('Y-m-d H:i:s')
];

# HASH PWD
$user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);

# INSERT USER
$sqlCreate = "INSERT INTO 
    users (
        name, 
        password, 
        email,
        created_at,
        updated_at
    ) 
    VALUES (
        :name, 
        :password, 
        :email,
        :created_at,
        :updated_at
    )";

# PREPARE QUERY
$PDOStatement = $GLOBALS['pdo']->prepare($sqlCreate);

# EXECUTE
$success = $PDOStatement->execute([
    ':name' => $user['name'],
    ':password' => $user['password'],
    ':email' => $user['email'],
    ':created_at' => $user['created_at'],
    ':updated_at' => $user['updated_at']
]);