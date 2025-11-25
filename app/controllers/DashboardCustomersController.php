<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class DashboardCustomersController extends Controller
{
    private \PDO $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
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
        // Lista clientes (usuários com role = 'cliente' se existir) ou todos ativos
        try {
            $stmt = $this->db->query("SELECT id, nome, email, criado_em FROM usuarios WHERE ativo = 1 ORDER BY criado_em DESC LIMIT 200");
            $clientes = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Throwable $e) { $clientes = []; }
        $this->loadPartial('Painel/clientes/index', ['clientes'=>$clientes]);
    }

    public function show($id)
    {
        $id = (int)$id;
        $cliente = null;
        try {
            $stmt = $this->db->prepare("SELECT id, nome, email, telefone, criado_em FROM usuarios WHERE id = :id LIMIT 1");
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            $cliente = $stmt->fetch(\PDO::FETCH_ASSOC);
        } catch (\Throwable $e) { }
        $this->loadPartial('Painel/clientes/show', ['cliente'=>$cliente]);
    }
}
