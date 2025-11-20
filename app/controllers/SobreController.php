<?php
namespace App\Controllers;

use App\Core\Controller;

/**
 * SobreController - Página institucional (Sem dados de conteúdo no Controller)
 */
class SobreController extends Controller
{
    /**
     * Exibe a página "Sobre Nós".
     */
    public function index()
    {
        // A View não precisa de variáveis de conteúdo, pois o texto estará no HTML.
        $this->loadView('home/sobre', [
            'title' => 'Sobre Nós - URBANSTREET',
            'metaDescription' => 'Conheça a história da UrbanStreet, nossa missão e os valores que movem nossa marca.',
            'pageClass' => 'about-page', 
        ]);
    }
}