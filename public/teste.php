<?php
echo "<h1>🔧 TESTE URBANSTREET</h1>";
echo "<p><strong>PHP Version:</strong> " . PHP_VERSION . "</p>";
echo "<p><strong>ROOT_PATH:</strong> " . (defined('ROOT_PATH') ? ROOT_PATH : 'NÃO DEFINIDO') . "</p>";
echo "<p><strong>APP_PATH:</strong> " . (defined('APP_PATH') ? APP_PATH : 'NÃO DEFINIDO') . "</p>";

// Testar autoloader
echo "<p><strong>Autoloader:</strong> ";
try {
    require_once __DIR__ . '/../vendor/autoload.php';
    echo "✅ OK";
} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage();
}
echo "</p>";

// Testar constantes
define('ROOT_PATH', realpath(__DIR__ . '/..'));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');

echo "<p><strong>Diretórios:</strong></p>";
echo "<ul>";
echo "<li>ROOT: " . (is_dir(ROOT_PATH) ? "✅" : "❌") . " " . ROOT_PATH . "</li>";
echo "<li>APP: " . (is_dir(APP_PATH) ? "✅" : "❌") . " " . APP_PATH . "</li>";
echo "<li>CONFIG: " . (is_dir(CONFIG_PATH) ? "✅" : "❌") . " " . CONFIG_PATH . "</li>";
echo "</ul>";

// Testar Router
echo "<p><strong>Router:</strong> ";
try {
    $routerFile = CONFIG_PATH . '/routes.php';
    if (file_exists($routerFile)) {
        $router = require_once $routerFile;
        echo "✅ Carregado com sucesso";
    } else {
        echo "❌ Arquivo não encontrado: " . $routerFile;
    }
} catch (Exception $e) {
    echo "❌ ERRO: " . $e->getMessage();
}
echo "</p>";

phpinfo();
?>