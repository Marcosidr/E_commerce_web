<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Users;

require_once CONFIG_PATH . '/connection.php';

class LoginController extends Controller
{
    private $users;

    public function __construct()
    {
        $connection = new \Connection();
        $pdo = $connection->conect();
        $this->users = new Users($pdo);

        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    /**
     * Exibe o formulário de login (rota /login)
     */
    public function login()
    {
        $this->loadPartial('Painel/login', [
            'title' => 'Login - URBANSTREET',
            'metaDescription' => 'Entre na sua conta URBANSTREET',
            'pageClass' => 'login-page'
        ]);
    }

    /**
     * Processa submissão de login (POST)
     */
    public function verify()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validações básicas
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $_SESSION['flash_message'] = [
                'type' => 'danger',
                'text' => 'Digite um e-mail válido.'
            ];
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        if ($password === '') {
            $_SESSION['flash_message'] = [
                'type' => 'danger',
                'text' => 'Senha obrigatória.'
            ];
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        // Buscar usuário no banco
        $dataUsers = $this->users->getByEmail($email);

        if (!$dataUsers || empty($dataUsers->id)) {
            $_SESSION['flash_message'] = [
                'type' => 'danger',
                'text' => 'Usuário não encontrado.'
            ];
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        // Verificação de senha
        if (!password_verify($password, $dataUsers->senha)) {
            $_SESSION['flash_message'] = [
                'type' => 'danger',
                'text' => 'Senha inválida.'
            ];
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        // Agora o role vem direto do banco — profissional
        $_SESSION['users'] = [
            'id' => $dataUsers->id,
            'nome' => $dataUsers->nome,
            'email' => $dataUsers->email,
            'role' => $dataUsers->role
        ];

        // Redirecionamento
        $redirectPath = $this->consumeRedirectAfterLogin();

        if ($redirectPath) {
            header('Location: ' . BASE_URL . $redirectPath);
            exit;
        }

        header('Location: ' . BASE_URL);
        exit;
    }

    public function logout()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        session_unset();
        session_destroy();

        header('Location: ' . BASE_URL . '/login');
        exit;
    }

    private function consumeRedirectAfterLogin(): ?string
    {
        if (empty($_SESSION['redirect_after_login'])) {
            return null;
        }

        $path = $_SESSION['redirect_after_login'];
        unset($_SESSION['redirect_after_login']);

        if (is_string($path) && isset($path[0]) && $path[0] === '/') {
            return $path;
        }

        return null;
    }
}
