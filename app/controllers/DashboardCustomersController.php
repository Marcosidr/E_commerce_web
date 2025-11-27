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
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Acesso negado. Somente administradores.'];
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }

    public function index()
    {
        $stmt = $this->db->query("SELECT id, nome, email, criado_em 
                                  FROM usuarios 
                                  WHERE ativo = 1 
                                  ORDER BY criado_em DESC");
        $clientes = $stmt->fetchAll(\PDO::FETCH_ASSOC);

        $this->loadPartial('Painel/clientes/index', ['clientes' => $clientes]);
    }

    public function show($id)
    {
        $id = (int)$id;
        $stmt = $this->db->prepare("SELECT * FROM usuarios WHERE id = :id LIMIT 1");
        $stmt->bindValue(':id', $id);
        $stmt->execute();
        $cliente = $stmt->fetch(\PDO::FETCH_ASSOC);

        $this->loadPartial('Painel/clientes/show', ['cliente' => $cliente]);
    }

    // FORMULÁRIO DE CADASTRO
    public function adicionar()
    {
        $this->loadPartial('Painel/clientes/adicionar');
    }

    // COMPATIBILIDADE
    public function salvar()
    {
        return $this->store();
    }

    // SALVAR NOVO CLIENTE
    public function store()
    {
        $nome   = trim($_POST['nome'] ?? '');
        $email  = trim($_POST['email'] ?? '');
        $senha  = trim($_POST['senha'] ?? '');
        $telefone = $_POST['telefone'] ?? null;
        $dataNasc = $_POST['data_nascimento'] ?? null;
        $genero = $_POST['genero'] ?? null;

        if ($nome === '' || $email === '' || $senha === '') {
            $_SESSION['flash_message'] = ['type'=>'danger','text'=>'Preencha todos os campos obrigatórios.'];
            header("Location: " . BASE_URL . "/dashboard/clientes/adicionar");
            exit;
        }

        if ($this->users->emailExists($email)) {
            $_SESSION['flash_message'] = ['type'=>'danger','text'=>'Este e-mail já está cadastrado.'];
            header("Location: " . BASE_URL . "/dashboard/clientes/adicionar");
            exit;
        }

        // Mapeamento do gênero
        $genMap = ['masculino'=>'M','feminino'=>'F','outro'=>'O'];
        $generoDB = $genMap[$genero] ?? 'O';

        // Role permitida
        $allowedRoles = ['admin','cliente','staff'];
        $role = $_POST['role'] ?? 'cliente';
        if (!in_array($role, $allowedRoles)) $role = 'cliente';

        // Criar usuário
        $user = $this->users->create([
            'nome' => $nome,
            'email' => $email,
            'senha' => password_hash($senha, PASSWORD_DEFAULT),
            'telefone' => $telefone,
            'data_nascimento' => $dataNasc,
            'genero' => $generoDB,
            'newsletter' => isset($_POST['newsletter']) ? 1 : 0,
            'sms_marketing' => isset($_POST['sms_marketing']) ? 1 : 0,
            'role' => $role
        ]);

        if ($user) {
            $_SESSION['flash_message'] = ['type'=>'success','text'=>'Cliente cadastrado com sucesso!'];
            header("Location: " . BASE_URL . "/dashboard/clientes");
            exit;
        }

        $_SESSION['flash_message'] = ['type'=>'danger','text'=>'Erro ao cadastrar cliente.'];
        header("Location: " . BASE_URL . "/dashboard/clientes/adicionar");
        exit;
    }

    // FORMULÁRIO DE EDIÇÃO
    public function editar($id)
    {
        $id = (int)$id;
        $cliente = $this->users->getById($id);

        if (!$cliente) {
            $_SESSION['flash_message'] = ['type'=>'danger','text'=>'Cliente não encontrado'];
            header("Location: " . BASE_URL . "/dashboard/clientes");
            exit;
        }
    

        $this->loadPartial('Painel/clientes/editar', ['cliente' => (array)$cliente]);
    }
  

    // ATUALIZAR CLIENTE
    public function update($id)
    {
        $id = (int)$id;

        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header("Location: " . BASE_URL . "/dashboard/clientes");
            exit;
        }

        $nome = trim($_POST['nome'] ?? '');
        $email = trim($_POST['email'] ?? '');

        if ($nome === '' || $email === '') {
            $_SESSION['flash_message'] = ['type'=>'danger','text'=>'Nome e e-mail são obrigatórios'];
            header("Location: " . BASE_URL . "/dashboard/clientes/$id/editar");
            exit;
        }

        // Mapeamento do gênero
        $gen = $_POST['genero'] ?? 'outro';
        $genMap = ['masculino'=>'M','feminino'=>'F','outro'=>'O'];
        $generoDB = $genMap[$gen] ?? 'O';

        $payload = [
            'nome' => $nome,
            'email' => $email,
            'telefone' => $_POST['telefone'] ?? null,
            'data_nascimento' => $_POST['data_nascimento'] ?? null,
            'genero' => $generoDB,
            'newsletter' => isset($_POST['newsletter']) ? 1 : 0,
            'sms_marketing' => isset($_POST['sms_marketing']) ? 1 : 0,
            'role' => $_POST['role'] ?? 'cliente'
        ];

        // senha opcional
        if (!empty($_POST['senha'])) {
            $payload['senha'] = password_hash($_POST['senha'], PASSWORD_DEFAULT);
        }

        $ok = $this->users->update($id, $payload);

        $_SESSION['flash_message'] = [
            'type' => $ok ? 'success' : 'danger',
            'text' => $ok ? 'Cliente atualizado com sucesso!' : 'Erro ao atualizar cliente.'
        ];

        header("Location: " . BASE_URL . "/dashboard/clientes/$id/editar");
        exit;
    }

    // DESATIVAR CLIENTE
    public function deletar($id)
    {
        $id = (int)$id;

        $ok = $this->users->deactivate($id);

        $_SESSION['flash_message'] = [
            'type' => $ok ? 'success' : 'danger',
            'text' => $ok ? 'Cliente removido.' : 'Erro ao remover.'
        ];

        header("Location: " . BASE_URL . "/dashboard/clientes");
        exit;
    }
}
