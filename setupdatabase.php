<?php
require_once __DIR__ . '/db/connection.php';

// Check if tables exist
$tablesToCheck = ['users', 'methods', 'categories', 'attachments', 'expenses'];
$tablesExist = true;

foreach ($tablesToCheck as $table) {
    $tableExistQuery = "SHOW TABLES LIKE '$table'";
    $tableExistStatement = $pdo->query($tableExistQuery);

    if ($tableExistStatement->rowCount() === 0) {
        $tablesExist = false;
        break;
    }
}

if (!$tablesExist) {
    $tablesToDrop = ['expenses', 'attachments', 'categories', 'methods', 'users'];

    foreach ($tablesToDrop as $table) {
        $pdo->exec("DROP TABLE IF EXISTS $table;");
    }

    $pdo->exec('
        CREATE TABLE users (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            first_name varchar(255) NOT NULL,
            last_name varchar(255) NOT NULL,
            password varchar(255) NOT NULL,
            email varchar(255) NOT NULL,
            avatar longblob NULL,
            country varchar(255) NULL,
            birthdate date NULL,
            admin BOOLEAN NOT NULL DEFAULT false,
            created_at timestamp NULL DEFAULT NULL,
            updated_at timestamp NULL DEFAULT NULL,
            deleted_at timestamp NULL DEFAULT NULL,
            PRIMARY KEY (id),
            UNIQUE KEY users_id_unique (id)
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

    $user = [
        'first_name' => 'root',
        'last_name' => 'root',
        'avatar' => 'NULL',
        'email' => 'root@root.com',
        'country' => 'Portugal',
        'birthdate' => '2003/08/19',
        'password' => 'root123',
        'admin' => true,
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
    ];

    $user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);

    $sqlCreate = "INSERT INTO 
        users (
            first_name,
            last_name, 
            password,
            avatar,
            email,
            country,
            birthdate,
            admin,
            created_at,
            updated_at
        ) 
        VALUES (
            :first_name,
            :last_name,  
            :password,
            :avatar,
            :email,
            :country,
            :birthdate,
            :admin,
            :created_at,
            :updated_at
        )";

    $PDOStatement = $pdo->prepare($sqlCreate);

    $success = $PDOStatement->execute([
        ':first_name' => $user['first_name'],
        ':last_name' => $user['last_name'],
        ':password' => $user['password'],
        ':avatar' => $user['avatar'],
        ':email' => $user['email'],
        ':country' => $user['country'],
        ':birthdate' => $user['birthdate'],
        ':admin' => $user['admin'],
        ':created_at' => $user['created_at'],
        ':updated_at' => $user['updated_at']
    ]);
    
    $categoriesToInsert = [
        ['description' => 'General'],        
        ['description' => 'Food'],
        ['description' => 'Transportation'],
        ['description' => 'Utilities'],
        ['description' => 'Entertainment'],
        ['description' => 'Rent'],
        ['description' => 'Insurance'],        
        ['description' => 'Mechanic'],        
        ['description' => 'Payroll Taxes'],        
        ['description' => 'Healthcare'],        
        ['description' => 'Investing'],        
        ['description' => 'Debt Payments'],        
        ['description' => 'Personal'],        
        ['description' => 'Miscellaneous'],        
        ['description' => 'Communication'],      
        ['description' => 'Housing'],      
    ];

    foreach ($categoriesToInsert as $categoryData) {
        $CreateQuery = "INSERT INTO categories (description, created_at, updated_at) 
                                VALUES (:description, NOW(), NOW())";
        
        $Statment = $pdo->prepare($CreateQuery);

        $Sucess = $Statment->execute([
            ':description' => $categoryData['description']
        ]);

        if (!$Sucess) {
            echo "Error adding a category: " . implode(" - ", $Statment->errorInfo()) . PHP_EOL;
        }
    }

    $methodsToInsert = [
        ['description' => 'Cash'],
        ['description' => 'Credit Card'],
        ['description' => 'Web Currency'],
        ['description' => 'PayPal'],
        ['description' => 'MB WAY'],
        ['description' => 'Cash APP'],
        ['description' => 'Skrill'],
    ];

    foreach ($methodsToInsert as $methodData) {
        $CreateQuery = "INSERT INTO methods (description, created_at, updated_at) 
                            VALUES (:description, NOW(), NOW())";
        
        $Statment = $pdo->prepare($CreateQuery);

        $Sucess = $Statment->execute([
            ':description' => $methodData['description']
        ]);

        if (!$Sucess) {
            echo "Error adding a payment method: " . implode(" - ", $Statment->errorInfo()) . PHP_EOL;
        }
    }    
}
?>
