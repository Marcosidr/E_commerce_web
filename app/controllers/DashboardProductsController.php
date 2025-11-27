<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Category; // adicionado

class DashboardProductsController extends Controller
{
    private Product $productModel;
    private Category $categoryModel;

    public function __construct()
    {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
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

    // lista produtos + envia categorias (útil pro modal)
    public function index()
    {
        $produtos = $this->productModel->getAllForAdmin(200, 0);
        $categorias = $this->categoryModel->getAllActive(); // supondo que exista
        $this->loadPartial('Painel/produtos/index', [
            'title' => 'Produtos - Dashboard',
            'produtos' => $produtos,
            'categorias' => $categorias
        ]);
    }

    // FORMULÁRIO (página separada) - se preferir modal, você pode incluir o modal na index
    public function create()
    {
        $categorias = $this->categoryModel->getAllActive();
        $this->loadPartial('Painel/produtos/adicionar', [
            'title' => 'Adicionar Produto - Dashboard',
            'categorias' => $categorias
        ]);
    }

    // RECEBE POST para criar produto
    public function store()
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/dashboard/produtos');
            exit;
        }

        // Validação obrigatória
        // aceita nomes em português ou inglês (compatibilidade)
        $nome = trim($_POST['nome'] ?? $_POST['name'] ?? '');
        $descricao = trim($_POST['descricao'] ?? $_POST['description'] ?? '');
        $preco = isset($_POST['preco']) ? (float)$_POST['preco'] : (isset($_POST['price']) ? (float)$_POST['price'] : 0);
        $marca = trim($_POST['marca'] ?? $_POST['brand'] ?? '');
        $estoque = isset($_POST['estoque']) ? (int)$_POST['estoque'] : (isset($_POST['stock_quantity']) ? (int)$_POST['stock_quantity'] : 0);
        $categoria_id = isset($_POST['categoria_id']) ? (int)$_POST['categoria_id'] : (isset($_POST['category_id']) ? (int)$_POST['category_id'] : null);

        // Validações
        $erros = [];
        if (empty($nome) || strlen($nome) < 3) {
            $erros[] = 'Nome deve ter no mínimo 3 caracteres';
        }
        if (empty($descricao) || strlen($descricao) < 10) {
            $erros[] = 'Descrição deve ter no mínimo 10 caracteres';
        }
        if ($preco <= 0) {
            $erros[] = 'Preço deve ser maior que 0';
        }
        if (empty($marca) || strlen($marca) < 2) {
            $erros[] = 'Marca é obrigatória';
        }
        if ($estoque < 0) {
            $erros[] = 'Estoque não pode ser negativo';
        }
        if (empty($categoria_id)) {
            $erros[] = 'Categoria é obrigatória';
        }

        // Se houver erros, retorna para formulário
        if (!empty($erros)) {
            $_SESSION['flash_message'] = [
                'type' => 'danger',
                'text' => implode('<br>', $erros)
            ];
            header('Location: ' . BASE_URL . '/dashboard/produtos/criar');
            exit;
        }

        // montar payload com nomes das colunas do DB
        $payload = [
            'nome' => $nome,
            'descricao' => $descricao,
            'preco' => $preco,
            'marca' => $marca,
            'estoque' => $estoque,
            'ativo' => isset($_POST['ativo']) ? (int)$_POST['ativo'] : (isset($_POST['active']) ? (int)$_POST['active'] : 1),
            'categoria_id' => $categoria_id,
            'destaque' => isset($_POST['destaque']) ? (int)$_POST['destaque'] : (isset($_POST['featured']) ? (int)$_POST['featured'] : 0),
            'criado_em' => date('Y-m-d H:i:s'),
            'atualizado_em' => date('Y-m-d H:i:s'),
        ];

        // criar no banco e obter id
        $newId = $this->productModel->create($payload);

        if ($newId === false) {
            $_SESSION['flash_message'] = ['type'=>'danger','text'=>'Falha ao criar produto'];
            header('Location: ' . BASE_URL . '/dashboard/produtos/criar');
            exit;
        }

        // upload imagens (se houver) - aceita 'imagens' (pt) ou 'images' (en)
        if ($newId) {
            if (!empty($_FILES['imagens']['name'][0])) {
                $this->uploadProductImages($newId, $_FILES['imagens'], $categoria_id);
            } elseif (!empty($_FILES['images']['name'][0])) {
                $this->uploadProductImages($newId, $_FILES['images'], $categoria_id);
            }
        }

        $_SESSION['flash_message'] = ['type'=>'success','text'=>'Produto criado com sucesso!'];
        
        // Redirecionar para catálogo filtrado por categoria
        header('Location: ' . BASE_URL . '/catalogo?category=' . $categoria_id);
        exit;
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

        // CARREGAR GALERIA DE IMAGENS
        $produto['images'] = $this->productModel->getImages($id);
        
        // Carregar categorias
        $categorias = $this->categoryModel->getAllActive();

        $this->loadPartial('Painel/produtos/editar', [
            'title' => 'Editar Produto - Dashboard',
            'produto' => $produto,
            'categorias' => $categorias
        ]);
    }

    public function update($id)
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            header('Location: ' . BASE_URL . '/dashboard/produtos');
            exit;
        }

        $logFile = __DIR__ . '/../../../upload_log.txt';
        file_put_contents($logFile, "\n=== UPDATE START id=$id ===" . PHP_EOL, FILE_APPEND);
        file_put_contents($logFile, "FILES keys: " . implode(', ', array_keys($_FILES)) . PHP_EOL, FILE_APPEND);
        if (isset($_FILES['imagens'])) {
            file_put_contents($logFile, "FILES['imagens']['name']: " . implode(', ', $_FILES['imagens']['name'] ?? []) . PHP_EOL, FILE_APPEND);
        }

        $id = (int)$id;
        
        // aceita nomes em português ou inglês (compatibilidade)
        $nome = trim($_POST['nome'] ?? $_POST['name'] ?? '');
        $descricao = trim($_POST['descricao'] ?? $_POST['description'] ?? '');
        $preco = isset($_POST['preco']) ? (float)$_POST['preco'] : (isset($_POST['price']) ? (float)$_POST['price'] : 0);
        $marca = trim($_POST['marca'] ?? $_POST['brand'] ?? '');
        $estoque = isset($_POST['estoque']) ? (int)$_POST['estoque'] : (isset($_POST['stock_quantity']) ? (int)$_POST['stock_quantity'] : 0);
        $categoria_id = isset($_POST['categoria_id']) ? (int)$_POST['categoria_id'] : (isset($_POST['category_id']) ? (int)$_POST['category_id'] : null);

        // Validações
        $erros = [];
        if (empty($nome) || strlen($nome) < 3) {
            $erros[] = 'Nome deve ter no mínimo 3 caracteres';
        }
        if (empty($descricao) || strlen($descricao) < 10) {
            $erros[] = 'Descrição deve ter no mínimo 10 caracteres';
        }
        if ($preco <= 0) {
            $erros[] = 'Preço deve ser maior que 0';
        }
        if (empty($marca) || strlen($marca) < 2) {
            $erros[] = 'Marca é obrigatória';
        }
        if ($estoque < 0) {
            $erros[] = 'Estoque não pode ser negativo';
        }
        if (empty($categoria_id)) {
            $erros[] = 'Categoria é obrigatória';
        }

        // Se houver erros, retorna para formulário
        if (!empty($erros)) {
            $_SESSION['flash_message'] = [
                'type' => 'danger',
                'text' => implode('<br>', $erros)
            ];
            header('Location: ' . BASE_URL . '/dashboard/produtos/' . $id . '/editar');
            exit;
        }

        $payload = [
            'nome'         => $nome,
            'descricao'    => $descricao,
            'preco'        => $preco,
            'marca'        => $marca,
            'estoque'      => $estoque,
            'ativo'        => isset($_POST['ativo']) ? (int)$_POST['ativo'] : (isset($_POST['active']) ? (int)$_POST['active'] : 1),
            'categoria_id' => $categoria_id,
            'destaque'     => isset($_POST['destaque']) ? (int)$_POST['destaque'] : (isset($_POST['featured']) ? (int)$_POST['featured'] : 0),
        ];

        $this->productModel->updateBasic($id, $payload);

        // UPLOAD DE IMAGENS (MULTIPLAS) - aceita 'imagens' ou 'images'
        if (!empty($_FILES['imagens']['name'][0])) {
            $this->uploadProductImages($id, $_FILES['imagens'], $categoria_id);
        } elseif (!empty($_FILES['images']['name'][0])) {
            $this->uploadProductImages($id, $_FILES['images'], $categoria_id);
        }

        $_SESSION['flash_message'] = ['type'=>'success','text'=>'Produto atualizado com sucesso!'];
        header('Location: ' . BASE_URL . '/dashboard/produtos/' . $id . '/editar');
        exit;
        
    }

    /**
     * Upload com organização por pasta de categoria.
     * $categoryId pode ser null → salva em "diversos"
     */
    private function uploadProductImages(int $productId, array $files, ?int $categoryId = null): void
    {
        // map de categorias → pastas
        $map = [
            1 => 'tenis',
            2 => 'camisetas',
            3 => 'moletons',
            4 => 'calcas',
            5 => 'diversos'
        ];

        $folder = $map[$categoryId] ?? 'diversos';
        $dir = __DIR__ . '/../../../public/images/products/' . $folder;

        // Log file
        $logFile = __DIR__ . '/../../../upload_log.txt';
        $log = function($msg) use ($logFile) {
            file_put_contents($logFile, '[' . date('Y-m-d H:i:s') . '] ' . $msg . "\n", FILE_APPEND);
        };

        $log("=== UPLOAD START product_id=$productId, category=$folder, dir=$dir ===");

        if (!is_dir($dir)) {
            mkdir($dir, 0777, true);
            $log("Criada pasta: $dir");
        }

        if (!isset($files['tmp_name']) || !is_array($files['tmp_name'])) {
            $log("ERRO: files['tmp_name'] não é array ou não existe");
            return;
        }

        foreach ($files['tmp_name'] as $i => $tmpName) {
            $log("\n--- File $i ---");
            $log("tmp_name: $tmpName");
            $log("name: " . ($files['name'][$i] ?? 'N/A'));
            $log("error: " . ($files['error'][$i] ?? 'N/A'));
            $log("size: " . ($files['size'][$i] ?? 'N/A'));

            if (!$tmpName) {
                $log("tmp_name vazio, pulando");
                continue;
            }

            $errorCode = $files['error'][$i] ?? 0;
            if ($errorCode !== UPLOAD_ERR_OK) {
                $log("ERRO de upload: código $errorCode");
                continue;
            }

            $origName = $files['name'][$i] ?? 'unknown';
            $ext = pathinfo($origName, PATHINFO_EXTENSION);
            $safe = preg_replace('/[^a-zA-Z0-9_\-\.]/', '_', pathinfo($origName, PATHINFO_FILENAME));
            $name = time() . '_' . $safe . '.' . $ext;
            $path = $dir . DIRECTORY_SEPARATOR . $name;

            $log("Target path: $path");
            $log("is_uploaded_file: " . (is_uploaded_file($tmpName) ? 'true' : 'false'));
            $log("tmp file exists: " . (file_exists($tmpName) ? 'true' : 'false'));
            $log("dir exists: " . (is_dir($dir) ? 'true' : 'false'));
            $log("dir writable: " . (is_writable($dir) ? 'true' : 'false'));

            $moved = false;
            if (is_uploaded_file($tmpName)) {
                $moved = @move_uploaded_file($tmpName, $path);
                $log("move_uploaded_file result: " . ($moved ? 'true' : 'false'));
            }

            if (!$moved && file_exists($tmpName)) {
                $copied = @copy($tmpName, $path);
                $log("copy() fallback result: " . ($copied ? 'true' : 'false'));
                $moved = $copied;
                if ($moved) @unlink($tmpName);
            }

            $log("File exists after move/copy: " . (file_exists($path) ? 'true' : 'false'));

            if ($moved && file_exists($path)) {
                $relative = 'images/products/' . $folder . '/' . $name;
                $log("Salvando no BD: product_id=$productId, path=$relative");
                $this->productModel->addImage($productId, $relative);
                $log("Salvo no BD com sucesso");
            } else {
                $log("FALHA: arquivo não foi movido ou não existe após move/copy");
            }
        }

        $log("=== UPLOAD END ===\n");
    }

    public function deleteImage($imageId)
    {
        $imageId = (int)$imageId;

        // antes de deletar do banco, tenta remover arquivo físico
        $img = $this->productModel->getImageById($imageId); // implementado no model abaixo
        if ($img) {
            $file = __DIR__ . '/../../../public/' . $img['image_path'];
            if (file_exists($file)) @unlink($file);
        }

        $this->productModel->deleteImage($imageId);

        $_SESSION['flash_message'] = ['type'=>'success','text'=>'Imagem removida'];
        header('Location: ' . $_SERVER['HTTP_REFERER']);
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
