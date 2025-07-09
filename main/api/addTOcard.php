<?php
session_start();
header('Content-Type: application/json');

if (!$_SESSION['user_id']) {
    echo json_encode(['success' => false, 'error' => 'Требуется авторизация']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$productId = $data['product_id'] ?? null;
$quantity = $data['quantity'] ?? 1;

if (!$productId) {
    echo json_encode(['success' => false, 'error' => 'Не указан ID товара']);
    exit;
}

if (!isset($_SESSION['card'])) {
    $_SESSION['card'] = [];
}

if (!key_exists($productId, $_SESSION['card'])) {
    $_SESSION['card'][$productId] = $productId;
    $_SESSION['card'][$productId] = ['quantity' => $quantity];

    echo json_encode(['success' => true]);
} else {
    echo json_encode(['success' => false, 'error' => 'Товар уже в корзине']);
}
