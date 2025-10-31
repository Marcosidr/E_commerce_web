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
$router->add('/^login$/', 'Login', 'login');
$router->add('/^login\/verify$/', 'Login', 'verify');
$router->add('/^cadastro$/', 'Registro', 'cadastro');
$router->add('/^cadastro\/create$/', 'Registro', 'create');


return $router;
// $router->get('/admin', [AdminController::class, 'dashboard']);