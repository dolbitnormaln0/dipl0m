<div class="profile-container">
    <h1>Моя корзина</h1>

    <?php if (!empty($cartItems)): ?>
        <div class="orders-list">
            <div class="order-card">
                <div class="order-header">
                    <div class="order-status preparing">
                        Товары в корзине
                    </div>
                    <div class="order-id">Товаров: <?= count($cartItems) ?></div>
                    <div class="order-total">
                        <?= number_format(array_sum(array_column($cartItems, 'total_price')), 0, '', ' ') ?> ₽
                    </div>
                </div>

                <div class="order-items">
                    <?php foreach ($cartItems as $item): ?>
                        <div class="order-item">
                            <div class="item-info">
                                <div class="item-name"><?= htmlspecialchars($item['nazvanie_tovara']) ?></div>
                                <div class="item-details">
                                    <span>Цена: <?= number_format($item['price'], 0, '', ' ') ?> ₽</span>
                                    <span>× <?= $item['quantity'] ?></span>
                                    <span>= <?= number_format($item['total_price'], 0, '', ' ') ?> ₽</span>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>

                <div class="order-footer">
                    <form action="/order/create" method="POST">
                        <button type="submit" class="qr-button">
                            Оформить заказ
                        </button>
                    </form>
                </div>
            </div>
        </div>
    <?php else: ?>
        <div class="no-orders">
            <p>Ваша корзина пуста</p>
            <a href="/products" class="btn">Перейти в каталог</a>
        </div>
    <?php endif; ?>
</div>

<style>
    .profile-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
        font-family: Arial, sans-serif;
    }

    .orders-list {
        display: grid;
        gap: 30px;
    }

    .order-card {
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        overflow: hidden;
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 15px 20px;
        background: #f8f9fa;
        border-bottom: 1px solid #eee;
    }

    .order-status {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 14px;
        display: inline-block;
    }

    .order-status.preparing {
        background-color: #f8d7da;
        color: #721c24;
    }

    .order-id {
        font-weight: bold;
        color: #007bff;
    }

    .order-total {
        font-weight: bold;
        font-size: 18px;
    }

    .order-items {
        padding: 15px;
    }

    .order-item {
        padding: 15px 0;
        border-bottom: 1px solid #f0f0f0;
    }

    .order-item:last-child {
        border-bottom: none;
    }

    .item-name {
        font-weight: 500;
        margin-bottom: 5px;
        font-size: 16px;
    }

    .item-details {
        display: flex;
        gap: 15px;
        color: #666;
        font-size: 14px;
    }

    .order-footer {
        margin-top: 15px;
        padding: 15px;
        border-top: 1px solid #eee;
        text-align: center;
    }

    .qr-button {
        display: inline-block;
        padding: 10px 20px;
        background-color: #4CAF50;
        color: white;
        text-decoration: none;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        transition: background-color 0.3s;
    }

    .qr-button:hover {
        background-color: #45a049;
    }

    .no-orders {
        text-align: center;
        padding: 40px;
        background: white;
        border-radius: 8px;
        box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    }

    .no-orders p {
        margin-bottom: 20px;
        font-size: 18px;
        color: #666;
    }

    .btn {
        display: inline-block;
        padding: 10px 20px;
        background: #007bff;
        color: white;
        border-radius: 4px;
        text-decoration: none;
        transition: background 0.3s;
    }

    .btn:hover {
        background: #0069d9;
    }
</style>