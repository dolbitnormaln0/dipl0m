<div class="product-detail">
    <div class="product-images">
        <div class="carousel">
            <button class="prev" onclick="changeImage(-1)">&#10094;</button>
            <img id="carouselImage" src="/app/pictures/<?php echo htmlspecialchars(trim($pathtoData[0])); ?>" alt="<?php echo htmlspecialchars($title); ?>">
            <button class="next" onclick="changeImage(1)">&#10095;</button>
        </div>
        <div class="image-thumbnails">
            <?php foreach ($pathtoData as $index => $image): ?>
                <img src="/app/pictures/<?php echo htmlspecialchars(trim($image)); ?>" alt="<?php echo htmlspecialchars($title); ?>" onclick="setImage(<?php echo $index; ?>)">
            <?php endforeach; ?>
        </div>
    </div>

    <div class="product-info">
        <h1><?= htmlspecialchars($product['nazvanie_tovara']) ?></h1>

        <div class="product-meta">
            <div class="product-sku">Артикул: <?= htmlspecialchars($product['tovar_id']) ?></div>
        </div>

        <div class="product-price">
            <span class="current-price"><?= number_format($product['cena'], 2, '.', ' ') ?> ₽</span>
            <?php if (isset($product['old_price'])): ?>
                <span class="old-price"><?= number_format($product['old_price'], 2, '.', ' ') ?> ₽</span>
            <?php endif; ?>
        </div>

        <div class="product-stock <?= $product['kolichestvo_na_sklade'] > 0 ? 'in-stock' : 'out-of-stock' ?>">
            <?= $product['kolichestvo_na_sklade'] > 0 ? 'В наличии' : 'Нет в наличии' ?>
            <?php if ($product['kolichestvo_na_sklade'] > 0): ?>
                <span class="stock-count">(<?= $product['kolichestvo_na_sklade'] ?> шт.)</span>
            <?php endif; ?>
        </div>

        <div class="product-actions">
            <div class="quantity-selector">
                <button class="quantity-btn minus">-</button>
                <input type="number" id="quantity-input" value="1" min="1" max="<?= $product['kolichestvo_na_sklade'] ?>">
                <button class="quantity-btn plus">+</button>
            </div>
            <button class="add-to-cart" id="add-to-cart-btn"
                <?= $product['kolichestvo_na_sklade'] <= 0 ? 'disabled' : '' ?>
                    data-product-id="<?= $product['tovar_id'] ?>">
                Добавить в корзину
            </button>
            <div id="cart-message" style="display:none; margin-left:10px; color:green;"></div>
        </div>

        <div class="add-review-form">
            <h3>Оставить отзыв</h3>

            <?php if ( isset($_SESSION['user_id'])): ?>
                <form id="new-review-form" method="post" action="/products/add-review">
                    <input type="hidden" name="product_id" value="<?= $product['tovar_id'] ?>">
                    <input type="hidden" name="userid" value="<?= $userId; ?>">

                    <div class="form-group">
                        <label>Ваша оценка:</label>
                        <div class="rating-select">
                            <div class="stars-rating">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" id="rate-<?= $i ?>" name="rating" value="<?= $i ?>" <?= $i == 5 ? 'checked' : '' ?>>
                                    <label for="rate-<?= $i ?>" title="<?= $i ?> звезд">★</label>
                                <?php endfor; ?>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="review-text">Текст отзыва:</label>
                        <textarea id="review-text" name="review_text" rows="5" required
                                  placeholder="Поделитесь вашим мнением о товаре..."></textarea>
                    </div>

                    <button type="submit" class="submit-review-btn">
                        <i class="icon-send"></i> Отправить отзыв
                    </button>
                </form>
            <?php else: ?>
                <div class="auth-required">
                    <p>Чтобы оставить отзыв, пожалуйста <a href="/login">войдите</a> или <a href="/register">зарегистрируйтесь</a>.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

        <div class="specs-reviews-container">
            <div class="product-specs">
                <h2>Характеристики</h2>
                <div class="specs-list">
                    <?php if (!empty($params)): ?>
                        <?php foreach ($params as $char): ?>
                            <div class="spec-row">
                                <div class="spec-name"><?= htmlspecialchars($char['attribute_name']) ?></div>
                                <div class="spec-value">
                                    <?php if ($char['attribute_type'] === 'boolean'): ?>
                                        <?= $char['attribute_value'] ? 'Да' : 'Нет' ?>
                                    <?php else: ?>
                                        <?= htmlspecialchars($char['attribute_value']) ?>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="no-specs">Характеристики отсутствуют</p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="product-reviews">
                <h2>Отзывы</h2>
                <?php if (!empty($product['comments'])): ?>
                    <div class="reviews-stats">
                        <div class="average-rating">
                            Средняя оценка:
                            <span class="rating-value">
                                <?= number_format(array_sum(array_column($product['comments'], 'stars')) / count($product['comments']), 1) ?>
                            </span>
                            из 5
                        </div>
                    </div>

                    <div class="reviews-list">
                        <?php foreach ($product['comments'] as $comment): ?>
                            <div class="review-card">
                                <div class="review-header">
                                    <div class="review-author"><?= htmlspecialchars($comment['user_name']) ?></div>
                                    <div class="review-rating">
                                        <?php for ($i = 1; $i <= 5; $i++): ?>
                                            <span class="star <?= $i <= $comment['stars'] ? 'filled' : '' ?>">★</span>
                                        <?php endfor; ?>
                                    </div>
                                </div>
                                <div class="review-content">
                                    <?= nl2br(htmlspecialchars($comment['commentcontent'])) ?>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <div class="no-reviews">
                        <p>Пока нет отзывов о этом товаре.</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

    </div>

    </div>
<script>
    document.getElementById('add-to-cart-btn').addEventListener('click', function() {
        const productId = this.getAttribute('data-product-id');
        const quantity = document.getElementById('quantity-input').value;

        fetch('/api/addTOcard.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                product_id: productId,
                quantity: quantity
            }),
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showCartMessage('Товар добавлен в корзину!');
                } else {
                    showCartMessage('Ошибка: ' + data.error, 'red');
                }
            })
            .catch(error => {
                showCartMessage('Ошибка сети', 'red');
                console.error('Error:', error);
            });
    });
</script>

<style>
    .reviews-layout {
        display: flex;
        gap: 30px;
        margin-top: 20px;
    }

    .reviews-list-container {
        flex: 2;
    }

    .add-review-form {
        flex: 1;
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        border: 1px solid #e0e0e0;
        position: sticky;
        top: 20px;
        align-self: flex-start;
    }

    /* Форма отзыва */
    .add-review-form h3 {
        margin-top: 0;
        color: #333;
        border-bottom: 1px solid #eee;
        padding-bottom: 10px;
    }

    .stars-rating {
        display: flex;
        flex-direction: row-reverse;
        justify-content: flex-end;
    }

    .stars-rating input {
        display: none;
    }

    .stars-rating label {
        font-size: 24px;
        color: #ccc;
        cursor: pointer;
        transition: color 0.2s;
    }

    .stars-rating input:checked ~ label,
    .stars-rating label:hover,
    .stars-rating label:hover ~ label {
        color: #ffc107;
    }

    #review-text {
        width: 100%;
        padding: 12px;
        border: 1px solid #ddd;
        border-radius: 4px;
        min-height: 120px;
        resize: vertical;
        font-family: inherit;
    }

    .submit-review-btn {
        background: #28a745;
        color: white;
        border: none;
        padding: 10px 20px;
        border-radius: 4px;
        cursor: pointer;
        font-size: 16px;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: background 0.3s;
    }

    .submit-review-btn:hover {
        background: #218838;
    }

    .auth-required {
        background: #fff8e1;
        padding: 15px;
        border-radius: 4px;
        text-align: center;
    }

    .auth-required a {
        color: #1976d2;
        text-decoration: none;
    }

    /* Адаптивность */
    @media (max-width: 768px) {
        .reviews-layout {
            flex-direction: column;
        }

        .add-review-form {
            order: -1;
            position: static;
            margin-bottom: 20px;
        }
    }
    /* Основные стили */
    .product-detail {
        display: flex;
        gap: 40px;
        margin-top: 30px;
    }

    /* Стили для изображений */
    .product-images {
        flex: 1;
        position: relative;
    }
    .carousel {
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
    }
    .carousel img {
        max-width: 80%;
        height: auto;
        border: 1px solid #ccc;
    }
    .prev, .next {
        cursor: pointer;
        background-color: rgba(0,0,0,0.5);
        color: white;
        border: none;
        padding: 10px;
        margin: 0 10px;
        font-size: 24px;
    }
    .image-thumbnails {
        display: flex;
        justify-content: center;
        flex-wrap: wrap;
        gap: 5px;
        margin-top: 10px;
    }
    .image-thumbnails img {
        width: 50px;
        height: auto;
        cursor: pointer;
        opacity: 0.6;
        transition: opacity 0.3s;
    }
    .image-thumbnails img:hover {
        opacity: 1;
    }

    /* Стили информации о товаре */
    .product-info {
        flex: 1;
    }
    .product-price {
        font-size: 24px;
        margin: 20px 0;
    }
    .current-price {
        color: #e74c3c;
        font-weight: bold;
    }
    .old-price {
        text-decoration: line-through;
        color: #999;
        margin-left: 10px;
        font-size: 18px;
    }
    .product-stock {
        padding: 8px 12px;
        border-radius: 4px;
        display: inline-block;
    }
    .in-stock {
        background: #e8f5e9;
        color: #27ae60;
    }
    .out-of-stock {
        background: #ffebee;
        color: #e74c3c;
    }
    .stock-count {
        font-weight: bold;
    }

    /* Стили для кнопок */
    .product-actions {
        display: flex;
        gap: 15px;
        margin: 25px 0;
        align-items: center;
    }
    .quantity-selector {
        display: flex;
        align-items: center;
    }
    .quantity-btn {
        width: 30px;
        height: 30px;
        background: #f1f1f1;
        border: none;
        cursor: pointer;
    }
    .quantity-selector input {
        width: 50px;
        height: 30px;
        text-align: center;
        margin: 0 5px;
        border: 1px solid #ddd;
    }
    .add-to-cart {
        padding: 10px 20px;
        background: #3498db;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    /* Стили для характеристик и отзывов */
    .specs-reviews-container {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 30px;
        margin-top: 40px;
    }
    .product-specs, .product-reviews {
        background: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.05);
    }
    .specs-list {
        display: grid;
        grid-template-columns: 1fr;
        gap: 12px;
        margin-top: 15px;
    }
    .spec-row {
        display: flex;
        justify-content: space-between;
        padding: 10px 0;
        border-bottom: 1px dashed #eee;
    }
    .spec-name {
        color: #666;
        font-weight: 500;
    }
    .spec-value {
        color: #333;
    }
    .no-specs {
        color: #999;
        text-align: center;
        padding: 20px;
    }

    /* Стили отзывов */
    .reviews-stats {
        margin: 15px 0;
    }
    .average-rating {
        font-size: 16px;
    }
    .rating-value {
        font-weight: bold;
        color: #e67e22;
    }
    .reviews-list {
        display: flex;
        flex-direction: column;
        gap: 20px;
        margin-top: 20px;
    }
    .review-card {
        background: #f9f9f9;
        padding: 15px;
        border-radius: 8px;
    }
    .review-header {
        display: flex;
        justify-content: space-between;
        margin-bottom: 10px;
    }
    .review-author {
        font-weight: bold;
    }
    .review-rating {
        color: #ffc107;
    }
    .star.filled {
        color: #ffc107;
    }
    .star:not(.filled) {
        color: #ddd;
    }
    .no-reviews {
        text-align: center;
        padding: 20px;
        color: #777;
    }

    /* Адаптивность */
    @media (max-width: 768px) {
        .product-detail {
            flex-direction: column;
            gap: 20px;
        }
        .specs-reviews-container {
            grid-template-columns: 1fr;
        }
        .carousel img {
            max-width: 100%;
        }
    }
</style>

<script>
    let currentIndex = 0;
    const images = <?php echo json_encode($pathtoData); ?>;

    function changeImage(direction) {
        currentIndex += direction;
        if (currentIndex < 0) currentIndex = images.length - 1;
        if (currentIndex >= images.length) currentIndex = 0;
        updateImage();
    }

    function setImage(index) {
        currentIndex = index;
        updateImage();
    }

    function updateImage() {
        document.getElementById('carouselImage').src = '/app/pictures/' + images[currentIndex].trim();
    }
</script>
<script>

    function showCartMessage(message, color = 'green') {
        const messageEl = document.getElementById('cart-message');
        messageEl.textContent = message;
        messageEl.style.color = color;
        messageEl.style.display = 'block';

        setTimeout(() => {
            messageEl.style.display = 'none';
        }, 3000);
    }

    document.querySelector('.quantity-btn.plus').addEventListener('click', function() {
        const input = document.getElementById('quantity-input');
        if (parseInt(input.value) < parseInt(input.max/3)) {
            input.value = parseInt(input.value) + 1;
        }
    });

    document.querySelector('.quantity-btn.minus').addEventListener('click', function() {
        const input = document.getElementById('quantity-input');
        if (parseInt(input.value) > parseInt(input.min)) {
            input.value = parseInt(input.value) - 1;
        }
    });
</script>
<div class="products">
    <?php if (!empty($productAdvice)): ?>
        <?php foreach ($productAdvice as $product): ?>
            <?php
            $rating = $product['average_rating'] ?? 0;
            $starsCount = min(5, ceil($rating));
            $emptyStars = 5 - $starsCount;
            ?>

            <div class="product-card">
                <div class="product-name"><?= htmlspecialchars($product['nazvanie_tovara']) ?></div>

                <div class="photo">
                    <?php if (!empty($product['images_array'][0])): ?>
                        <img src="/app/pictures/<?= htmlspecialchars($product['images_array'][0]) ?>"
                             alt="<?= htmlspecialchars($product['nazvanie_tovara']) ?>">
                    <?php else: ?>
                        <div class="no-image">Нет изображения</div>
                    <?php endif; ?>
                </div>

                <div class="product-price"><?= number_format($product['cena'], 2, '.', ' ') ?> ₽</div>
                <div class="product-stock">В наличии: <?= htmlspecialchars($product['kolichestvo_na_sklade']) ?> шт.</div>
                <div class="product-info">Бренд: <?= htmlspecialchars($product['brend_name']) ?></div>
                <div class="product-info">Категория: <?= htmlspecialchars($product['category_name']) ?></div>

                <!-- Кнопка "Подробнее" теперь вне блока с фото -->
                <a href="/product?id=<?= $product['tovar_id'] ?>" class="product-button">Подробнее</a>
            </div>
        <?php endforeach; ?>
    <?php else: ?>
        <p>Товары не найдены</p>
    <?php endif; ?>
</div>
    <style>   .products {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            justify-content: space-between;
        }

        .product-card {
            flex: 1 1 calc(25% - 20px);
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
            overflow: hidden;
            background: #fff;
            padding: 15px;
            display: flex;
            flex-direction: column;
        }

        .photo {
            height: 180px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #f5f5f5;
            margin-bottom: 10px;
        }

        .photo img {
            max-width: 100%;
            max-height: 100%;
            object-fit: contain;
        }

        .no-image {
            color: #999;
            font-size: 14px;
        }

        .product-name {
            font-weight: bold;
            margin-bottom: 10px;
            font-size: 16px;
        }

        .product-price {
            color: #e74c3c;
            font-weight: bold;
            font-size: 18px;
            margin: 5px 0;
        }

        .product-stock {
            color: #27ae60;
            font-size: 14px;
            margin: 3px 0;
        }

        .product-info {
            color: #666;
            font-size: 14px;
            margin: 3px 0;
        }

        .product-button {
            display: inline-block;
            margin-top: 15px;
            padding: 8px 15px;
            background: #3498db;
            color: white;
            text-align: center;
            text-decoration: none;
            border-radius: 4px;
            font-size: 14px;
        }

        @media (max-width: 768px) {
            .product-card {
                flex: 1 1 calc(50% - 15px);
            }
        }</style>
