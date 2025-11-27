<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Users;

class DashboardCustomersController extends Controller
{
    private \PDO $db;
    private Users $users;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->users = new Users($this->db);

        if (session_status() === PHP_SESSION_NONE) session_start();
        $this->requireAdmin();
    }

    private function requireAdmin(): void
    {
        if (!isset($_SESSION['users']) || ($_SESSION['users']['role'] ?? '') !== 'admin') {
            $_SESSION['flash_message'] = ['type'=>'danger','text'=>'Acesso negado. Somente administradores.'];
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }

    public function index()
    {
        try {
            $stmt = $this->db->query("SELECT id, nome, email, criado_em FROM usuarios WHERE ativo = 1 ORDER BY criado_em DESC LIMIT 200");
            $clientes = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            $clientes = [];
        }

        $this->loadPartial('Painel/clientes/index', ['clientes'=>$clientes]);
    }

    public function show($id)
    {
        $id = (int)$id;

        try {
            $stmt = $this->db->prepare("SELECT id, nome, email, telefone, criado_em 
                                        FROM usuarios 
                                        WHERE id = :id LIMIT 1");
            $stmt->bindValue(':id', $id);
            $stmt->execute();
            $cliente = $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            $cliente = null;
        }

        $this->loadPartial('Painel/clientes/show', ['cliente'=>$cliente]);
    }

    // ============================
    //     ADICIONAR CLIENTE
    // ============================
    public function adicionar()
    {
        // Apenas carrega o formulário
        $this->loadPartial('Painel/clientes/criar');
    }

    // ============================
    //     SALVAR NOVO CLIENTE
    // ============================
    public function salvar()
    {
        $nome  = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($nome === '' || $email === '') {
            $_SESSION['flash_message'] = ['type'=>'danger', 'text'=>'Preencha todos os campos obrigatórios.'];
            header("Location: " . BASE_URL . "/dashboard/clientes/adicionar");
            exit;
        }

        if ($this->users->emailExists($email)) {
            $_SESSION['flash_message'] = ['type'=>'danger', 'text'=>'Este e-mail já está cadastrado.'];
            header("Location: " . BASE_URL . "/dashboard/clientes/adicionar");
            exit;
        }

        $user = $this->users->create([
            'name'  => $nome,
            'email' => $email,
            'password' => password_hash('123456', PASSWORD_DEFAULT),
            'role' => 'cliente'
        ]);

        if ($user) {
            $_SESSION['flash_message'] = ['type'=>'success', 'text'=>'Cliente cadastrado com sucesso!'];
            header("Location: " . BASE_URL . "/dashboard/clientes");
            exit;
        }

        $_SESSION['flash_message'] = ['type'=>'danger', 'text'=>'Erro ao cadastrar cliente.'];
        header("Location: " . BASE_URL . "/dashboard/clientes/adicionar");
        exit;
    }
}
