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
$router->add('/^categoria\/(?P<category_slug>[a-z0-9-]+)$/', 'Product', 'categoria');
$router->add('/^produto\/(?P<id>\d+)$/', 'Product', 'produto');
$router->add('/^buscar$/', 'Product', 'buscar');

// Rotas para compatibilidade com o design original
$router->add('/^categoria\/tenis$/', 'Product', 'categoria');
$router->add('/^categoria\/camisetas$/', 'Product', 'categoria');
$router->add('/^categoria\/moletons$/', 'Product', 'categoria');
$router->add('/^categoria\/calcas$/', 'Product', 'categoria');
$router->add('/^categoria\/acessorios$/', 'Product', 'categoria');
$router->add('/^produto\/(?P<id>\d+)$/', 'Product', 'produto');
$router->add('/^categorias$/', 'Categorias', 'categorias');



return $router;
// $router->get('/admin', [AdminController::class, 'dashboard']);