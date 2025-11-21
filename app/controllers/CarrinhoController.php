<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;

/**
 * CarrinhoController - URBANSTREET
 * Gerencia o carrinho de compras
 */
class CarrinhoController extends Controller
{
    private $productModel;
    
    public function __construct()
    {
        $this->productModel = new Product();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Inicializar carrinho se não existir
        if (!isset($_SESSION['carrinho'])) {
            $_SESSION['carrinho'] = [];
        }
    }
    
    /**
     * Exibe o carrinho
     */
    public function index()
    {
        $carrinho = $this->getCarrinhoDetalhado();
        $total = $this->calcularTotal();
        
        // Atualizar contador no header
        $this->atualizarContador();
        
        $this->loadView('carrinho', [
            'title' => 'Meu Carrinho - URBANSTREET',
            'metaDescription' => 'Finalize sua compra na URBANSTREET',
            'carrinho' => $carrinho,
            'total' => $total,
            'pageClass' => 'cart-page'
        ]);
    }
    
    /**
     * Adiciona produto ao carrinho via AJAX
     */
    public function adicionar()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            exit;
        }
        
        $produtoId = (int)($_POST['produto_id'] ?? 0);
        $quantidade = (int)($_POST['quantidade'] ?? 1);
        $tamanho = $_POST['tamanho'] ?? '';
        if (!is_string($tamanho) || trim($tamanho) === '') {
            $tamanho = 'UNICO';
        }
        
        // Validações
        if ($produtoId <= 0) {
            echo json_encode(['success' => false, 'message' => 'Produto inválido']);
            exit;
        }
        
        if ($quantidade <= 0) {
            echo json_encode(['success' => false, 'message' => 'Quantidade inválida']);
            exit;
        }
        
        // Verificar se produto existe
        $produto = $this->productModel->findById($produtoId);
        if (!$produto) {
            echo json_encode(['success' => false, 'message' => 'Produto não encontrado']);
            exit;
        }
        
        // Verificar estoque
        if ($produto['stock_quantity'] < $quantidade) {
            echo json_encode(['success' => false, 'message' => 'Estoque insuficiente']);
            exit;
        }
        
        // Chave única para o item (produto + tamanho)
        $itemKey = $produtoId . '_' . $tamanho;
        
        // Adicionar ao carrinho
        if (isset($_SESSION['carrinho'][$itemKey])) {
            $_SESSION['carrinho'][$itemKey]['quantidade'] += $quantidade;
        } else {
            $_SESSION['carrinho'][$itemKey] = [
                'produto_id' => $produtoId,
                'quantidade' => $quantidade,
                'tamanho' => $tamanho,
                'preco' => $produto['price'],
                'nome' => $produto['name']
            ];
        }
        
        $this->atualizarContador();
        $totalItens = $this->contarItens();
        $totalCarrinho = $this->calcularTotal();
        
        echo json_encode([
            'success' => true,
            'message' => 'Produto adicionado ao carrinho!',
            'totalItens' => $totalItens,
            'totalCarrinho' => $totalCarrinho
        ]);
        exit;
    }
    
    /**
     * Atualiza quantidade de um item
     */
    public function atualizar()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            exit;
        }
        
        $itemKey = $_POST['item_key'] ?? '';
        $quantidade = (int)($_POST['quantidade'] ?? 0);
        
        if (!isset($_SESSION['carrinho'][$itemKey])) {
            echo json_encode(['success' => false, 'message' => 'Item não encontrado']);
            exit;
        }
        
        if ($quantidade <= 0) {
            // Remove o item se quantidade for 0 ou negativa
            unset($_SESSION['carrinho'][$itemKey]);
            $message = 'Item removido do carrinho';
        } else {
            $_SESSION['carrinho'][$itemKey]['quantidade'] = $quantidade;
            $message = 'Quantidade atualizada';
        }
        
        $this->atualizarContador();
        
        echo json_encode([
            'success' => true,
            'message' => $message,
            'totalItens' => $this->contarItens(),
            'totalCarrinho' => $this->calcularTotal()
        ]);
        exit;
    }
    
    /**
     * Remove item do carrinho
     */
    public function remover()
    {
        header('Content-Type: application/json');
        
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            echo json_encode(['success' => false, 'message' => 'Método não permitido']);
            exit;
        }
        
        $itemKey = $_POST['item_key'] ?? '';
        
        if (!isset($_SESSION['carrinho'][$itemKey])) {
            echo json_encode(['success' => false, 'message' => 'Item não encontrado']);
            exit;
        }
        
        unset($_SESSION['carrinho'][$itemKey]);
        $this->atualizarContador();
        
        echo json_encode([
            'success' => true,
            'message' => 'Item removido do carrinho',
            'totalItens' => $this->contarItens(),
            'totalCarrinho' => $this->calcularTotal()
        ]);
        exit;
    }
    
    /**
     * Limpa todo o carrinho
     */
    public function limpar()
    {
        $_SESSION['carrinho'] = [];
        $this->atualizarContador();
        
        if (isset($_POST['ajax'])) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'message' => 'Carrinho limpo']);
            exit;
        }
        
        $_SESSION['success'] = 'Carrinho limpo com sucesso!';
        header('Location: ' . BASE_URL . '/carrinho');
        exit;
    }
    
    /**
     * Retorna dados do carrinho via AJAX
     */
    public function dados()
    {
        header('Content-Type: application/json');
        
        echo json_encode([
            'success' => true,
            'totalItens' => $this->contarItens(),
            'totalCarrinho' => $this->calcularTotal(),
            'itens' => $this->getCarrinhoDetalhado()
        ]);
        exit;
    }

    /**
     * Etapa de checkout: exige usuário autenticado
     */
    public function checkout()
    {
        $carrinho = $this->getCarrinhoDetalhado();

        if (empty($carrinho)) {
            $_SESSION['error'] = 'Seu carrinho está vazio. Adicione produtos para continuar.';
            header('Location: ' . BASE_URL . '/carrinho');
            exit;
        }

        if (!isset($_SESSION['users'])) {
            $_SESSION['flash_message'] = [
                'type' => 'warning',
                'text' => 'Faça login ou crie uma conta para finalizar sua compra.'
            ];
            $_SESSION['redirect_after_login'] = '/checkout';
            header('Location: ' . BASE_URL . '/login');
            exit;
        }

        $this->loadView('checkout', [
            'title' => 'Finalizar compra - URBANSTREET',
            'metaDescription' => 'Revise seus dados e conclua o pedido',
            'carrinho' => $carrinho,
            'total' => $this->calcularTotal(),
            'pageClass' => 'checkout-page'
        ]);
    }
    
    /**
     * Conta total de itens no carrinho
     */
    private function contarItens()
    {
        $total = 0;
        foreach ($_SESSION['carrinho'] as $item) {
            $total += $item['quantidade'];
        }
        return $total;
    }
    
    /**
     * Calcula total do carrinho
     */
    private function calcularTotal()
    {
        $total = 0;
        foreach ($_SESSION['carrinho'] as $item) {
            $total += $item['preco'] * $item['quantidade'];
        }
        return $total;
    }
    
    /**
     * Retorna carrinho com detalhes dos produtos
     */
    private function getCarrinhoDetalhado()
    {
        $carrinho = [];
        
        foreach ($_SESSION['carrinho'] as $key => $item) {
            $produto = $this->productModel->findById($item['produto_id']);
            
            if ($produto) {
                $carrinho[$key] = [
                    'produto_id' => $item['produto_id'],
                    'quantidade' => $item['quantidade'],
                    'tamanho' => $item['tamanho'],
                    'preco' => $item['preco'],
                    'nome' => $produto['name'],
                    'marca' => $produto['brand'],
                    'imagem' => $produto['image'] ?? null,
                    'slug' => $produto['slug'],
                    'subtotal' => $item['preco'] * $item['quantidade']
                ];
            }
        }
        
        return $carrinho;
    }
    
    /**
     * Atualiza contador do carrinho na sessão
     */
    private function atualizarContador()
    {
        $total = 0;
        
        if (isset($_SESSION['carrinho']) && is_array($_SESSION['carrinho'])) {
            foreach ($_SESSION['carrinho'] as $item) {
                $total += (int) $item['quantidade'];
            }
        }
        
        $_SESSION['carrinho_count'] = $total;
    }
}