<?php
require_once __DIR__ . '/db/connection.php';

date_default_timezone_set('Europe/Lisbon');

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
    $tablesToDrop = ['shared_expenses', 'expenses','attachments', 'categories', 'methods', 'users'];

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

        CREATE TABLE expenses (
            expense_id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            category_id bigint(20) UNSIGNED NOT NULL,
            description varchar(255) NOT NULL,
            payment_id bigint(20) UNSIGNED NOT NULL,
            amount decimal(10,2) NOT NULL,
            date date NOT NULL,
            receipt_img longblob NULL,
            payed BOOLEAN NOT NULL DEFAULT false,
            note varchar(255) DEFAULT NULL,
            user_id bigint(20) UNSIGNED NOT NULL,
            created_at timestamp NULL DEFAULT NULL,
            updated_at timestamp NULL DEFAULT NULL,
            deleted_at timestamp NULL DEFAULT NULL,
            PRIMARY KEY (expense_id),
            KEY expenses_category_id_foreign (category_id),
            KEY expenses_payment_id_foreign (payment_id),
            KEY expenses_user_id_foreign (user_id),
            CONSTRAINT expenses_category_id_foreign FOREIGN KEY (category_id) REFERENCES categories (id),
            CONSTRAINT expenses_payment_id_foreign FOREIGN KEY (payment_id) REFERENCES methods (id),
            CONSTRAINT expenses_user_id_foreign FOREIGN KEY (user_id) REFERENCES users (id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
        
        CREATE TABLE shared_expenses (
            id bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT,
            receiver_user_id bigint(20) UNSIGNED NOT NULL,
            sharer_user_id bigint(20) UNSIGNED NOT NULL,
            expense_id bigint(20) UNSIGNED NOT NULL,
            created_at timestamp NULL DEFAULT NULL,
            updated_at timestamp NULL DEFAULT NULL,
            deleted_at timestamp NULL DEFAULT NULL,
            PRIMARY KEY (id),
            KEY shared_expenses_receiver_user_id_foreign (receiver_user_id),
            KEY shared_expenses_sharer_user_id_foreign (sharer_user_id),
            KEY shared_expenses_expense_id_foreign (expense_id),
            CONSTRAINT shared_expenses_receiver_user_id_foreign FOREIGN KEY (receiver_user_id) REFERENCES users (id),
            CONSTRAINT shared_expenses_sharer_user_id_foreign FOREIGN KEY (sharer_user_id) REFERENCES users (id),
            CONSTRAINT shared_expenses_expense_id_foreign FOREIGN KEY (expense_id) REFERENCES expenses (expense_id),
            CONSTRAINT unique_users CHECK (receiver_user_id <> sharer_user_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
    ');
        
    $usersToInsert = [
        [
            'first_name' => 'Rafael',
            'last_name' => 'André',
            'email' => 'kromenz@expflow.com',
            'country' => 'Portugal',
            'birthdate' => '2003-08-19',
            'password' => 'rafael123',
            'avatar' => null,
            'admin' => true,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ],
        [
            'first_name' => 'Diogo',
            'last_name' => 'Pinheiro',
            'email' => 'pinheiro@expflow.com',
            'country' => 'Portugal',
            'birthdate' => '2003-12-11',
            'password' => 'pinheiro123',
            'avatar' => null,
            'admin' => true,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ],
        [
            'first_name' => 'root',
            'last_name' => 'root',
            'email' => 'root@root.com',
            'country' => 'Portugal',
            'birthdate' => '2003/08/19',
            'password' => 'root123',
            'avatar' => null,
            'admin' => true,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s')
        ]
    ];

    foreach ($usersToInsert as $userData) {
        $userData['password'] = password_hash($userData['password'], PASSWORD_DEFAULT);

        $sqlCreateUser = "INSERT INTO 
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

        $PDOStatementUser = $pdo->prepare($sqlCreateUser);

        $successUser = $PDOStatementUser->execute([
            ':first_name' => $userData['first_name'],
            ':last_name' => $userData['last_name'],
            ':password' => $userData['password'],
            ':avatar' => $userData['avatar'],
            ':email' => $userData['email'],
            ':country' => $userData['country'],
            ':birthdate' => $userData['birthdate'],
            ':admin' => $userData['admin'],
            ':created_at' => $userData['created_at'],
            ':updated_at' => $userData['updated_at']
        ]);

        if (!$successUser) {
            echo "Error adding user: " . implode(" - ", $PDOStatementUser->errorInfo()) . PHP_EOL;
        }
    }
    
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
        ['description' => 'None'],
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
