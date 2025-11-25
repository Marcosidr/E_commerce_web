<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;

class DashboardOrdersController extends Controller
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
        // Lista simples de pedidos (se tabela existir)
        try {
            $stmt = $this->db->query("SELECT p.id, p.usuario_id, p.status, p.total, p.criado_em FROM pedidos p ORDER BY p.criado_em DESC LIMIT 100");
            $pedidos = $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\Throwable $e) {
            $pedidos = [];
        }
        $this->loadPartial('Painel/pedidos/index', ['pedidos'=>$pedidos]);
    }

    public function show($id)
    {
        $id = (int)$id;
        $pedido = null; $itens = [];
        try {
            $stmt = $this->db->prepare("SELECT p.*, u.nome AS nome_cliente, u.email AS email_cliente 
                                         FROM pedidos p 
                                         LEFT JOIN usuarios u ON u.id = p.usuario_id 
                                         WHERE p.id = :id LIMIT 1");
            $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
            $stmt->execute();
            $pedido = $stmt->fetch(\PDO::FETCH_ASSOC);

            if ($pedido) {
                $s2 = $this->db->prepare("SELECT i.*, pr.nome as nome_produto 
                                           FROM itens_pedido i 
                                           LEFT JOIN produtos pr ON pr.id = i.produto_id 
                                           WHERE i.pedido_id = :id");
                $s2->bindValue(':id', $id, \PDO::PARAM_INT);
                $s2->execute();
                $itens = $s2->fetchAll(\PDO::FETCH_ASSOC);
            }
        } catch (\Throwable $e) {
            // mantém null/[]
        }
        $this->loadPartial('Painel/pedidos/show', ['pedido'=>$pedido,'itens'=>$itens]);
    }
}
