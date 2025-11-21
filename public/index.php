<?php

// Carrega o autoloader do Composer
require_once __DIR__ . '/../vendor/autoload.php';

// Configurações básicas do PHP
error_reporting(E_ALL);
ini_set('display_errors', 1);
date_default_timezone_set('America/Sao_Paulo');

// Inicia a sessão
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Define constantes do projeto
define('ROOT_PATH', realpath(__DIR__ . '/..'));
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('STORAGE_PATH', ROOT_PATH . '/storage');
define('UPLOAD_PATH', PUBLIC_PATH . '/uploads');
define('BASE_URL', 'http://localhost/E-comerce/public');

//debug 
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Carrega funções helper se existirem
$helperPath = APP_PATH . '/helpers/functions.php';
if (file_exists($helperPath)) {
    require_once $helperPath;
}

// Inicializa o contador do carrinho se não existir
if (!isset($_SESSION['carrinho_count'])) {
    $_SESSION['carrinho_count'] = 0;
}

try {
    // Carrega as rotas
    $routesPath = CONFIG_PATH . '/routes.php';
    if (!file_exists($routesPath)) {
        throw new Exception('Arquivo de rotas não encontrado.');
    }
    
    require_once $routesPath;
    
    // Verifica se o router foi criado
    if (!isset($router)) {
        throw new Exception('Router não foi inicializado.');
    }
    
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
    
    // Tenta carregar página de erro customizada
    $errorPage = APP_PATH . '/views/errors/500.php';
    if (file_exists($errorPage)) {
        require_once $errorPage;
    } else {
        // Página de erro padrão
        echo "<!DOCTYPE html>
        <html lang='pt-BR'>
        <head>
            <meta charset='UTF-8'>
            <meta name='viewport' content='width=device-width, initial-scale=1.0'>
            <title>Erro 500</title>
            <style>
                body { 
                    font-family: Arial, sans-serif; 
                    text-align: center; 
                    padding: 50px;
                    background: #f5f5f5;
                }
                .error-container {
                    background: white;
                    padding: 30px;
                    border-radius: 10px;
                    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
                    max-width: 800px;
                    margin: 0 auto;
                }
                h1 { color: #e74c3c; }
                .error-details {
                    background: #f8f9fa;
                    padding: 15px;
                    border-left: 4px solid #e74c3c;
                    text-align: left;
                    margin: 20px 0;
                    font-family: monospace;
                }
            </style>
        </head>
        <body>
            <div class='error-container'>
                <h1>⚠️ Erro 500 - Erro interno do servidor</h1>
                <div class='error-details'>
                    <strong>Mensagem:</strong> " . htmlspecialchars($e->getMessage()) . "<br>
                    <strong>Arquivo:</strong> " . htmlspecialchars($e->getFile()) . "<br>
                    <strong>Linha:</strong> " . $e->getLine() . "
                </div>
                <p><a href='" . BASE_URL . "'>← Voltar para a página inicial</a></p>
            </div>
        </body>
        </html>";
    }
    
    error_log("URBANSTREET Error: " . $e->getMessage());
}