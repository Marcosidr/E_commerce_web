<?php

/**
 * Rotas - URBANSTREET
 */

use App\Core\Router;

$router = new Router();

// Página inicial
$router->add('/^$/', 'Home', 'index');
$router->add('/^home$/', 'Home', 'index');

// Páginas institucionais
$router->add('/^sobre$/', 'Home', 'sobre');
$router->add('/^contato$/', 'Home', 'contato');
$router->add('/^newsletter$/', 'Home', 'newsletter');

// Catálogo e produtos
$router->add('/^catalogo$/', 'Product', 'catalogo');
$router->add('/^produto\/(?P<id>\d+)$/', 'Product', 'produto');

// Redirecionamentos para catálogo com filtros (compatibilidade)
$router->add('/^categoria\/(?P<category_slug>[a-z0-9-]+)$/', 'Product', 'redirectToCategory');

// Autenticação
$router->add('/^login$/', 'Login', 'login');
$router->add('/^login\/verify$/', 'Login', 'verify');
$router->add('/^cadastro$/', 'Registro', 'cadastro');
$router->add('/^cadastro\/create$/', 'Registro', 'create');

// Carrinho de compras
$router->add('/^carrinho$/', 'Carrinho', 'index');
$router->add('/^carrinho\/adicionar$/', 'Carrinho', 'adicionar');
$router->add('/^carrinho\/atualizar$/', 'Carrinho', 'atualizar');
$router->add('/^carrinho\/remover$/', 'Carrinho', 'remover');
$router->add('/^carrinho\/limpar$/', 'Carrinho', 'limpar');
$router->add('/^carrinho\/dados$/', 'Carrinho', 'dados');


return $router;
// $router->get('/admin', [AdminController::class, 'dashboard']);