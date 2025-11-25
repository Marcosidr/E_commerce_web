<?php

namespace App\Models;

use App\Core\Model;

/**
 * Category Model - URBANSTREET
 * Gerencia categorias de produtos
 */
class Category extends Model
{
    protected $table = 'categorias';
    
    /**
     * Busca categorias principais (ativas)
     */
    public function getMainCategories()
    {
        $sql = "SELECT * FROM {$this->table} 
            WHERE ativo = 1 
            ORDER BY ordem ASC, nome ASC";
        return $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Busca todas as categorias ativas
     */
    public function getAllActive()
    {
        $sql = "SELECT * FROM {$this->table} 
            WHERE ativo = 1 
            ORDER BY nome ASC";
        return $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Busca categoria por slug
     */
    public function findBySlug($slug)
    {
        $sql = "SELECT * FROM {$this->table} 
            WHERE slug = :slug AND ativo = 1";
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
        $sql = "SELECT COUNT(*) FROM produtos 
            WHERE categoria_id = :category_id AND ativo = 1";
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
        $sql = "SELECT p.*, c.nome as category_name, c.slug as category_slug 
            FROM produtos p 
            LEFT JOIN categorias c ON p.categoria_id = c.id 
            WHERE p.destaque = 1 AND p.ativo = 1 
            ORDER BY p.criado_em DESC 
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
            WHERE id = :id AND ativo = 1";
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
        $sql = "SELECT p.*, c.nome as category_name, c.slug as category_slug 
            FROM produtos p 
            LEFT JOIN categorias c ON p.categoria_id = c.id 
            WHERE p.categoria_id = :category_id AND p.ativo = 1 
            ORDER BY p.criado_em DESC";
                
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
            LEFT JOIN produtos p ON c.id = p.categoria_id AND p.ativo = 1 
            WHERE c.ativo = 1 
            GROUP BY c.id 
            ORDER BY c.ordem ASC, c.nome ASC";
        return $this->db->query($sql)->fetchAll(\PDO::FETCH_ASSOC);
    }
}