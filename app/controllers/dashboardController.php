<?php
namespace App\Controllers;

use App\Core\Controller;

class DashboardController extends Controller
{
    public function index()
    {
        $this->loadPartial('Painel/dashboard', [
            'title' => 'Dashboard - URBANSTREET',
            'metaDescription' => 'Painel do usuário autenticado na URBANSTREET.',
            'pageClass' => 'dashboard-page'
        ]);
    }
}