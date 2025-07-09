<?php
header('Content-Type: application/json');

$dbConfig = [
    'host' => 'db:3306',
    'dbname' => 'my_db_test1',
    'username' => 'root',
    'password' => 'qwe123',
    'charset' => 'utf8mb4'
];

try {
    $dsn = "mysql:host={$dbConfig['host']};dbname={$dbConfig['dbname']};charset={$dbConfig['charset']}";
    $pdo = new PDO($dsn, $dbConfig['username'], $dbConfig['password'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    $data = json_decode(file_get_contents('php://input'), true);
    $firstName = $data['first_name'] ?? '';
    $email = $data['email'] ?? '';
    $password = $data['password'] ?? '';

    // Проверяем, существует ли пользователь
    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $stmt->execute([$email]);

    if ($stmt->fetch()) {
        echo json_encode(['success' => false, 'message' => 'Пользователь с таким email уже существует']);
        exit;
    }

    // В реальном проекте нужно использовать password_hash()
    $stmt = $pdo->prepare("INSERT INTO users (first_name, email, password) VALUES (?, ?, ?)");
    $stmt->execute([$firstName, $email, $password]);

    echo json_encode(['success' => true]);

} catch (PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Ошибка базы данных: ' . $e->getMessage()]);
}
?>