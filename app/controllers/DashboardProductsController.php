<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Core\Database;
use App\Models\Product;
use App\Models\Category;

class DashboardProductsController extends Controller
{
    private \PDO $db;
    private Product $product;
    private Category $category;

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) session_start();

        if (!isset($_SESSION['users']) || ($_SESSION['users']['role'] ?? '') !== 'admin') {
            header('Location: ' . BASE_URL . '/');
            exit;
        }

        $this->db = Database::getInstance();
        $this->product = new Product();
        $this->category = new Category();
    }

    public function index()
    {
        $produtos = $this->product->getAllForAdmin();
        $this->loadPartial('Painel/produtos/index', compact('produtos'));
    }

    public function create()
    {
        $categorias = $this->category->getAllActive();
        $this->loadPartial('Painel/produtos/adicionar', compact('categorias'));
    }

    public function store()
    {
        // Normaliza campos da VIEW -> MODEL
        $data = [
            'nome'          => $_POST['name'],
            'marca'         => $_POST['brand'],
            'descricao'     => $_POST['description'],
            'categoria_id'  => $_POST['category_id'],
            'preco'         => $_POST['price'],
            'estoque'       => $_POST['stock_quantity'],
            'ativo'         => isset($_POST['active']) ? 1 : 0,
            'destaque'      => isset($_POST['featured']) ? 1 : 0,
            'criado_em'     => date('Y-m-d H:i:s'),
            'atualizado_em' => date('Y-m-d H:i:s')
        ];

        $productId = $this->product->create($data);

        if (!$productId) {
            $_SESSION['flash_message'] = ['type' => 'danger', 'text' => 'Erro ao salvar produto'];
            header('Location: ' . BASE_URL . '/dashboard/produtos/criar');
            exit;
        }

        // Upload de imagens
        if (!empty($_FILES['images']['name'][0])) {
            $this->handleImagesUpload($productId);
        }

        $_SESSION['flash_message'] = ['type'=>'success','text'=>'Produto criado com sucesso!'];
        header('Location: ' . BASE_URL . '/dashboard/produtos');
        exit;
    }

    private function handleImagesUpload(int $productId)
    {
        $dir = __DIR__ . '/../../public/uploads/produtos/';
        if (!is_dir($dir)) mkdir($dir, 0777, true);

        foreach ($_FILES['images']['tmp_name'] as $i => $tmp) {

            if (!is_uploaded_file($tmp)) continue;

            $ext = pathinfo($_FILES['images']['name'][$i], PATHINFO_EXTENSION);
            $fileName = uniqid('prod_', true) . '.' . strtolower($ext);

            move_uploaded_file($tmp, $dir . $fileName);

            $path = '/uploads/produtos/' . $fileName;

            $this->product->addImage($productId, $path);
        }
    }

    public function edit($id)
    {
        $produto = $this->product->findById($id);
        $categorias = $this->category->getAllActive();
        $imagens = $this->product->getImages($id);

        $this->loadPartial('Painel/produtos/editar', compact('produto','categorias','imagens'));
    }

    public function update($id)
    {
        $data = [
            'nome'         => $_POST['name'],
            'marca'        => $_POST['brand'],
            'descricao'    => $_POST['description'],
            'categoria_id' => $_POST['category_id'],
            'preco'        => $_POST['price'],
            'estoque'      => $_POST['stock_quantity'],
            'ativo'        => isset($_POST['active']) ? 1 : 0,
            'destaque'     => isset($_POST['featured']) ? 1 : 0
        ];

        $ok = $this->product->updateBasic($id, $data);

        if (!empty($_FILES['images']['name'][0])) {
            $this->handleImagesUpload($id);
        }

        $_SESSION['flash_message'] = [
            'type' => $ok ? 'success' : 'danger',
            'text' => $ok ? 'Produto atualizado!' : 'Erro ao atualizar.'
        ];

        header('Location: ' . BASE_URL . "/dashboard/produtos/$id/editar");
        exit;
    }

    public function deleteImage($id)
    {
        $image = $this->product->getImageById($id);
        if ($image) {
            $file = __DIR__ . '/../../public' . $image['image_path'];
            if (file_exists($file)) unlink($file);
        }

        $this->product->deleteImage($id);

        $_SESSION['flash_message'] = ['type'=>'success','text'=>'Imagem removida'];
        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }

    public function toggleFeatured($id)
    {
        $produto = $this->product->findById($id);
        $new = $produto['destaque'] ? 0 : 1;

        $this->product->setFeatured($id, $new);

        header('Location: ' . $_SERVER['HTTP_REFERER']);
        exit;
    }
}
