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
        $this->loadPartial('Painel/clientes/adicionar');
    }

    // ============================
    //     SALVAR NOVO CLIENTE
    // ============================
    public function salvar()
    {
        // mantemos compatibilidade: encaminha para store()
        return $this->store();
    }

    // compatível com rota /dashboard/clientes/store
    public function store()
    {
        $nome  = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $senha = trim($_POST['senha'] ?? '');

        if ($nome === '' || $email === '' || $senha === '') {
            $_SESSION['flash_message'] = ['type'=>'danger', 'text'=>'Preencha todos os campos obrigatórios.'];
            header("Location: " . BASE_URL . "/dashboard/clientes/adicionar");
            exit;
        }

        if ($this->users->emailExists($email)) {
            $_SESSION['flash_message'] = ['type'=>'danger', 'text'=>'Este e-mail já está cadastrado.'];
            header("Location: " . BASE_URL . "/dashboard/clientes/adicionar");
            exit;
        }

        // converter genero para valores do DB (enum 'M','F','O')
        $gen = $_POST['genero'] ?? '';
        $genMap = ['masculino' => 'M', 'feminino' => 'F', 'outro' => 'O', 'M'=>'M','F'=>'F','O'=>'O'];
        $sexo = $genMap[strtolower($gen)] ?? ($genMap[$gen] ?? null);

        // validar role enviada (somente valores permitidos)
        $allowedRoles = ['admin','cliente','staff'];
        $role = $_POST['role'] ?? 'cliente';
        if (!in_array($role, $allowedRoles, true)) $role = 'cliente';

        $user = $this->users->create([
            'name'  => $nome,
            'email' => $email,
            'password' => password_hash($senha, PASSWORD_DEFAULT),
            'role' => $role,
            'telefone' => $_POST['telefone'] ?? null,
            'data_nascimento' => $_POST['data_nascimento'] ?? null,
            'sexo' => $sexo,
            'newsletter' => isset($_POST['newsletter']) ? 1 : 0,
            'sms_marketing' => isset($_POST['sms_marketing']) ? 1 : 0,
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

    // Mostrar formulário de edição
    public function editar($id)
    {
        $id = (int)$id;
        $cliente = $this->users->getById($id);
        if (!$cliente) {
            $_SESSION['flash_message'] = ['type'=>'danger','text'=>'Cliente não encontrado'];
            header('Location: ' . BASE_URL . '/dashboard/clientes');
            exit;
        }

        // converter objeto para array (views atuais esperam array)
        $clienteArr = (array)$cliente;
        $this->loadPartial('Painel/clientes/editar', ['cliente'=>$clienteArr]);
    }

    // Atualizar cliente
    public function update($id)
    {
        $id = (int)$id;
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/dashboard/clientes');
            exit;
        }

        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($nome === '' || $email === '') {
            $_SESSION['flash_message'] = ['type'=>'danger','text'=>'Nome e e-mail são obrigatórios'];
            header('Location: ' . BASE_URL . '/dashboard/clientes/' . $id . '/editar');
            exit;
        }

        // montar payload conforme Users::update espera
        // mapear genero para 'M','F','O'
        $gen = $_POST['genero'] ?? '';
        $genMap = ['masculino' => 'M', 'feminino' => 'F', 'outro' => 'O', 'M'=>'M','F'=>'F','O'=>'O'];
        $sexo = $genMap[strtolower($gen)] ?? ($genMap[$gen] ?? null);

        $payload = [
            'nome' => $nome,
            'email' => $email,
            'telefone' => $_POST['telefone'] ?? null,
            'data_nascimento' => $_POST['data_nascimento'] ?? null,
            'genero' => $sexo,
            'newsletter' => isset($_POST['newsletter']) ? 1 : 0,
            'sms_marketing' => isset($_POST['sms_marketing']) ? 1 : 0,
        ];

        // role opcional (apenas valores permitidos)
        $allowedRoles = ['admin','cliente','staff'];
        if (!empty($_POST['role']) && in_array($_POST['role'], $allowedRoles, true)) {
            $payload['role'] = $_POST['role'];
        }

        // senha opcional
        if (!empty($_POST['senha'])) {
            $payload['senha'] = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        }

        $ok = $this->users->update($id, $payload);
        if ($ok) {
            $_SESSION['flash_message'] = ['type'=>'success','text'=>'Cliente atualizado com sucesso'];
        } else {
            $_SESSION['flash_message'] = ['type'=>'danger','text'=>'Falha ao atualizar cliente'];
        }

        header('Location: ' . BASE_URL . '/dashboard/clientes/' . $id . '/editar');
        exit;
    }

    // Deletar (desativar) cliente
    public function deletar($id)
    {
        $id = (int)$id;
        $ok = $this->users->deactivate($id);
        if ($ok) {
            $_SESSION['flash_message'] = ['type'=>'success','text'=>'Cliente removido (inativado)'];
        } else {
            $_SESSION['flash_message'] = ['type'=>'danger','text'=>'Falha ao remover cliente'];
        }
        header('Location: ' . BASE_URL . '/dashboard/clientes');
        exit;
    }
}
