<?php
return [
    'host' => 'db:3306',
    'dbname' => 'my_db_test1',
    'username' => 'root',
    'password' => 'qwe123',
    'charset' => 'utf8mb4',
    'options' => [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]
];