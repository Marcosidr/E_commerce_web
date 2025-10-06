<?php
namespace App\Controllers;

use App\Core\Controller;

/**
 * ContatoController - página de contato estilizada
 */
class ContatoController extends Controller
{
    /**
     * Exibe formulário de contato + informações + FAQ.
     */
    public function index()
    {
        $this->loadView('home/contato', [
            'title' => 'Contato - URBANSTREET',
            'metaDescription' => 'Fale conosco para dúvidas, suporte ou trocas. Estamos prontos para ajudar!',
            'pageClass' => 'contact-page'
        ]);
    }
}
