<?php

namespace App\Core;

/**
 * Database - URBANSTREET
 * Gerencia conexão com banco de dados usando Singleton
 */
class Database
{
    private static $instance = null;
    private $connection;
    
    private function __construct()
    {
        $config = require_once CONFIG_PATH . '/database.php';
        
        try {
            $dsn = "mysql:host={$config['host']};dbname={$config['database']};charset={$config['charset']}";
            $this->connection = new \PDO(
                $dsn,
                $config['username'],
                $config['password'],
                $config['options']
            );
        } catch (\PDOException $e) {
            error_log('Database connection failed: ' . $e->getMessage());
            throw new \Exception('Erro na conexão com o banco de dados');
        }
    }
    
    /**
     * Retorna instância única da classe
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance->connection;
    }
    
    /**
     * Previne clonagem
     */
    private function __clone() {}
    
    /**
     * Previne unserialize
     */
    public function __wakeup() {}
}