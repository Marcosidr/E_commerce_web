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

// API - Produtos
$router->add('/^api\/product\/(?P<id>\d+)$/', 'Api', 'product');

// Redirecionamentos para catálogo com filtros (compatibilidade)
$router->add('/^categoria\/(?P<category_slug>[a-z0-9-]+)$/', 'Product', 'redirectToCategory');

// Autenticação
$router->add('/^login$/', 'Login', 'login');
$router->add('/^login\/verify$/', 'Login', 'verify');
$router->add('/^logout$/', 'Login', 'logout');
$router->add('/^cadastro$/', 'Registro', 'cadastro');
$router->add('/^cadastro\/create$/', 'Registro', 'create');

// Carrinho de compras
$router->add('/^carrinho$/', 'Carrinho', 'index');
$router->add('/^carrinho\/adicionar$/', 'Carrinho', 'adicionar');
$router->add('/^carrinho\/atualizar$/', 'Carrinho', 'atualizar');
$router->add('/^carrinho\/remover$/', 'Carrinho', 'remover');
$router->add('/^carrinho\/limpar$/', 'Carrinho', 'limpar');
$router->add('/^carrinho\/dados$/', 'Carrinho', 'dados');
$router->add('/^checkout$/', 'Carrinho', 'checkout');
// Dashboard (painel após login)
$router->add('/^dashboard$/', 'Dashboard', 'index');

// Dashboard > Produtos
$router->add('/^dashboard\/produtos$/', 'DashboardProducts', 'index');
$router->add('/^dashboard\/produtos\/(?P<id>\d+)\/editar$/', 'DashboardProducts', 'edit');
$router->add('/^dashboard\/produtos\/(?P<id>\d+)\/atualizar$/', 'DashboardProducts', 'update');
$router->add('/^dashboard\/produtos\/(?P<id>\d+)\/destaque$/', 'DashboardProducts', 'toggleFeatured');

// Dashboard > Pedidos
$router->add('/^dashboard\/pedidos$/', 'DashboardOrders', 'index');
$router->add('/^dashboard\/pedidos\/(?P<id>\d+)$/', 'DashboardOrders', 'show');

// Dashboard > Clientes
$router->add('/^dashboard\/clientes$/', 'DashboardCustomers', 'index');
$router->add('/^dashboard\/clientes\/(?P<id>\d+)$/', 'DashboardCustomers', 'show');

// Dashboard > Relatórios (exportações)
$router->add('/^dashboard\/relatorios\/produtos\.(?P<format>csv|xlsx|pdf|docx)$/', 'DashboardReports', 'exportProducts');
$router->add('/^dashboard\/relatorios\/pedidos\.(?P<format>csv|xlsx|pdf|docx)$/', 'DashboardReports', 'exportOrders');
// Dashboard > Relatórios (UI + outras exportações)
$router->add('/^dashboard\/relatorios$/', 'DashboardReports', 'index');
$router->add('/^dashboard\/relatorios\/clientes\.(?P<format>csv|xlsx|pdf|docx)$/', 'DashboardReports', 'exportCustomers');
$router->add('/^dashboard\/relatorios\/vendas_diario\.(?P<format>csv|xlsx|pdf|docx)$/', 'DashboardReports', 'exportDailySales');
$router->add('/^dashboard\/relatorios\/vendas\.(?P<format>csv|xlsx|pdf|docx)$/', 'DashboardReports', 'exportSales');


return $router;
// $router->get('/admin', [AdminController::class, 'dashboard']);