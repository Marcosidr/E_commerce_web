<?php
namespace App\Controllers;

use App\Core\Controller;
/**
 * DashboardController - Painel do Usuário
 * Controlador para o painel do usuário autenticado
 */
class dashboardController extends Controller 
 {
    //exiber a view do dashboard 
    public function index()
    {
        $this->loadView('dashboard/index', [
            'title' => 'Dashboard - URBANSTREET',
            'metaDescription' => 'Painel do usuário autenticado na URBANSTREET.',
            'pageClass' => 'dashboard-page'
        ]);
    }
 }