<?php
/**
 * Página de debug para testar carregamento de dados
 */

require_once __DIR__ . '/../vendor/autoload.php';

// Define constantes se não estiverem definidas
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', __DIR__ . '/..');
    define('APP_PATH', ROOT_PATH . '/app');
    define('CONFIG_PATH', ROOT_PATH . '/config');
    define('PUBLIC_PATH', ROOT_PATH . '/public');
    define('BASE_URL', 'http://localhost/E-comerce/public');
}

require_once APP_PATH . '/helpers/functions.php';

use App\Models\Product;
use App\Models\Category;

echo "<h1>🔧 Debug URBANSTREET</h1>";

try {
    echo "<h2>📊 Teste de Modelos</h2>";
    
    // Testar conexão
    $productModel = new Product();
    $categoryModel = new Category();
    
    echo "✅ Modelos instanciados com sucesso<br>";
    
    // Testar categorias
    $categories = $categoryModel->getAllActive();
    echo "<strong>Categorias encontradas:</strong> " . count($categories) . "<br>";
    foreach ($categories as $cat) {
        echo "- {$cat['name']} (ID: {$cat['id']})<br>";
    }
    
    echo "<br>";
    
    // Testar produtos
    $products = $productModel->getByFilters([]);
    echo "<strong>Produtos encontrados:</strong> " . count($products) . "<br>";
    foreach ($products as $product) {
        echo "- {$product['name']} - " . formatPrice($product['price']) . " (ID: {$product['id']})<br>";
    }
    
    echo "<br>";
    
    // Testar marcas
    $brands = $productModel->getDistinctBrands();
    echo "<strong>Marcas encontradas:</strong> " . count($brands) . "<br>";
    foreach ($brands as $brand) {
        echo "- {$brand}<br>";
    }
    
    echo "<br>";
    
    // Testar imagens
    echo "<h2>🖼️ Teste de Imagens</h2>";
    echo "Imagem produto ID 1: " . getProductImageUrl(1) . "<br>";
    echo "Imagem produto ID 999: " . getProductImageUrl(999) . "<br>";
    
} catch (Exception $e) {
    echo "❌ <strong>Erro:</strong> " . $e->getMessage() . "<br>";
    echo "<strong>Trace:</strong><br><pre>" . $e->getTraceAsString() . "</pre>";
}