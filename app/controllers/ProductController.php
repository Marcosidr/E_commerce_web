<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Category;

/**
 * ProductController - URBANSTREET
 * Gerencia produtos da loja
 */
class ProductController extends Controller
{
    private $productModel;
    private $categoryModel;
    
    public function __construct()
    {
        $this->productModel = new Product();
        $this->categoryModel = new Category();
    }
    
    /**
     * Catálogo completo de produtos
     */
    public function catalogo()
    {
        $page = (int)($_GET['page'] ?? 1);
        $limit = 12;
        $offset = ($page - 1) * $limit;
        
        $products = $this->productModel->getActive($limit, $offset);
        $totalProducts = $this->productModel->countActive();
        $totalPages = ceil($totalProducts / $limit);
        
        $categories = $this->categoryModel->getAllActive();
        
        $this->loadView('products/catalogo', [
            'title' => 'Catálogo - URBANSTREET',
            'metaDescription' => 'Explore nossa coleção completa de streetwear. Tênis, camisetas, moletons e muito mais.',
            'products' => $products,
            'categories' => $categories,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'pageClass' => 'catalog-page'
        ]);
    }
    
    /**
     * Produtos por categoria
     */
    public function categoria($categorySlug = null)
    {
        if (!$categorySlug) {
            $this->redirect('/catalogo');
        }
        
        $category = $this->categoryModel->findBySlug($categorySlug);
        if (!$category) {
            $this->redirect('/catalogo');
        }
        
        $page = (int)($_GET['page'] ?? 1);
        $limit = 12;
        $offset = ($page - 1) * $limit;
        
        $products = $this->productModel->getByCategory($category['id'], $limit, $offset);
        $totalProducts = $this->productModel->countByCategory($category['id']);
        $totalPages = ceil($totalProducts / $limit);
        
        $this->loadView('products/categoria', [
            'title' => $category['name'] . ' - URBANSTREET',
            'metaDescription' => 'Explore nossa coleção de ' . strtolower($category['name']) . '. Produtos autênticos de streetwear.',
            'category' => $category,
            'products' => $products,
            'currentPage' => $page,
            'totalPages' => $totalPages,
            'pageClass' => 'category-page'
        ]);
    }
    
    /**
     * Detalhes do produto
     */
    public function produto($id = null)
    {
        if (!$id) {
            $this->redirect('/catalogo');
        }
        
        $product = $this->productModel->findById($id);
        if (!$product || !$product['active']) {
            $this->redirect('/catalogo');
        }
        
        // Produtos relacionados (mesma categoria)
        $relatedProducts = $this->productModel->getRelated($product['category_id'], $product['id'], 4);
        
        $this->loadView('products/produto', [
            'title' => $product['name'] . ' - URBANSTREET',
            'metaDescription' => substr(strip_tags($product['description']), 0, 150),
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'pageClass' => 'product-page'
        ]);
    }
    
    /**
     * Busca de produtos
     */
    public function buscar()
    {
        $query = trim($_GET['q'] ?? '');
        $products = [];
        
        if (!empty($query)) {
            $products = $this->productModel->search($query);
        }
        
        $this->loadView('products/buscar', [
            'title' => 'Busca: ' . $query . ' - URBANSTREET',
            'metaDescription' => 'Resultados da busca por: ' . $query,
            'query' => $query,
            'products' => $products,
            'pageClass' => 'search-page'
        ]);
    }
}