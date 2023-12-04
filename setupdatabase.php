<?php
#EASY DATABASE SETUP
require __DIR__ . '/infra/db/connection.php';

#DROP TABLE
$pdo->exec('DROP TABLE IF EXISTS users;');

echo 'table users deleted!' . PHP_EOL;

#CREATE TABLE
$pdo->exec(
    'CREATE TABLE users (
    id INTEGER PRIMARY KEY AUTO_INCREMENT, 
    name varchar(50)	, 
    lastname varchar(50)	, 
    phoneNumber varchar(50)	, 
    email varchar(50)	 NOT NULL, 
    foto varchar(50)	 NULL, 
    administrator bit, 
    password varchar(200)	);'
);

echo 'Tabela users created!' . PHP_EOL;

#DEFAULT USER TO ADD
$user = [
    'name' => 'Marcelo',
    'lastname' => 'Antunes Fernandes',
    'phoneNumber' => '987654321',
    'email' => 'fernandesmarcelo@estg.ipvc.pt',
    'foto' => null,
    'administrator' => true,
    'password' => '123456'
];

#HASH PWD
$user['password'] = password_hash($user['password'], PASSWORD_DEFAULT);

#INSERT USER
$sqlCreate = "INSERT INTO 
    users (
        name, 
        lastname, 
        phoneNumber, 
        email, 
        foto, 
        administrator, 
        password) 
    VALUES (
        :name, 
        :lastname, 
        :phoneNumber, 
        :email, 
        :foto, 
        :administrator, 
        :password
    )";

#PREPARE QUERY
$PDOStatement = $GLOBALS['pdo']->prepare($sqlCreate);

#EXECUTE
$success = $PDOStatement->execute([
    ':name' => $user['name'],
    ':lastname' => $user['lastname'],
    ':phoneNumber' => $user['phoneNumber'],
    ':email' => $user['email'],
    ':foto' => $user['foto'],
    ':administrator' => $user['administrator'],
    ':password' => $user['password']
]);

echo 'Default user created!';