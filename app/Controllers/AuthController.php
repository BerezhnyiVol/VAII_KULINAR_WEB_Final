<?php

require_once __DIR__ . '/../core/Database.php';

class AuthController {
    private $db;

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
    }
// Показать форму входа
    public function showLoginForm() {
        require_once __DIR__ . '/../Views/pages/login.view.php';
    }
    public function showRegisterForm() {
        require_once __DIR__ . '/../Views/pages/register.view.php';
    }
    // 1️⃣ Регистрация пользователя
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die("❌ Неверный метод запроса");
        }

        $name = $_POST['name'] ?? null;
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!$name || !$email || !$password) {
            die("<h1 style='text-align: center; color: red;'>❌ Заполните все поля</h1>");
        }

        // Проверяем, есть ли уже такой пользователь
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);

        if ($stmt->fetch()) {
            die("<h1 style='text-align: center; color: red;'>❌ Пользователь с таким email уже существует</h1>");
        }

        // Хешируем пароль
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Добавляем пользователя в БД
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, 'user')");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashedPassword
        ]);

        // Выводим сообщение и перенаправляем на главную
        echo "<h1 style='text-align: center; color: green;'>✅ Регистрация успешна!</h1>";
        echo "<script>
            setTimeout(function() {
                window.location.href = '/VAII_KULINAR_WEB/public/index.php/home';
            }, 2000);
          </script>";
        exit();
    }


    public function login() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $username = $_POST['username'] ?? '';
            $password = $_POST['password'] ?? '';

            if (!$username || !$password) {
                die("<h1 style='text-align: center; color: red;'>❌ Заполните все поля</h1>");
            }

            // Проверяем, есть ли пользователь с таким именем или email
            $stmt = $this->db->prepare("SELECT * FROM users WHERE name = :username OR email = :username");
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role']; // 'admin' или 'user'

                // Вывод сообщения и редирект через 2 секунды
                echo "<h1 style='text-align: center; color: green;'>✅ Вход выполнен успешно!</h1>";
                echo "<script>
                    setTimeout(function() {
                        window.location.href = '/VAII_KULINAR_WEB/public/index.php/home';
                    }, 2000);
                  </script>";
                exit();
            } else {
                echo "<h1 style='text-align: center; color: red;'>❌ Ошибка: неправильные данные</h1>";
            }
        } else {
            require_once __DIR__ . '/../Views/pages/login.view.php';
        }
    }


    // 3️⃣ Выход пользователя
    public function logout() {
        session_destroy();
        echo "<div style='
        text-align: center; 
        font-size: 28px; 
        font-weight: bold; 
        margin-top: 20%;
        color: green;
    '>
        ✅ Выход выполнен!
    </div>";

        // Автоматический редирект через 3 секунды
        echo "<script>
        setTimeout(function() {
            window.location.href = '/VAII_KULINAR_WEB/public/index.php/login';
        }, 3000);
    </script>";
        exit();
    }


    // 4️⃣ Создание администратора (ручной вызов)
    public function createAdmin() {
        $email = "admin@example.com";
        $password = password_hash("admin123", PASSWORD_DEFAULT);
        $role = "admin";

        try {
            $stmt = $this->db->prepare("INSERT INTO users (email, password, role) VALUES (:email, :password, :role)");
            $stmt->execute([
                ':email' => $email,
                ':password' => $password,
                ':role' => $role
            ]);

            echo "✅ Админ создан: $email / admin123";
        } catch (Exception $e) {
            die("❌ Ошибка при создании админа: " . $e->getMessage());
        }
    }

    // Проверка: пользователь авторизован?
    public function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }

// Проверка: является ли пользователь администратором?
    public function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
}
