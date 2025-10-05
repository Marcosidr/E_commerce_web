<?php<?php

/**/**

 * URBANSTREET E-commerce - Sistema MVC * URBANSTREET E-commerce - Sistema MVC

 * Loja de Streetwear Autêntica * Loja de Streetwear Autêntica

 */ */



// Carrega o autoloader do Composer

require_once __DIR__ . '/vendor/autoload.php';

// Carrega o autoloader do Composer// Carrega o autoloader do Composer

// Configurações básicas do PHP

error_reporting(E_ALL);require_once __DIR__ . '/vendor/autoload.php';require_once __DIR__ . '/vendor/autoload.php';

ini_set('display_errors', 1);

session_start();



// Define constantes do projeto// Configurações básicas do PHP// Configurações básicas do PHP

define('ROOT_PATH', __DIR__);

define('APP_PATH', ROOT_PATH . '/app');error_reporting(E_ALL);error_reporting(E_ALL);

define('CONFIG_PATH', ROOT_PATH . '/config');

define('PUBLIC_PATH', ROOT_PATH . '/public');ini_set('display_errors', 1);ini_set('display_errors', 1);

define('BASE_URL', 'http://localhost/E-comerce');

session_start();session_start();

try {

    // Carrega as rotas

    $router = require_once CONFIG_PATH . '/routes.php';

    // Define constantes do projeto// Define constantes do projeto

    // Obtém a URL atual

    $url = $_SERVER['REQUEST_URI'];define('ROOT_PATH', __DIR__);define('ROOT_PATH', __DIR__);

    $url = str_replace('/E-comerce', '', $url); // Remove base path

    $url = parse_url($url, PHP_URL_PATH);define('APP_PATH', ROOT_PATH . '/app');define('APP_PATH', ROOT_PATH . '/app');

    $url = ltrim($url, '/');

    define('CONFIG_PATH', ROOT_PATH . '/config');define('CONFIG_PATH', ROOT_PATH . '/config');

    // Processa a rota

    $router->dispatch($url);define('PUBLIC_PATH', ROOT_PATH . '/public');define('PUBLIC_PATH', ROOT_PATH . '/public');

    

} catch (Exception $e) {define('BASE_URL', 'http://localhost/E-comerce');define('BASE_URL', 'http://localhost/E-comerce');

    // Página de erro

    http_response_code(500);

    require_once APP_PATH . '/views/errors/500.php';

    error_log("URBANSTREET Error: " . $e->getMessage());try {try {

}

?>    // Carrega as rotas    // Carrega as rotas

    $router = require_once CONFIG_PATH . '/routes.php';    $router = require_once CONFIG_PATH . '/routes.php';

        

    // Obtém a URL atual    // Obtém a URL atual

    $url = $_SERVER['REQUEST_URI'];    $url = $_SERVER['REQUEST_URI'];

    $url = str_replace('/E-comerce', '', $url); // Remove base path    $url = str_replace('/E-comerce', '', $url); // Remove base path

    $url = parse_url($url, PHP_URL_PATH);    $url = parse_url($url, PHP_URL_PATH);

    $url = ltrim($url, '/');    $url = ltrim($url, '/');

        

    // Processa a rota    // Processa a rota

    $router->dispatch($url);    $router->dispatch($url);

        

} catch (Exception $e) {} catch (Exception $e) {

    // Página de erro    // Página de erro

    http_response_code(500);    http_response_code(500);

    require_once APP_PATH . '/views/errors/500.php';    require_once APP_PATH . '/views/errors/500.php';

    error_log("URBANSTREET Error: " . $e->getMessage());    error_log("URBANSTREET Error: " . $e->getMessage());

}}

?>?>