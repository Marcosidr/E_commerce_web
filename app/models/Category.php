<?php

namespace App\Models;

use App\Core\Model;

/**
 * Category Model - URBANSTREET
 * Gerencia categorias de produtos
 */
class Category extends Model
{
    protected $table = 'categories';
    
    /**
     * Busca categorias principais (ativas)
     */
    public function getMainCategories()
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE active = 1 
                ORDER BY sort_order ASC, name ASC";
        return $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Busca todas as categorias ativas
     */
    public function getAllActive()
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE active = 1 
                ORDER BY name ASC";
        return $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Busca categoria por slug
     */
    public function findBySlug($slug)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE slug = :slug AND active = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':slug', $slug);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Conta produtos por categoria
     */
    public function getProductCount($categoryId)
    {
        $sql = "SELECT COUNT(*) FROM products 
                WHERE category_id = :category_id AND active = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':category_id', $categoryId, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchColumn();
    }

    /**
     * Busca produtos em destaque
     */
    public function getFeatured($limit = 4)
    {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM products p 
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
     * Busca categoria por ID
     */
    public function findById($id)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE id = :id AND active = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    /**
     * Busca produtos de uma categoria
     */
    public function getProducts($categoryId, $limit = null, $offset = 0)
    {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM products p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.category_id = :category_id AND p.active = 1 
                ORDER BY p.created_at DESC";
                
        if ($limit) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':category_id', $categoryId, \PDO::PARAM_INT);
        
        if ($limit) {
            $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
            $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        }
        
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Busca categorias com contagem de produtos
     */
    public function getCategoriesWithCount()
    {
        $sql = "SELECT c.*, COUNT(p.id) as product_count 
                FROM {$this->table} c 
                LEFT JOIN products p ON c.id = p.category_id AND p.active = 1 
                WHERE c.active = 1 
                GROUP BY c.id 
                ORDER BY c.sort_order ASC, c.name ASC";
        return $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }
}