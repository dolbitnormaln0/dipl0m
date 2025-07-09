<?php
// Запускаем сессию, если она еще не запущена
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Проверяем, авторизован ли пользователь
$isLoggedIn = isset($_SESSION['user_id']);
$userName = $_SESSION['user_name'] ?? '';
$userId = $_SESSION['user_id'] ?? null;
echo $userId;
?>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Интернет-магазин') ?></title>
    <style>
        /* Общие стили */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            line-height: 1.6;
        }
        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            padding: 15px 0;
        }
        header {
            background: #3498db;
            color: white;
            padding: 8px 0;
            margin-bottom: 15px;
        }
        header a {
            color: white;
            text-decoration: none;
        }
        nav ul {
            list-style: none;
            padding: 0;
            display: flex;
            gap: 20px;
            align-items: center;
        }
        footer {
            background: #333;
            color: white;
            text-align: center;
            padding: 15px 0;
            margin-top: 30px;
        }

        /* Стили для поиска и кнопок */
        .header-controls {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .search-container {
            display: flex;
            align-items: center;
            margin-top: 8px;
        }
        .search-input {
            padding: 8px 15px;
            border: none;
            border-radius: 4px 0 0 4px;
            font-size: 15px;
            width: 250px;
            height: 36px;
            box-sizing: border-box;
        }

        .search-button {
            padding: 0 15px;
            background: #2980b9;
            color: white;
            border: none;
            border-radius: 0 4px 4px 0;
            cursor: pointer;
            transition: background 0.3s;
            font-size: 15px;
            height: 36px;
        }

        .search-button:hover {
            background: #1a6ea7;
        }

        .login-button {
            padding: 0 20px;
            background: #2ecc71;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
            text-decoration: none;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
            height: 36px;
            line-height: 36px;
            margin-top: 8px;
        }

        .login-button:hover {
            background: #27ae60;
        }

        .user-profile {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-top: 8px;
        }

        .user-name {
            color: white;
            font-weight: bold;
        }

        .logout-button {
            padding: 0 15px;
            background: #e74c3c;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: background 0.3s;
            font-size: 14px;
            height: 30px;
        }

        .logout-button:hover {
            background: #c0392b;
        }

        /* Гибкое расположение элементов шапки */
        .header-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 8px;
        }
        .header-bottom {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        /* Заголовок */
        header h1 {
            font-size: 22px;
            margin: 0;
            padding: 2px 0;
        }

        /* Основное содержимое */
        main {
            min-height: 60vh;
        }
    </style>
</head>
<body>
<header>
    <div class="container">
        <div class="header-top">
            <h1><a href="/">Интернет-магазин</a></h1>
            <div class="header-controls">
                <div class="search-container">
                    <form action="/search" method="GET" class="search-form">
                        <input type="text" name="string" class="search-input" placeholder="Поиск товаров..." value="<?= isset($_GET['q']) ? htmlspecialchars($_GET['q']) : '' ?>">
                        <button type="submit" class="search-button">Найти</button>
                    </form>
                </div>
                <?php if ($isLoggedIn): ?>
                    <div class="user-profile">
                        <a href="/profile" class="user-name"><?= htmlspecialchars($userName) ?></a>
                        <form action="/logout" method="get" style="margin: 0;">
                            <button type="submit" class="logout-button" >Выйти</button>
                        </form>
                    </div>
                <?php else: ?>
                    <a href="/login" class="login-button">Вход</a>
                <?php endif; ?>
            </div>
        </div>
        <div class="header-bottom">
            <nav>
                <ul>
                    <li><a href="/">Главная</a></li>
                    <li><a href="/"></a></li>
                    <li><a href="/products">Каталог</a></li>
                    <?php if ($isLoggedIn): ?>
                        <li><a href="/profile">Профиль</a></li>
                        <li><a href="/card">Корзина</a></li>
                    <?php endif; ?>
                </ul>
            </nav>
        </div>
    </div>
</header>

<main class="container">
    <?php
    // Подключаем конкретный view
    if (isset($view)) {
        require __DIR__ .DIRECTORY_SEPARATOR.'..'.DIRECTORY_SEPARATOR . $view . '.php';
    } else {
        echo 'Содержимое страницы';
    }
    ?>
</main>

<footer>
    <div class="container">
        &copy; <?= date('Y') ?> Интернет-магазин
    </div>
</footer>

<script>
    // Поиск при нажатии Enter
    document.querySelector('.search-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            document.querySelector('.search-button').click();
        }
    });
</script>
</body>
</html>