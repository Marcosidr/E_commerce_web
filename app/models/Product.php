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
     * Lista produtos para administração (campos essenciais)
     */
    public function getAllForAdmin($limit = 100, $offset = 0)
    {
        $sql = "SELECT id, name, price, featured, active, stock_quantity FROM {$this->table} ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Altera flag de destaque do produto
     */
    public function setFeatured(int $id, bool $featured): bool
    {
        $sql = "UPDATE {$this->table} SET featured = :featured, updated_at = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $val = $featured ? 1 : 0;
        $stmt->bindParam(':featured', $val, \PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    /**
     * Atualiza campos básicos de produto (admin)
     */
    public function updateBasic(int $id, array $data): bool
    {
        $allowed = ['name','price','brand','stock_quantity','active','category_id','featured'];
        $payload = [];
        foreach ($allowed as $field) {
            if (array_key_exists($field, $data)) {
                $payload[$field] = $data[$field];
            }
        }
        if (empty($payload)) return false;
        // garante updated_at
        $payload['updated_at'] = date('Y-m-d H:i:s');
        return (bool) $this->update($id, $payload);
    }
    
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
        // Se há busca, usar método de busca separado
        if (!empty($filters['search'])) {
            return $this->searchWithFilters($filters);
        }
        
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.active = 1";
        $params = [];

        if (!empty($filters['category'])) {
            $sql .= " AND p.category_id = :category";
            $params['category'] = $filters['category'];
        }
        
        if (!empty($filters['price_min'])) {
            $sql .= " AND p.price >= :price_min";
            $params['price_min'] = $filters['price_min'];
        }
        
        if (!empty($filters['price_max'])) {
            $sql .= " AND p.price <= :price_max";
            $params['price_max'] = $filters['price_max'];
        }
        
        if (!empty($filters['brand'])) {
            $sql .= " AND p.brand = :brand";
            $params['brand'] = $filters['brand'];
        }

        // Ordenação
        switch ($filters['sort'] ?? 'recent') {
            case 'price_asc':
                $sql .= " ORDER BY p.price ASC"; break;
            case 'price_desc':
                $sql .= " ORDER BY p.price DESC"; break;
            default:
                $sql .= " ORDER BY p.created_at DESC"; break;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }
    
    /**
     * Busca produtos com termo de pesquisa e filtros
     */
    private function searchWithFilters($filters)
    {
        $searchTerm = "%{$filters['search']}%";
        
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.active = 1 
                AND (p.name LIKE ? OR p.description LIKE ?)";
        
        $params = [$searchTerm, $searchTerm];
        
        // Adicionar outros filtros se necessário
        if (!empty($filters['category'])) {
            $sql .= " AND p.category_id = ?";
            $params[] = $filters['category'];
        }
        
        if (!empty($filters['brand'])) {
            $sql .= " AND p.brand = ?";
            $params[] = $filters['brand'];
        }
        
        if (!empty($filters['price_min'])) {
            $sql .= " AND p.price >= ?";
            $params[] = $filters['price_min'];
        }
        
        if (!empty($filters['price_max'])) {
            $sql .= " AND p.price <= ?";
            $params[] = $filters['price_max'];
        }
        
        // Ordenação
        switch ($filters['sort'] ?? 'recent') {
            case 'price_asc':
                $sql .= " ORDER BY p.price ASC"; break;
            case 'price_desc':
                $sql .= " ORDER BY p.price DESC"; break;
            default:
                $sql .= " ORDER BY p.created_at DESC"; break;
        }
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    /**
     * Retorna lista de marcas distintas
     */
    public function getDistinctBrands(): array
    {
        $sql = "SELECT DISTINCT brand FROM {$this->table} 
                WHERE brand IS NOT NULL AND brand <> '' 
                ORDER BY brand ASC";
        $stmt = $this->db->query($sql);
        return array_map(function($row){ return $row['brand']; }, $stmt->fetchAll(\PDO::FETCH_ASSOC));
    }
    
    /**
     * Busca produto por ID
     */
    public function findById($id)
    {
        $sql = "SELECT p.*, c.name as category_name, c.slug as category_slug 
                FROM {$this->table} p 
                LEFT JOIN categories c ON p.category_id = c.id 
                WHERE p.id = :id AND p.active = 1";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
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