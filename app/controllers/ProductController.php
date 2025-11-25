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
        // Filtros via query string
        $filters = [
            'category'  => $_GET['category'] ?? null,
            'brand'     => $_GET['brand'] ?? null,
            'price_min' => isset($_GET['min']) ? (float)$_GET['min'] : null,
            'price_max' => isset($_GET['max']) ? (float)$_GET['max'] : null,
            'search'    => trim($_GET['q'] ?? ''),
            'sort'      => $_GET['sort'] ?? 'recent'
        ];

        $products   = $this->productModel->getByFilters($filters);
        $categories = $this->categoryModel->getAllActive();
        $brands     = $this->productModel->getDistinctBrands();
        
        $this->loadView('products/catalogo', [
            'title' => 'Catálogo - URBANSTREET',
            'metaDescription' => 'Explore nossa coleção completa de streetwear. Tênis, camisetas, moletons e muito mais.',
            'products' => $products,
            'categories' => $categories,
            'brands' => $brands,
            'filters' => $filters,
            'pageClass' => 'catalog-page'
        ]);
    }
    

    
 
    public function produto($id = null)
    {
        if (!$id) {
            $this->redirect('/catalogo');
        }
        
        $product = $this->productModel->findById($id);
        if (!$product || !$product['ativo']) {
            $this->redirect('/catalogo');
        }
        
        // Produtos relacionados (mesma categoria)
        $relatedProducts = $this->productModel->getRelated($product['categoria_id'], $product['id'], 4);
        
        $this->loadView('products/produto', [
            'title' => $product['nome'] . ' - URBANSTREET',
            'metaDescription' => substr(strip_tags($product['descricao']), 0, 150),
            'product' => $product,
            'relatedProducts' => $relatedProducts,
            'pageClass' => 'product-page'
        ]);
    }
    
    
    public function redirectToCategory($categorySlug = null)
    {
        // Mapear slugs para IDs das categorias
        $categoryMap = [
            'tenis' => 1,
            'camisetas' => 2,
            'moletons' => 3,
            'calcas' => 4,
            'acessorios' => 5
        ];
        
        if ($categorySlug && isset($categoryMap[$categorySlug])) {
            // Redirecionar para catálogo com filtro de categoria
            $categoryId = $categoryMap[$categorySlug];
            $this->redirect("/catalogo?category={$categoryId}");
        } else {
            // Fallback para catálogo geral
            $this->redirect('/catalogo');
        }
    }
}