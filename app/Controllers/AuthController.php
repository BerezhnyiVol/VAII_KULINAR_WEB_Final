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
// Zobraziť prihlasovací formulár
    public function showLoginForm() {
        require_once __DIR__ . '/../Views/pages/login.view.php';
    }
    public function showRegisterForm() {
        require_once __DIR__ . '/../Views/pages/register.view.php';
    }
    // 1️⃣ Registrácia používateľa
    public function register() {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            die("❌ Neplatná metóda požiadavky");
        }

        $name = $_POST['name'] ?? null;
        $email = $_POST['email'] ?? null;
        $password = $_POST['password'] ?? null;

        if (!$name || !$email || !$password) {
            die("<h1 style='text-align: center; color: red;'>❌ Vyplňte všetky polia</h1>");
        }

        // Kontrola, či už používateľ existuje
        $stmt = $this->db->prepare("SELECT id FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);

        if ($stmt->fetch()) {
            die("<h1 style='text-align: center; color: red;'>❌ Používateľ s týmto e-mailom už existuje</h1>");
        }

        // Hashovanie hesla
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Pridanie používateľa do databázy
        $stmt = $this->db->prepare("INSERT INTO users (name, email, password, role) VALUES (:name, :email, :password, 'user')");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $hashedPassword
        ]);

        // Zobrazenie správy a presmerovanie na hlavnú stránku
        echo "<h1 style='text-align: center; color: green;'>✅ Registrácia úspešná!</h1>";
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
                die("<h1 style='text-align: center; color: red;'>❌ Vyplňte všetky polia</h1>");
            }

            // Overenie používateľa podľa mena alebo e-mailu
            $stmt = $this->db->prepare("SELECT * FROM users WHERE name = :username OR email = :username");
            $stmt->execute([':username' => $username]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($user && password_verify($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_role'] = $user['role']; // 'admin' alebo 'user'

                // Zobrazenie správy a presmerovanie po 2 sekundách
                echo "<h1 style='text-align: center; color: green;'>✅ Prihlásenie úspešné!</h1>";
                echo "<script>
                    setTimeout(function() {
                        window.location.href = '/VAII_KULINAR_WEB/public/index.php/home';
                    }, 2000);
                  </script>";
                exit();
            } else {
                echo "<h1 style='text-align: center; color: red;'>❌ Chyba: nesprávne údaje</h1>";
            }
        } else {
            require_once __DIR__ . '/../Views/pages/login.view.php';
        }
    }


    // 3️⃣ Odhlásenie používateľa
    public function logout() {
        session_destroy();
        echo "<div style='
        text-align: center; 
        font-size: 28px; 
        font-weight: bold; 
        margin-top: 20%;
        color: green;
    '>
        ✅ Odhlásenie úspešné!
    </div>";

        // Automatické presmerovanie po 3 sekundách
        echo "<script>
        setTimeout(function() {
            window.location.href = '/VAII_KULINAR_WEB/public/index.php/login';
        }, 3000);
    </script>";
        exit();
    }


    // 4️⃣ Vytvorenie administrátora (manuálne volanie)
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

            echo "✅ Administrátor vytvorený: $email / admin123";
        } catch (Exception $e) {
            die("❌ Chyba pri vytváraní administrátora: " . $e->getMessage());
        }
    }

    // Overenie: je používateľ prihlásený?
    public function isAuthenticated() {
        return isset($_SESSION['user_id']);
    }

// Overenie: je používateľ administrátor?
    public function isAdmin() {
        return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
    }
}
