<?php

namespace App\Core;

/**
 * Classe base Controller
 * Todas as controllers do projeto devem herdar desta classe
 */
class Controller
{
    /**
     * Carrega uma view
     *
     * @param string $view Nome da view
     * @param array $data Dados para passar para a view
     * @return void
     */
    protected function loadView($view, $data = [])
    {
        $viewPath = APP_PATH . '/views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            extract($data);
            require_once $viewPath;
        } else {
            throw new \Exception("View não encontrada: {$view}");
        }
    }
    
    /**
     * Redireciona para uma URL
     *
     * @param string $url
     * @return void
     */
    protected function redirect($url)
    {
        header("Location: {$url}");
        exit();
    }
    
    /**
     * Retorna JSON response
     *
     * @param array $data
     * @return void
     */
    protected function jsonResponse($data)
    {
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
}