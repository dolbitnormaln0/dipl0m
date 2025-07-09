<div class="products">

    <?php if (!empty($products)): ?>
        <?php for($i=0;$i<count($products);$i++){ ?>
            <?php

            // Подготовка данных
            $rating = $product['average_rating'] ?? 0;
            $starsCount = min(5, ceil($rating)); // Округляем вверх, максимум 5
            $emptyStars = 5 - $starsCount;
            ?>

            <div class="product-card">
                <div class="product-name"><?= htmlspecialchars($product['nazvanie_tovara']) ?></div>

                <div class="photo">
                    <?php if (!empty($product['images_array'][0])): ?>
                        <img src="/app/pictures/<?= htmlspecialchars($product['images_array'][0]) ?>"
                             alt="<?= htmlspecialchars($product['nazvanie_tovara']) ?>">
                    <?php else: ?>
                        <img src="/path/to/default/image.jpg" alt="Нет изображения">
                    <?php endif; ?>
                </div>

                <div class="product-price"><?= number_format($product['cena'], 2, '.', ' ') ?> ₽</div>

                <div class="product-rating">
                    <span class="rating-stars" style="color: <?= getRatingColor($rating) ?>">
                        <?= str_repeat('★', $starsCount) ?>
                        <?= str_repeat('☆', $emptyStars) ?>
                    </span>
                    <span class="rating-value">(<?= number_format($rating, 1) ?>)</span>
                </div>

                <div class="product-stock">В наличии: <?= htmlspecialchars($product['kolichestvo_na_sklade']) ?> шт.</div>
                <div class="product-info">Бренд: <?= htmlspecialchars($product['brend_name']) ?></div>
                <div class="product-info">Категория: <?= htmlspecialchars($product['category_name']) ?></div>

                <a href="/product?id=<?= $product['tovar_id'] ?>" class="product-button">Подробнее</a>
            </div>
        <?php } ?>
    <?php else:?>
        <p>Товары не найдены</p>
    <?php endif; ?>
</div>