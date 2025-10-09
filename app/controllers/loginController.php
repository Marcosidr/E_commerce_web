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
        if (session_status() === PHP_SESSION_NONE) { session_start(); }
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
            return;
        }

        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';

        // Validações básicas
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            echo "<script>mensagem('Digite um email válido','error','');history.back();</script>"; return;
        }
        if ($password === '') {
            echo "<script>mensagem('Senha obrigatória','error','');history.back();</script>"; return;
        }

    $dataUsers = $this->users->getByEmail($email);
        if (!$dataUsers || empty($dataUsers->id)) {
            echo "<script>mensagem('Usuário inválido','error','');history.back();</script>"; return;
        }
        if (!password_verify($password, $dataUsers->password)) {
            echo "<script>mensagem('Senha inválida','error','');history.back();</script>"; return;
        }

        // Sucesso
        $_SESSION['users'] = [
            'id' => $dataUsers->id,
            'nome' => $dataUsers->name
        ];
        echo "<script>location.href='" . BASE_URL . "';</script>"; // redireciona para home
    }
}
