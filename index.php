<?php
/**
 * Arquivo de inicialização do projeto E-commerce
 * Inclui o autoloader do Composer e configurações básicas
 */

// Carrega o autoloader do Composer
require_once __DIR__ . '/vendor/autoload.php';

// Configurações básicas do PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Define constantes do projeto
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('PUBLIC_PATH', ROOT_PATH . '/public');

// Exemplo de uso do autoloader PSR-4
// Agora você pode usar classes como: new App\Controllers\ProductController();
// O Composer vai carregar automaticamente de app/Controllers/ProductController.php

echo "Sistema E-commerce inicializado com sucesso!\n";
echo "Autoloader PSR-4 configurado para namespace App\\\n";
?>