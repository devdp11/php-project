<?php
@require_once __DIR__ . '/db/connection.php';

date_default_timezone_set('Europe/Lisbon');

$tablesToCheck = ['users', 'methods', 'categories', 'expenses', 'shared_expenses'];
$tablesExist = true;

foreach ($tablesToCheck as $table) {
    $tableExistQuery = "SHOW TABLES LIKE '$table'";
    $tableExistStatement = $pdo->query($tableExistQuery);

    if ($tableExistStatement->rowCount() == 0) {
        $tablesExist = false;
        break;
    }
}

if (!$tablesExist) {
    $tablesToDrop = ['shared_expenses', 'expenses', 'categories', 'methods', 'users'];

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

    $expensesToInsert = [];

    // Example data for three expenses
    $expenseDataList = [
        [
            'category_id' => 1,
            'description' => 'Expense 1',
            'payment_id' => 2,
            'amount' => 50.00,
            'date' => '2023-12-13',
            'receipt_img' => null,
            'payed' => false,
            'note' => 'Note for expense 1',
            'user_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'deleted_at' => null,
        ],
        [
            'category_id' => 2,
            'description' => 'Expense 2',
            'payment_id' => 1,
            'amount' => 75.50,
            'date' => '2023-12-14',
            'receipt_img' => null,
            'payed' => true,
            'note' => 'Note for expense 2',
            'user_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'deleted_at' => null,
        ],
        [
            'category_id' => 3,
            'description' => 'Expense 3',
            'payment_id' => 3,
            'amount' => 30.25,
            'date' => '2023-12-15',
            'receipt_img' => null,
            'payed' => false,
            'note' => 'Note for expense 3',
            'user_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'deleted_at' => null,
        ],
        [
            'category_id' => 4,
            'description' => 'Expense 4',
            'payment_id' => 4,
            'amount' => 40.25,
            'date' => '2023-11-15',
            'receipt_img' => null,
            'payed' => false,
            'note' => 'Note for expense 4',
            'user_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'deleted_at' => null,
        ],
        [
            'category_id' => 5,
            'description' => 'Expense 5',
            'payment_id' => 5,
            'amount' => 50.25,
            'date' => '2023-10-15',
            'receipt_img' => null,
            'payed' => false,
            'note' => 'Note for expense 5',
            'user_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'deleted_at' => null,
        ],
        [
            'category_id' => 6,
            'description' => 'Expense 6',
            'payment_id' => 6,
            'amount' => 60.25,
            'date' => '2024-01-15',
            'receipt_img' => null,
            'payed' => false,
            'note' => 'Note for expense 6',
            'user_id' => 1,
            'created_at' => date('Y-m-d H:i:s'),
            'updated_at' => date('Y-m-d H:i:s'),
            'deleted_at' => null,
        ]
    ];

    foreach ($expenseDataList as $expenseData) {
        $expensesToInsert[] = $expenseData;
    }

    foreach ($expensesToInsert as $expenseData) {
        $createQuery = "INSERT INTO expenses (
                            category_id,
                            description,
                            payment_id,
                            amount,
                            date,
                            receipt_img,
                            payed,
                            note,
                            user_id,
                            created_at,
                            updated_at,
                            deleted_at
                        ) VALUES (
                            :category_id,
                            :description,
                            :payment_id,
                            :amount,
                            :date,
                            :receipt_img,
                            :payed,
                            :note,
                            :user_id,
                            :created_at,
                            :updated_at,
                            :deleted_at
                        )";

        $statement = $pdo->prepare($createQuery);

        $success = $statement->execute([
            ':category_id' => $expenseData['category_id'],
            ':description' => $expenseData['description'],
            ':payment_id' => $expenseData['payment_id'],
            ':amount' => $expenseData['amount'],
            ':date' => $expenseData['date'],
            ':receipt_img' => $expenseData['receipt_img'],
            ':payed' => $expenseData['payed'],
            ':note' => $expenseData['note'],
            ':user_id' => $expenseData['user_id'],
            ':created_at' => $expenseData['created_at'],
            ':updated_at' => $expenseData['updated_at'],
            ':deleted_at' => $expenseData['deleted_at'],
        ]);

        if (!$success) {
            echo "Error adding expense: " . implode(" - ", $statement->errorInfo()) . PHP_EOL;
        }
    }

    $sharedExpensesToInsert = [
        ['receiver_user_id' => 1, 'sharer_user_id' => 2, 'expense_id' => 1],
        ['receiver_user_id' => 3, 'sharer_user_id' => 2, 'expense_id' => 2],
    ];
    
    foreach ($sharedExpensesToInsert as $sharedExpenseData) {
        $createQuery = "INSERT INTO shared_expenses (receiver_user_id, sharer_user_id, expense_id, created_at, updated_at) 
                        VALUES (:receiver_user_id, :sharer_user_id, :expense_id, NOW(), NOW())";
    
        $statement = $pdo->prepare($createQuery);
    
        $success = $statement->execute([
            ':receiver_user_id' => $sharedExpenseData['receiver_user_id'],
            ':sharer_user_id' => $sharedExpenseData['sharer_user_id'],
            ':expense_id' => $sharedExpenseData['expense_id'],
        ]);
    
        if (!$success) {
            echo "Error adding a shared expense: " . implode(" - ", $statement->errorInfo()) . PHP_EOL;
        }
    }
}
?>