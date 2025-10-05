<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Category;

/**
 * HomeController - URBANSTREET
 * Controller da página inicial
 */
class HomeController extends Controller
{
    private $productModel;
    private $categoryModel;
    
    public function __construct()
    {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }
    
    /**
     * Página inicial - Estilo Urbano Autêntico
     */
    public function index()
    {
        // Produtos em destaque
        $featuredProducts = $this->productModel->getFeatured(4);
        
        // Categorias principais
        $categories = $this->categoryModel->getMainCategories();
        
        $this->loadView('home/index', [
            'title' => 'URBANSTREET - Estilo Urbano Autêntico',
            'metaDescription' => 'Descubra as últimas tendências em streetwear. Moda que representa sua atitude.',
            'featuredProducts' => $featuredProducts,
            'categories' => $categories,
            'pageClass' => 'home-page'
        ]);
    }
    
    /**
     * Página sobre nós
     */
    public function sobre()
    {
        $this->loadView('home/sobre', [
            'title' => 'Sobre Nós - URBANSTREET',
            'metaDescription' => 'Conheça a história da URBANSTREET e nossa paixão pela cultura urbana.',
            'pageClass' => 'about-page'
        ]);
    }
    
    /**
     * Página de contato
     */
    public function contato()
    {
        $success = false;
        $errors = [];
        
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            // Processa formulário de contato
            $name = trim($_POST['name'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $message = trim($_POST['message'] ?? '');
            
            // Validação
            if (empty($name)) $errors[] = 'Nome é obrigatório';
            if (empty($email)) $errors[] = 'Email é obrigatório';
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email inválido';
            if (empty($message)) $errors[] = 'Mensagem é obrigatória';
            
            if (empty($errors)) {
                // Aqui você pode enviar email, salvar no banco, etc.
                $success = true;
            }
        }
        
        $this->loadView('home/contato', [
            'title' => 'Contato - URBANSTREET',
            'metaDescription' => 'Entre em contato conosco. Estamos aqui para ajudar.',
            'success' => $success,
            'errors' => $errors,
            'pageClass' => 'contact-page'
        ]);
    }
    
    /**
     * Newsletter signup (AJAX)
     */
    public function newsletter()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && $this->isAjax()) {
            $email = trim($_POST['email'] ?? '');
            
            if (empty($email)) {
                $this->jsonResponse(['success' => false, 'message' => 'Email é obrigatório'], 400);
            }
            
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->jsonResponse(['success' => false, 'message' => 'Email inválido'], 400);
            }
            
            // Aqui você salvaria o email na newsletter
            // Por enquanto, apenas simula sucesso
            $this->jsonResponse([
                'success' => true, 
                'message' => 'Obrigado! Você receberá nossas novidades em primeira mão.'
            ]);
        }
        
        $this->jsonResponse(['success' => false, 'message' => 'Método não permitido'], 405);
    }
}