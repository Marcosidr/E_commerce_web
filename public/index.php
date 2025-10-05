<?php
/**
 * URBANSTREET E-commerce - Sistema MVC
 * Loja de Streetwear Autêntica
 */

// Carrega o autoloader do Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Configurações básicas do PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();

// Define constantes do projeto
define('ROOT_PATH', realpath(__DIR__ . '/..')); // sobe um nível
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('BASE_URL', 'http://localhost/E-comerce/public');


try {
    // Carrega as rotas
    $router = require_once CONFIG_PATH . '/routes.php';
    
    // Obtém a URL atual
    $url = $_SERVER['REQUEST_URI'];
    $url = str_replace('/E-comerce/public', '', $url); // Remove base path
    $url = parse_url($url, PHP_URL_PATH);
    $url = ltrim($url, '/');
    
    // Processa a rota
    $router->dispatch($url);
    
} catch (Exception $e) {
    // Página de erro
    http_response_code(500);
    require_once APP_PATH . '/views/errors/500.php';
    error_log("URBANSTREET Error: " . $e->getMessage());
}
?>