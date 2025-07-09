<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Вход / Регистрация</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
            background-color: #f4f4f9;
        }
        .form-container {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }
        input {
            width: 100%;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }
        button {
            width: 100%;
            padding: 10px;
            background-color: #007BFF;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }
        button:hover {
            background-color: #0056b3;
        }
        .switch-form {
            text-align: center;
            margin-top: 15px;
        }
        .switch-form a {
            color: #007BFF;
            text-decoration: none;
        }
        .switch-form a:hover {
            text-decoration: underline;
        }
        .hidden {
            display: none;
        }
        .error-message {
            color: red;
            margin-top: 10px;
            text-align: center;
        }
        .success-message {
            color: green;
            margin-top: 10px;
            text-align: center;
        }
    </style>
</head>
<body>
<div class="form-container">
    <!-- Форма входа -->
    <div id="login-form">
        <h1>Вход</h1>
        <form id="login">
            <div class="form-group">
                <label for="login-email">Email:</label>
                <input type="email" id="login-email" required>
            </div>
            <div class="form-group">
                <label for="login-password">Пароль:</label>
                <input type="password" id="login-password" required>
            </div>
            <div id="login-error" class="error-message"></div>
            <div id="login-success" class="success-message"></div>
            <button type="button" onclick="handleLogin()">Войти</button>
        </form>
        <div class="switch-form">
            Нет аккаунта? <a href="#" onclick="switchToRegister()">Зарегистрироваться</a>
        </div>
    </div>

    <!-- Форма регистрации -->
    <div id="register-form" class="hidden">
        <h1>Регистрация</h1>
        <form id="register">
            <div class="form-group">
                <label for="register-first-name">Имя:</label>
                <input type="text" id="register-first-name" required>
            </div>
            <div class="form-group">
                <label for="register-email">Email:</label>
                <input type="email" id="register-email" required>
            </div>
            <div class="form-group">
                <label for="register-password">Пароль:</label>
                <input type="password" id="register-password" required minlength="6">
            </div>
            <div id="register-error" class="error-message"></div>
            <div id="register-success" class="success-message"></div>
            <button type="button" onclick="handleRegister()">Зарегистрироваться</button>
        </form>
        <div class="switch-form">
            Уже есть аккаунт? <a href="#" onclick="switchToLogin()">Войти</a>
        </div>
    </div>
</div>

<script>

    function switchToRegister() {
        document.getElementById('login-form').classList.add('hidden');
        document.getElementById('register-form').classList.remove('hidden');
        clearMessages();
    }

    function switchToLogin() {
        document.getElementById('register-form').classList.add('hidden');
        document.getElementById('login-form').classList.remove('hidden');
        clearMessages();
    }

    function clearMessages() {
        document.getElementById('login-error').textContent = '';
        document.getElementById('login-success').textContent = '';
        document.getElementById('register-error').textContent = '';
        document.getElementById('register-success').textContent = '';
    }


    async function handleLogin() {
        const email = document.getElementById('login-email').value;
        const password = document.getElementById('login-password').value;
        const errorElement = document.getElementById('login-error');
        const successElement = document.getElementById('login-success');

        clearMessages();

        try {
            const response = await fetch('/api/login.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ email, password })
            });

            const result = await response.json();

            if (result.success) {
                successElement.textContent = 'Вход выполнен успешно!';
                window.location.href = '/';
            } else {
                errorElement.textContent = result.message || 'Неверный email или пароль';
            }
        } catch (error) {
            console.error('Ошибка:', error);
            errorElement.textContent = 'Ошибка при входе. Попробуйте позже.';
        }
    }
    async function handleRegister() {
        const firstName = document.getElementById('register-first-name').value;
        const email = document.getElementById('register-email').value;
        const password = document.getElementById('register-password').value;
        const errorElement = document.getElementById('register-error');
        const successElement = document.getElementById('register-success');

        clearMessages();

        try {
            const response = await fetch('/api/register.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ first_name: firstName, email, password })
            });

            const result = await response.json();

            if (result.success) {
                successElement.textContent = 'Регистрация прошла успешно!';
                setTimeout(() => {
                    switchToLogin();
                }, 1500);
            } else {
                errorElement.textContent = result.message || 'Ошибка при регистрации';
            }
        } catch (error) {
            console.error('Ошибка:', error);
            errorElement.textContent = 'Ошибка при регистрации. Попробуйте позже.';
        }
    }
</script>
</body>
</html>