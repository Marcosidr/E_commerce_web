<?php

namespace App\Controllers;

use App\Core\Controller;
use App\Models\Product;

class ApiController extends Controller
{
    private $productModel;
    
    public function __construct()
    {
        $this->productModel = new Product();
    }
    
    /**
     * Retorna dados de um produto em JSON
     */
    public function product($id = null)
    {
        header('Content-Type: application/json; charset=utf-8');
        
        if (!$id) {
            echo json_encode(['success' => false, 'message' => 'ID não fornecido']);
            return;
        }
        
        $product = $this->productModel->findById($id);
        
        if (!$product || !$product['ativo']) {
            echo json_encode(['success' => false, 'message' => 'Produto não encontrado']);
            return;
        }
        
        echo json_encode([
            'success' => true,
            'product' => $product
        ]);
    }
}
