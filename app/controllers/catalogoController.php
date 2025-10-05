<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;
use App\Models\Category;

/**
 * CatalogoController - URBANSTREET
 * Controller do catálogo de produtos
 */
class CatalogoController extends Controller
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
            'title' => 'Catalogo - URBANSTREET',
            'featuredProducts' => $featuredProducts,
            'categories' => $categories
        ]);
    }
    public function detalhes($id)
    {
        // Detalhes do produto
        $product = $this->productModel->findById($id);
        
        if (!$product) {
            // Redirecionar se o produto não for encontrado
            header("Location: /catalogo");
            exit;
        }
        
        $this->loadView('catalogo/detalhes', [
            'title' => $product['name'] . ' - URBANSTREET',
            'product' => $product
        ]);
    }   
    public function filtros()
    {
        $filters = $_GET; // Captura os filtros da URL
        $products = $this->productModel->getByFilters($filters);
        $this->loadView('catalogo/filtros', [
            'title' => 'Produtos Filtrados - URBANSTREET',
            'products' => $products,
            'appliedFilters' => $filters
        ]);
     }


    
 
}