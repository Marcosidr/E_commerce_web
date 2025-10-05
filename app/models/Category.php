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
}