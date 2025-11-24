<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Middleware\AuthMiddleware;

class DashboardController extends Controller
{
    public function __construct()
    {
        AuthMiddleware::requireAdmin(); // só admin acessa o dashboard
    }

    public function index()
    {
        $this->loadPartial('Painel/dashboard', [
            'title' => 'Dashboard - URBANSTREET',
            'metaDescription' => 'Painel do usuário autenticado na URBANSTREET.',
            'pageClass' => 'dashboard-page'
        ]);
    }
}