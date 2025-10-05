<?php

namespace App\Core;

/**
 * Router - Sistema de Roteamento URBANSTREET
 * Gerencia todas as rotas da aplicação
 */
class Router
{
    private $routes = [];
    private $params = [];
    
    /**
     * Adiciona uma rota
     */
    public function add($route, $controller, $action = 'index')
    {
        $this->routes[$route] = [
            'controller' => $controller,
            'action' => $action
        ];
    }
    
    /**
     * Processa a URL e executa o controller/action apropriado
     */
    public function dispatch($url)
    {
        $url = $this->removeQueryStringVariables($url);
        
        if ($this->match($url)) {
            $controller = $this->params['controller'];
            $controller = $this->convertToStudlyCaps($controller);
            $controller = "App\\Controllers\\{$controller}Controller";
            
            if (class_exists($controller)) {
                $controllerObject = new $controller();
                
                $action = $this->params['action'];
                $action = $this->convertToCamelCase($action);
                
                if (method_exists($controllerObject, $action)) {
                    if (isset($this->params['id'])) {
                        $controllerObject->$action($this->params['id']);
                    } else {
                        $controllerObject->$action();
                    }
                } else {
                    $this->show404();
                }
            } else {
                $this->show404();
            }
        } else {
            $this->show404();
        }
    }
    
    /**
     * Verifica se a URL corresponde a uma rota
     */
    protected function match($url)
    {
        foreach ($this->routes as $route => $params) {
            if (preg_match($route, $url, $matches)) {
                foreach ($matches as $key => $match) {
                    if (is_string($key)) {
                        $params[$key] = $match;
                    }
                }
                $this->params = $params;
                return true;
            }
        }
        return false;
    }
    
    /**
     * Remove query string da URL
     */
    protected function removeQueryStringVariables($url)
    {
        if ($url != '') {
            $parts = explode('&', $url, 2);
            if (strpos($parts[0], '=') === false) {
                $url = $parts[0];
            } else {
                $url = '';
            }
        }
        return $url;
    }
    
    /**
     * Converte para StudlyCaps
     */
    protected function convertToStudlyCaps($string)
    {
        return str_replace(' ', '', ucwords(str_replace('-', ' ', $string)));
    }
    
    /**
     * Converte para camelCase
     */
    protected function convertToCamelCase($string)
    {
        return lcfirst($this->convertToStudlyCaps($string));
    }
    
    /**
     * Exibe página 404
     */
    protected function show404()
    {
        http_response_code(404);
        require_once APP_PATH . '/views/errors/404.php';
    }
}