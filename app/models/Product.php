<?php

namespace App\Models;

use App\Core\Model;

/**
 * Product Model - URBANSTREET
 * Gerencia produtos da loja
 */
class Product extends Model
{
    protected $table = 'products';
    
    /**
     * Busca produtos em destaque
     */
    public function getFeatured($limit = 4)
    {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.featured = 1 AND p.active = 1 
                ORDER BY p.created_at DESC 
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Busca produtos ativos com paginação
     */
    public function getActive($limit = 12, $offset = 0)
    {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.active = 1 
                ORDER BY p.created_at DESC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Conta produtos ativos
     */
    public function countActive()
    {
        return $this->count('active = 1');
    }
    
    /**
     * Busca produtos por categoria
     */
    public function getByCategory($categoryId, $limit = 12, $offset = 0)
    {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.category_id = :category_id AND p.active = 1 
                ORDER BY p.created_at DESC 
                LIMIT :limit OFFSET :offset";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':category_id', $categoryId, \PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Conta produtos por categoria
     */
    public function countByCategory($categoryId)
    {
        return $this->count("category_id = {$categoryId} AND active = 1");
    }
    
    /**
     * Busca produtos relacionados
     */
    public function getRelated($categoryId, $excludeId, $limit = 4)
    {
        $sql = "SELECT p.*, c.name as category_name 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.category_id = :category_id 
                AND p.id != :exclude_id 
                AND p.active = 1 
                ORDER BY RAND() 
                LIMIT :limit";
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':category_id', $categoryId, \PDO::PARAM_INT);
        $stmt->bindParam(':exclude_id', $excludeId, \PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Busca produtos por termo
     */
    public function search($term)
    {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE (p.name LIKE :term OR p.description LIKE :term) 
                AND p.active = 1 
                ORDER BY p.name ASC";
        
        $stmt = $this->db->prepare($sql);
        $searchTerm = "%{$term}%";
        $stmt->bindParam(':term', $searchTerm);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Busca produtos com filtros aplicados
     */
    public function getByFilters($filters)
    {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.active = 1";
        $params = [];

        if (!empty($filters['category'])) {
            $sql .= " AND p.category_id = :category";
            $params[':category'] = $filters['category'];
        }
        
        if (!empty($filters['price_min'])) {
            $sql .= " AND p.price >= :price_min";
            $params[':price_min'] = $filters['price_min'];
        }
        
        if (!empty($filters['price_max'])) {
            $sql .= " AND p.price <= :price_max";
            $params[':price_max'] = $filters['price_max'];
        }
        
        if (!empty($filters['brand'])) {
            $sql .= " AND p.brand = :brand";
            $params[':brand'] = $filters['brand'];
        }
        
        if (!empty($filters['search'])) {
            $sql .= " AND (p.name LIKE :search OR p.description LIKE :search)";
            $params[':search'] = "%{$filters['search']}%";
        }

        $sql .= " ORDER BY p.created_at DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Alias para findById (compatibilidade)
     */
    public function getById($id)
    {
        return $this->findById($id);
    }
    
    /**
     * Formata preço
     */
    public static function formatPrice($price)
    {
        return 'R$ ' . number_format($price, 2, ',', '.');
    }
}