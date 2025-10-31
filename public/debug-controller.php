<?php
/**
 * Debug do Controller de Produto - URBANSTREET
 */

require_once __DIR__ . '/../../vendor/autoload.php';

// Define constantes se não estiverem definidas
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', __DIR__ . '/../..');
    define('APP_PATH', ROOT_PATH . '/app');
    define('CONFIG_PATH', ROOT_PATH . '/config');
    define('PUBLIC_PATH', ROOT_PATH . '/public');
    define('BASE_URL', 'http://localhost/E-comerce/public');
}

require_once APP_PATH . '/helpers/functions.php';

use App\Controllers\ProductController;

echo "<h1>🔧 Debug ProductController::catalogo()</h1>";

try {
    $controller = new ProductController();
    
    echo "✅ ProductController instanciado<br>";
    
    // Simular chamada do método catalogo
    ob_start();
    $controller->catalogo();
    $output = ob_get_clean();
    
    if (empty($output)) {
        echo "⚠️ Saída vazia do método catalogo()<br>";
    } else {
        echo "✅ Método catalogo() executou e gerou saída<br>";
        echo "<strong>Tamanho da saída:</strong> " . strlen($output) . " caracteres<br>";
    }
    
} catch (Exception $e) {
    echo "❌ <strong>Erro:</strong> " . $e->getMessage() . "<br>";
    echo "<strong>Arquivo:</strong> " . $e->getFile() . ":" . $e->getLine() . "<br>";
    echo "<strong>Trace:</strong><br><pre>" . $e->getTraceAsString() . "</pre>";
}