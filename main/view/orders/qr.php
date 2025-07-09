<div class="qr-container">
    <h1>QR-код заказа #<?= htmlspecialchars($orderId) ?></h1>
    <div class="qr-code">
        <img src="<?= htmlspecialchars($qrUrl) ?>"
             alt="QR-код заказа #<?= htmlspecialchars($orderId) ?>">
        <p>Покажите этот код сотруднику для получения заказа</p>
    </div>
    <div class="qr-info">
        <p><strong>Номер заказа:</strong> #<?= htmlspecialchars($orderId) ?></p>
        <p><strong>Статус:</strong> <?= $order['status'] == 1 ? 'Готов к выдаче' : 'В обработке' ?></p>
    </div>
    <a href="/profile" class="back-link">← Вернуться к заказам</a>
</div>
<style>
.qr-container {
max-width: 600px;
margin: 0 auto;
padding: 20px;
text-align: center;
}

.qr-code {
margin: 30px 0;
padding: 20px;
background: white;
border-radius: 8px;
display: inline-block;
}

.qr-code img {
width: 300px;
height: 300px;
}

.qr-info {
margin: 20px 0;
padding: 15px;
background: #f8f9fa;
border-radius: 8px;
text-align: left;
}

.back-link {
display: inline-block;
margin-top: 20px;
color: #007bff;
text-decoration: none;
}
</style>