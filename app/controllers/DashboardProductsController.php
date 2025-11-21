<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;

class DashboardProductsController extends Controller
{
    private Product $productModel;

    public function __construct()
    {
        $this->productModel = new Product();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->requireAdmin();
    }

    private function requireAdmin(): void
    {
        if (!isset($_SESSION['users']) || ($_SESSION['users']['role'] ?? '') !== 'admin') {
            $_SESSION['flash_message'] = [
                'type' => 'danger',
                'text' => 'Acesso negado. Somente administradores.'
            ];
            header('Location: ' . BASE_URL . '/');
            exit;
        }
    }

    public function index()
    {
        $produtos = $this->productModel->getAllForAdmin(200, 0);
        $this->loadPartial('Painel/produtos/index', [
            'title' => 'Produtos - Dashboard',
            'produtos' => $produtos
        ]);
    }

    public function edit($id)
    {
        $id = (int)$id;
        $produto = $this->productModel->findById($id);
        if (!$produto) {
            $_SESSION['flash_message'] = ['type'=>'danger','text'=>'Produto não encontrado'];
            header('Location: ' . BASE_URL . '/dashboard/produtos');
            exit;
        }
        $this->loadPartial('Painel/produtos/editar', [
            'title' => 'Editar Produto - Dashboard',
            'produto' => $produto
        ]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/dashboard/produtos');
            exit;
        }
        $id = (int)$id;
        $payload = [
            'name' => $_POST['name'] ?? null,
            'price' => isset($_POST['price']) ? (float)$_POST['price'] : null,
            'brand' => $_POST['brand'] ?? null,
            'stock_quantity' => isset($_POST['stock_quantity']) ? (int)$_POST['stock_quantity'] : null,
            'active' => isset($_POST['active']) ? (int)$_POST['active'] : null,
            'category_id' => isset($_POST['category_id']) ? (int)$_POST['category_id'] : null,
            'featured' => isset($_POST['featured']) ? (int)$_POST['featured'] : null,
        ];
        // remove nulls
        $payload = array_filter($payload, fn($v) => $v !== null);
        $ok = $this->productModel->updateBasic($id, $payload);
        $_SESSION['flash_message'] = $ok
            ? ['type'=>'success','text'=>'Produto atualizado com sucesso']
            : ['type'=>'danger','text'=>'Falha ao atualizar produto'];
        header('Location: ' . BASE_URL . '/dashboard/produtos');
        exit;
    }

    public function toggleFeatured($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/dashboard/produtos');
            exit;
        }
        $id = (int)$id;
        $to = isset($_POST['featured']) ? (bool)$_POST['featured'] : true;
        $ok = $this->productModel->setFeatured($id, $to);
        header('Content-Type: application/json');
        echo json_encode(['success' => (bool)$ok]);
        exit;
    }
}
