<h1>Каталог товаров</h1>
<?php $currentPage = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1; ?>
<div class="header-controls">
    <div class="total-products">Всего товаров: <?= htmlspecialchars($productcount) ?></div>

    <div class="sort-dropdown">
        <form method="get" action="">
            <select name="sortField" onchange="this.form.submit()">
                <option value="">Сортировать по...</option>
                <option value="t.cena" <?= isset($_GET['sortField']) && $_GET['sortField'] == 't.cena' ? 'selected' : '' ?>>Цене</option>
                <option value="t.average_rating" <?= isset($_GET['sortField']) && $_GET['sortField'] == 't.average_rating' ? 'selected' : '' ?>>Рейтингу</option>
                <option value="t.kolichestvo_na_sklade" <?= isset($_GET['sortField']) && $_GET['sortField'] == 't.kolichestvo_na_sklade' ? 'selected' : '' ?>>Наличию на складе</option>
            </select>

            <!-- Сохраняем другие GET-параметры -->
            <?php foreach ($_GET as $key => $value): ?>
                <?php if ($key !== 'sortField'): ?>
                    <?php if (is_array($value)): ?>
                        <?php foreach ($value as $item): ?>
                            <input type="hidden" name="<?= htmlspecialchars($key) ?>[]" value="<?= htmlspecialchars($item) ?>">
                        <?php endforeach; ?>
                    <?php else: ?>
                        <input type="hidden" name="<?= htmlspecialchars($key) ?>" value="<?= htmlspecialchars($value) ?>">
                    <?php endif; ?>
                <?php endif; ?>
            <?php endforeach; ?>
        </form>
    </div>
</div>

<?php if (!isset($_GET['string'])): ?>
    <button id="toggle-filters" class="toggle-filters-btn">
        <svg class="filter-icon" width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M4 21v-7M4 10V3M12 21v-9M12 8V3M20 21v-5M20 12V3M1 10h6M9 8h6M17 12h6"></path>
        </svg>
        <span class="toggle-text">Фильтры</span>
        <svg class="toggle-icon" width="12" height="12" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
            <path d="M6 9l6 6 6-6"></path>
        </svg>
    </button>
<?php endif; ?>

<!-- Блок фильтров -->
<div class="filters-section" id="filters-section">
    <h3>Фильтры</h3>
    <form method="get" action="/products" id="category-filter-form">
        <input type="hidden" name="page" value="1">

        <div class="filter-group">
            <h4>Категория</h4>
            <select name="idCat" onchange="submitCategoryFilter(this.value)">
                <option value="">Выберите категорию...</option>
                <?php foreach ($spec as $category): ?>
                    <option value="<?= htmlspecialchars($category['category_id']) ?>"
                        <?= (isset($_GET['idCat']) && $_GET['idCat'] == $category['category_id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($category['category_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <!-- Умные фильтры по атрибутам -->
        <div class="filter-group">
            <h4>Фильтры по характеристикам</h4>
            <?php if (!empty($attributeFiltersData)): ?>
                <?php foreach ($attributeFiltersData as $attributeId => $attributeData):
                    $attributeName = $attributeData['name'];
                    $attributeType = $attributeData['type'];
                    $values = $attributeData['values'];
                    ?>
                    <div class="smart-filter">
                        <h5><?= htmlspecialchars($attributeName) ?></h5>
                        <input type="hidden" name="attr_ids[]" value="<?= $attributeId ?>">

                        <?php if ($attributeType == 'number' && isset($values['min']) && isset($values['max'])): ?>
                            <!-- Числовой диапазон -->
                            <div class="range-inputs">
                                <input type="number" name="attr_<?= $attributeId ?>_min"
                                       placeholder="От" min="<?= $values['min'] ?>" max="<?= $values['max'] ?>"
                                       value="<?= $_GET['attr_'.$attributeId.'_min'] ?? $values['min'] ?>">
                                <span>-</span>
                                <input type="number" name="attr_<?= $attributeId ?>_max"
                                       placeholder="До" min="<?= $values['min'] ?>" max="<?= $values['max'] ?>"
                                       value="<?= $_GET['attr_'.$attributeId.'_max'] ?? $values['max'] ?>">
                            </div>

                        <?php elseif ($attributeType == 'text' && is_array($values)): ?>
                            <!-- Чекбоксы для текстовых значений -->
                            <div class="checkbox-options">
                                <?php foreach ($values as $value): ?>
                                    <label>
                                        <input type="checkbox" name="attr_<?= $attributeId ?>[]"
                                               value="<?= htmlspecialchars($value) ?>"
                                            <?= isset($_GET['attr_'.$attributeId]) &&
                                            is_array($_GET['attr_'.$attributeId]) &&
                                            in_array($value, $_GET['attr_'.$attributeId]) ? 'checked' : '' ?>>
                                        <?= htmlspecialchars($value) ?>
                                    </label>
                                <?php endforeach; ?>
                            </div>

                        <?php elseif ($attributeType == 'boolean'): ?>
                            <!-- Радиокнопки для boolean -->
                            <div class="radio-options">
                                <label>
                                    <input type="radio" name="attr_<?= $attributeId ?>_bool" value="1"
                                        <?= isset($_GET['attr_'.$attributeId.'_bool']) && $_GET['attr_'.$attributeId.'_bool'] == '1' ? 'checked' : '' ?>>
                                    Да
                                </label>
                                <label>
                                    <input type="radio" name="attr_<?= $attributeId ?>_bool" value="0"
                                        <?= isset($_GET['attr_'.$attributeId.'_bool']) && $_GET['attr_'.$attributeId.'_bool'] == '0' ? 'checked' : '' ?>>
                                    Нет
                                </label>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Нет доступных фильтров для этой категории</p>
            <?php endif; ?>
        </div>

        <!-- Сохранение других параметров -->
        <?php if (isset($_GET['sortField'])): ?>
            <input type="hidden" name="sortField" value="<?= htmlspecialchars($_GET['sortField']) ?>">
        <?php endif; ?>
        <?php if (isset($_GET['brand'])): ?>
            <input type="hidden" name="brand" value="<?= htmlspecialchars($_GET['brand']) ?>">
        <?php endif; ?>

        <div class="filter-actions">
            <button type="submit" class="btn btn-primary">Применить фильтры</button>
            <a href="/products" class="btn btn-secondary">Сбросить все</a>
        </div>
    </form>
</div>

<div class="products">
    <?php if (!empty($products)): ?>
        <?php foreach ($products as $product): ?>
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
        <?php endforeach; ?>
    <?php else: ?>
        <p>Товары не найдены</p>
    <?php endif; ?>
</div>
    <?php if (count($products) > 0): ?>
        <div class="pagination">
            <?php

            $queryParams = $_GET;
            unset($queryParams['page']);
            $queryString = http_build_query($queryParams);

            $itemsPerPage = 9;
            $totalPages = ceil($productcount / $itemsPerPage);
            $maxVisiblePages = 5;


            $startPage = max(1, $currentPage - floor($maxVisiblePages / 2));
            $endPage = min($totalPages, $startPage + $maxVisiblePages - 1);


            if ($endPage - $startPage + 1 < $maxVisiblePages) {
                $startPage = max(1, $endPage - $maxVisiblePages + 1);
            }
            ?>



            <!-- Пропуск в начале -->
            <?php if ($startPage > 1): ?>
                <a href="?<?= $queryString ?>&page=1" class="pagination-link">1</a>
                <?php if ($startPage > 2): ?>
                    <span class="pagination-dots">...</span>
                <?php endif; ?>
            <?php endif; ?>

            <!-- Основные страницы -->
            <?php for ($i = $startPage; $i <= $endPage; $i++): ?>
                <a href="?<?= $queryString ?>&page=<?= $i ?>" class="pagination-link <?= $i == $currentPage ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>

            <!-- Пропуск в конце -->
            <?php if ($endPage < $totalPages): ?>
                <?php if ($endPage < $totalPages - 1): ?>
                    <span class="pagination-dots">...</span>
                <?php endif; ?>
                <a href="?<?= $queryString ?>&page=<?= $totalPages ?>" class="pagination-link"><?= $totalPages ?></a>
            <?php endif; ?>


        </div>
    <?php endif; ?>
</div>

<?php

function getRatingColor($rating) {
    if ($rating >= 4) return '#27ae60'; // Зеленый
    if ($rating >= 2) return '#f39c12'; // Оранжевый
    return '#e74c3c'; // Красный
}
?>

<style>
    .filter-actions {
        margin-top: 20px;
        display: flex;
        gap: 10px;
    }

    .filter-actions .btn {
        padding: 8px 16px;
        border-radius: 4px;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s ease;
    }

    .filter-actions .btn-primary {
        background-color: #007bff;
        color: white;
        border: 1px solid #007bff;
    }

    .filter-actions .btn-primary:hover {
        background-color: #0069d9;
        border-color: #0062cc;
    }

    .filter-actions .btn-secondary {
        background-color: #6c757d;
        color: white;
        border: 1px solid #6c757d;
    }

    .filter-actions .btn-secondary:hover {
        background-color: #5a6268;
        border-color: #545b62;
    }
    .filter-actions {
        display: flex;
        align-items: center;
        gap: 10px;
        margin-top: 8px;
    }
    /* Обновленные стили для пагинации */
    .pagination {
        height: 50px; /* Фиксированная высота */
        display: flex;
        justify-content: center;
        align-items: center;
        margin: 30px 0;
    }

    .pagination-link {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        min-width: 36px;
        height: 36px;
        padding: 0 8px;
        background-color: #f8f9fa;
        color: #3498db;
        text-decoration: none;
        border-radius: 4px;
        border: 1px solid #ddd;
        transition: all 0.3s;
        font-size: 14px;
    }

    .pagination-link:hover {
        background-color: #e9ecef;
        border-color: #adb5bd;
    }

    .pagination-link.active {
        background-color: #3498db;
        color: white;
        border-color: #3498db;
        font-weight: bold;
    }

    .pagination-dots {
        padding: 0 8px;
        color: #7f8c8d;
    }

    .pagination-first,
    .pagination-last,
    .pagination-prev,
    .pagination-next {
        font-weight: bold;
    }

    /* Остальные стили остаются прежними */
    .smart-filter {
        margin-bottom: 1rem;
        padding: 0.5rem;
        border-bottom: 1px solid #eee;
    }
    /* ... остальные стили ... */
</style>

<script>
    // Скрипт для показа/скрытия фильтров
    document.addEventListener('DOMContentLoaded', function() {
        const toggleBtn = document.getElementById('toggle-filters');
        const filtersSection = document.getElementById('filters-section');
        const toggleIcon = toggleBtn.querySelector('.toggle-icon');

        // Проверяем, есть ли активные фильтры
        const hasActiveFilters = window.location.search.includes('idCat=') ||
            window.location.search.includes('sortField=') ||
            window.location.search.includes('brand=');

        // Изначально показываем фильтры, если есть активные
        let filtersVisible = hasActiveFilters;
        updateFiltersVisibility();

        // Обработчик клика по кнопке
        toggleBtn.addEventListener('click', function() {
            filtersVisible = !filtersVisible;
            updateFiltersVisibility();
        });

        // Функция обновления видимости
        function updateFiltersVisibility() {
            if (filtersVisible) {
                filtersSection.style.display = 'block';
                toggleIcon.style.transform = 'rotate(180deg)';
                toggleBtn.classList.add('active');
            } else {
                filtersSection.style.display = 'none';
                toggleIcon.style.transform = 'rotate(0deg)';
                toggleBtn.classList.remove('active');
            }
        }
    });

    function submitCategoryFilter(categoryId) {
        const form = document.getElementById('category-filter-form');
        const url = new URL(window.location.href);

        // Устанавливаем параметры
        url.searchParams.set('idCat', categoryId);
        url.searchParams.set('page', 1);

        // Сохраняем другие параметры
        const sortField = form.querySelector('input[name="sortField"]');
        if (sortField) {
            url.searchParams.set('sortField', sortField.value);
        }

        // Переходим по новому URL
        window.location.href = url.toString();
    }
</script>
<style>
    .pagination {
        display: flex;
        justify-content: center;
        align-items: center;
        gap: 5px;
        margin: 30px 0;
        flex-wrap: wrap;
    }

    .pagination-link {
        display: inline-flex;
        justify-content: center;
        align-items: center;
        min-width: 36px;
        height: 36px;
        padding: 0 8px;
        background-color: #f8f9fa;
        color: #3498db;
        text-decoration: none;
        border-radius: 4px;
        border: 1px solid #ddd;
        transition: all 0.3s;
        font-size: 14px;
    }

    .pagination-link:hover {
        background-color: #e9ecef;
        border-color: #adb5bd;
    }

    .pagination-link.active {
        background-color: #3498db;
        color: white;
        border-color: #3498db;
        font-weight: bold;
    }

    .pagination-dots {
        padding: 0 8px;
        color: #7f8c8d;
    }

    .pagination-first,
    .pagination-last,
    .pagination-prev,
    .pagination-next {
        font-weight: bold;
    }

    /* Остальные стили остаются прежними */
    .smart-filter {
        margin-bottom: 1rem;
        padding: 0.5rem;
        border-bottom: 1px solid #eee;
    }
    .smart-filter {
        margin-bottom: 1rem;
        padding: 0.5rem;
        border-bottom: 1px solid #eee;
    }
    .smart-filter h5 {
        margin: 0 0 0.5rem 0;
        font-size: 0.9rem;
        font-weight: 600;
    }
    .range-inputs {
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }
    .range-inputs input {
        width: 80px;
        padding: 0.3rem;
    }
    .checkbox-options {
        display: flex;
        flex-direction: column;
        gap: 0.3rem;
    }
    .checkbox-options label {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        font-weight: normal;
        cursor: pointer;
    }
    select {
        width: 100%;
        padding: 0.3rem;
    }

    .header-controls {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 20px;
    }

    .sort-dropdown select {
        padding: 8px 15px;
        border-radius: 4px;
        border: 1px solid #ddd;
        background-color: white;
        font-size: 14px;
        cursor: pointer;
        transition: all 0.3s;
    }

    .sort-dropdown select:hover {
        border-color: #3498db;
    }

    .sort-dropdown select:focus {
        outline: none;
        border-color: #3498db;
        box-shadow: 0 0 0 2px rgba(52, 152, 219, 0.2);
    }

    /* Стили для блока фильтров */
    .filters-section {
        background: #f8f9fa;
        padding: 20px;
        border-radius: 8px;
        margin-bottom: 30px;
        box-shadow: 0 1px 3px rgba(0,0,0,0.1);
        display: none; /* По умолчанию скрыт */
    }

    .filters-section h3 {
        margin-top: 0;
        margin-bottom: 20px;
        color: #2c3e50;
        font-size: 20px;
    }

    .filter-group {
        margin-bottom: 20px;
    }

    .filter-group h4 {
        margin: 0 0 10px 0;
        color: #34495e;
        font-size: 16px;
    }

    .filter-options {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
    }

    .filter-option {
        display: flex;
        align-items: center;
        cursor: pointer;
        position: relative;
        padding-left: 25px;
        margin-right: 15px;
        font-size: 14px;
        color: #34495e;
    }

    .filter-option input {
        position: absolute;
        opacity: 0;
        cursor: pointer;
    }

    .checkmark {
        position: absolute;
        left: 0;
        height: 18px;
        width: 18px;
        background-color: #fff;
        border: 1px solid #ddd;
        transition: all 0.3s;
    }

    /* Стиль для чекбоксов */
    .checkmark:not(.radio) {
        border-radius: 3px;
    }

    /* Стиль для радиокнопок */
    .checkmark.radio {
        border-radius: 50%;
    }

    .filter-option:hover .checkmark {
        border-color: #3498db;
    }

    .filter-option input:checked ~ .checkmark {
        background-color: #3498db;
        border-color: #3498db;
    }

    .checkmark:after {
        content: "";
        position: absolute;
        display: none;
    }

    .filter-option input:checked ~ .checkmark:after {
        display: block;
    }

    /* Галочка для чекбоксов */
    .filter-option .checkmark:not(.radio):after {
        left: 6px;
        top: 2px;
        width: 5px;
        height: 10px;
        border: solid white;
        border-width: 0 2px 2px 0;
        transform: rotate(45deg);
    }

    /* Точка для радиокнопок */
    .filter-option .checkmark.radio:after {
        top: 4px;
        left: 4px;
        width: 10px;
        height: 10px;
        border-radius: 50%;
        background: white;
    }

    .filter-buttons {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .apply-filters {
        padding: 8px 15px;
        background-color: #3498db;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s;
    }

    .apply-filters:hover {
        background-color: #2980b9;
    }

    .reset-filters {
        padding: 8px 15px;
        background-color: #e74c3c;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        font-size: 14px;
        transition: background-color 0.3s;
    }
    .filter-actions{


    }
    .reset-filters:hover {
        background-color: #c0392b;
    }

    .total-products {
        background: #3498db;
        color: white;
        padding: 5px 10px;
        border-radius: 20px;
        font-size: 14px;
        display: inline-block;
    }

    .products {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 20px;
        margin-top: 30px;
    }

    .product-card {
        background: white;
        border-radius: 8px;
        padding: 15px;
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        transition: transform 0.3s;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.1);
    }

    .product-name {
        font-size: 18px;
        font-weight: bold;
        margin-bottom: 10px;
        color: #2c3e50;
    }

    .photo {
        width: 100%;
        height: 200px;
        overflow: hidden;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-bottom: 10px;
    }

    .photo img {
        max-width: 100%;
        max-height: 100%;
        object-fit: contain;
    }

    .product-price {
        color: #e74c3c;
        font-weight: bold;
        font-size: 20px;
        margin: 10px 0;
    }

    .product-stock {
        color: #27ae60;
        margin-bottom: 10px;
    }

    .product-info {
        color: #7f8c8d;
        font-size: 14px;
        margin-top: 5px;
    }

    .product-button {
        display: inline-block;
        margin-top: 10px;
        padding: 8px 15px;
        background-color: #3498db;
        color: white;
        text-decoration: none;
        border-radius: 4px;
        border: none;
        cursor: pointer;
        font-size: 14px;
        transition: background-color 0.3s;
    }

    .product-button:hover {
        background-color: #2980b9;
    }

    .product-rating {
        margin: 10px 0;
        display: flex;
        align-items: center;
        gap: 5px;
    }

    .rating-stars {
        font-size: 18px;
        letter-spacing: 2px;
    }

    .rating-value {
        color: #7f8c8d;
        font-size: 14px;
    }

    .toggle-filters-btn {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 8px 16px;
        margin-bottom: 15px;
        background-color: #f8f9fa;
        color: #2c3e50;
        border: 1px solid #ddd;
        border-radius: 6px;
        cursor: pointer;
        font-size: 14px;
        transition: all 0.3s ease;
    }

    .toggle-filters-btn:hover {
        background-color: #e9ecef;
        border-color: #adb5bd;
    }

    .toggle-filters-btn.active {
        background-color: #e3f2fd;
        border-color: #3498db;
    }

    .filter-icon {
        margin-right: 4px;
    }

    .toggle-icon {
        transition: transform 0.3s ease;
    }

    /* Адаптивность */
    @media (max-width: 768px) {
        .products {
            grid-template-columns: repeat(2, 1fr);
        }

        .filters-section {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 1000;
            background: white;
            padding: 20px;
            overflow-y: auto;
        }

        .toggle-filters-btn {
            position: sticky;
            top: 10px;
            z-index: 1001;
        }
    }
    .pagination-simple {
        display: flex;
        justify-content: center;
        gap: 15px;
        margin: 30px 0;
    }

    .pagination-btn {
        padding: 8px 16px;
        background-color: #f8f9fa;
        color: #3498db;
        text-decoration: none;
        border-radius: 4px;
        border: 1px solid #ddd;
        transition: all 0.3s;
        font-size: 14px;
    }

    .pagination-btn:hover {
        background-color: #e9ecef;
        border-color: #adb5bd;
    }

    .pagination-btn:disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    @media (max-width: 480px) {
        .products {
            grid-template-columns: 1fr;
        }

        .header-controls {
            flex-direction: column;
            align-items: flex-start;
            gap: 10px;
        }
    }
</style>