<?php

namespace App\Core;

/**
 * Controller Base - URBANSTREET
 * Classe base para todos os controllers
 */
class Controller
{
    /**
     * Carrega uma view com layout
     */
    protected function loadView($view, $data = [])
    {
        $viewPath = APP_PATH . '/views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            extract($data);
            ob_start();
            require_once $viewPath;
            $content = ob_get_clean();
            
            // Carrega o layout principal
            require_once APP_PATH . '/views/layouts/main.php';
        } else {
            throw new \Exception("View não encontrada: {$view}");
        }
    }
    
    /**
     * Carrega view sem layout
     */
    protected function loadPartial($view, $data = [])
    {
        $viewPath = APP_PATH . '/views/' . $view . '.php';
        
        if (file_exists($viewPath)) {
            extract($data);
            require_once $viewPath;
        } else {
            throw new \Exception("Partial não encontrada: {$view}");
        }
    }
    
    /**
     * Redireciona para uma URL
     */
    protected function redirect($url)
    {
        if (!str_starts_with($url, 'http')) {
            $url = BASE_URL . $url;
        }
        header("Location: {$url}");
        exit();
    }
    
    /**
     * Retorna JSON response
     */
    protected function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }
    
    /**
     * Verifica se é uma requisição AJAX
     */
    protected function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}