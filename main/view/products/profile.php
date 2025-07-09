<div class="profile-container">
    <h1>Мои заказы</h1>

    <?php if (!empty($orders)): ?>
        <div class="orders-list">
            <?php foreach ($orders as $order): ?>
                <div class="order-card">
                    <div class="order-header">
                        <div>

                            <div class="order-status <?= $order['status'] == 1 ? 'ready' : 'preparing' ?>">
                                <?= $order['status'] == 1 ? 'Заказ готов к выдаче' : 'Заказ еще готовится' ?>
                            </div>
                        </div>
                        <div class="order-id">Заказ #<?= htmlspecialchars($order['id']) ?></div>
                        <div class="order-date">
                            <?= date('d.m.Y H:i', strtotime($order['created_at'])) ?>
                        </div>
                        <div class="order-total">
                            <?= number_format($order['total_price'], 0, '', ' ') ?> ₽
                        </div>
                    </div>

                    <div class="order-items">
                        <?php foreach ($order['items'] as $item): ?>
                            <div class="order-item">
                                <div class="item-info">
                                    <div class="item-name"><?= htmlspecialchars($item['nazvanie_tovara']) ?></div>
                                    <div class="item-details">
                                        <span>Цена: <?= number_format($item['price'], 0, '', ' ') ?> ₽</span>
                                        <span>× <?= $item['quantity'] ?></span>
                                        <span>= <?= number_format($item['price'] * $item['quantity'], 0, '', ' ') ?> ₽</span>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="order-footer">
                        <form action="/order/qr" method="POST" target="_blank">
                            <input type="hidden" name="order_id" value="<?= (int)$order['id'] ?>">
                            <button type="submit" class="qr-button">
                                Получить QR-код для выдачи
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    <?php else: ?>
        <div class="no-orders">
            <p>У вас пока нет заказов</p>
            <a href="/products" class="btn">Перейти в каталог</a>
        </div>
    <?php endif; ?>
</div>
<style>
    .qr-button {
        display: inline-block;
        padding: 8px 16px;
        background-color: #4CAF50;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        margin-top: 15px;
        transition: background-color 0.3s;
    }

    .qr-button:hover {
        background-color: #45a049;
    }

    .order-footer {
        margin-top: 15px;
        padding-top: 15px;
        border-top: 1px solid #eee;
        text-align: center;
    }
    .order-status {
        padding: 4px 8px;
        border-radius: 4px;
        font-size: 14px;
        margin-top: 5px;
        display: inline-block;
    }

    .order-status.ready {
        background-color: #d4edda;
        color: #155724;
    }

    .order-status.preparing {
        background-color: #f8d7da;
        color: #721c24;
    }
    .profile-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 20px;
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

    .order-id {
        font-weight: bold;
        color: #007bff;
    }

    .order-date {
        color: #666;
    }

    .order-total {
        font-weight: bold;
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
    }

    .item-details {
        display: flex;
        gap: 15px;
        color: #666;
        font-size: 14px;
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
    }</style>