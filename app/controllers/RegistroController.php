<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Users;

require_once CONFIG_PATH . '/connection.php';

class RegistroController extends Controller
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

    public function cadastro()
    {
        $this->loadPartial('auth/cadastro', [
            'title' => 'Criar Conta - URBANSTREET',
            'metaDescription' => 'Crie sua conta na URBANSTREET e aproveite ofertas exclusivas',
            'pageClass' => 'register-page'
        ]);
    }

    public function create()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/cadastro');
            exit;
        }

        try {
            $nome = trim($_POST['nome'] ?? '');
            $sobrenome = trim($_POST['sobrenome'] ?? '');
            $email = strtolower(trim($_POST['email'] ?? ''));
            $password = $_POST['password'] ?? '';
            $confirmPassword = $_POST['confirm_password'] ?? '';
            
            if (strlen($nome) < 2) {
                $_SESSION['error'] = 'Nome deve ter pelo menos 2 caracteres.';
                header('Location: ' . BASE_URL . '/cadastro');
                exit;
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $_SESSION['error'] = 'Email inválido.';
                header('Location: ' . BASE_URL . '/cadastro');
                exit;
            }
            
            if (strlen($password) < 6) {
                $_SESSION['error'] = 'Senha deve ter pelo menos 6 caracteres.';
                header('Location: ' . BASE_URL . '/cadastro');
                exit;
            }
            
            if ($password !== $confirmPassword) {
                $_SESSION['error'] = 'Senhas não coincidem.';
                header('Location: ' . BASE_URL . '/cadastro');
                exit;
            }

            if ($this->users->emailExists($email)) {
                $_SESSION['error'] = 'Este email já está cadastrado.';
                header('Location: ' . BASE_URL . '/cadastro');
                exit;
            }

            $userData = $this->users->create([
                'name' => $nome . ' ' . $sobrenome,
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT),
                'telefone' => $_POST['telefone'] ?? null,
                'data_nascimento' => $_POST['data_nascimento'] ?? null,
                'sexo' => $_POST['sexo'] ?? null,
                'newsletter' => isset($_POST['newsletter']) ? 1 : 0,
                'sms_marketing' => isset($_POST['sms_marketing']) ? 1 : 0
            ]);

            if ($userData) {
                $_SESSION['success'] = 'Conta criada com sucesso!';
                $_SESSION['users'] = [
                    'id' => $userData->id,
                    'nome' => $userData->nome,
                    'email' => $email
                ];
                header('Location: ' . BASE_URL . '/');
            } else {
                $_SESSION['error'] = 'Erro ao criar conta.';
                header('Location: ' . BASE_URL . '/cadastro');
            }

        } catch (\Exception $e) {
            error_log("Erro no cadastro: " . $e->getMessage());
            $_SESSION['error'] = 'Erro interno. Tente novamente.';
            header('Location: ' . BASE_URL . '/cadastro');
        }
        exit;
    }
}