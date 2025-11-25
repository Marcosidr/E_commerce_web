<?php

namespace App\Models;

use App\Core\Model;

/**
 * Product Model - URBANSTREET
 * Gerencia produtos da loja
 */
class Product extends Model
{
    protected $table = 'produtos';
    
    /**
     * Lista produtos para administração (campos essenciais)
     */
    public function getAllForAdmin($limit = 100, $offset = 0)
    {
        $sql = "SELECT id, nome, preco, destaque, ativo, estoque FROM {$this->table} ORDER BY criado_em DESC LIMIT :limit OFFSET :offset";
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
        $sql = "UPDATE {$this->table} SET destaque = :destaque, atualizado_em = NOW() WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $val = $featured ? 1 : 0;
        $stmt->bindParam(':destaque', $val, \PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }
    
    /**
     * Atualiza campos básicos de produto (admin)
     */
    public function updateBasic(int $id, array $data): bool
    {
        $allowed = ['nome','preco','marca','estoque','ativo','categoria_id','destaque'];
        $payload = [];
        foreach ($allowed as $field) {
            if (array_key_exists($field, $data)) {
                $payload[$field] = $data[$field];
            }
        }
        if (empty($payload)) return false;
        // garante updated_at
        $payload['atualizado_em'] = date('Y-m-d H:i:s');
        return (bool) $this->update($id, $payload);
    }
    
    /**
     * Busca produtos em destaque
     */
    public function getFeatured($limit = 4)
    {
        $sql = "SELECT p.*, c.nome as category_name, c.slug as category_slug 
            FROM {$this->table} p 
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
     * Busca produtos ativos com paginação
     */
    public function getActive($limit = 12, $offset = 0)
    {
        $sql = "SELECT p.*, c.nome as category_name, c.slug as category_slug 
            FROM {$this->table} p 
            LEFT JOIN categorias c ON p.categoria_id = c.id 
            WHERE p.ativo = 1 
            ORDER BY p.criado_em DESC 
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
        return $this->count('ativo = 1');
    }
    
    /**
     * Busca produtos por categoria
     */
    public function getByCategory($categoryId, $limit = 12, $offset = 0)
    {
        $sql = "SELECT p.*, c.nome as category_name, c.slug as category_slug 
            FROM {$this->table} p 
            LEFT JOIN categorias c ON p.categoria_id = c.id 
            WHERE p.categoria_id = :category_id AND p.ativo = 1 
            ORDER BY p.criado_em DESC 
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
        return $this->count("categoria_id = {$categoryId} AND ativo = 1");
    }
    
    /**
     * Busca produtos relacionados
     */
    public function getRelated($categoryId, $excludeId, $limit = 4)
    {
        $sql = "SELECT p.*, c.nome as category_name 
            FROM {$this->table} p 
            LEFT JOIN categorias c ON p.categoria_id = c.id 
            WHERE p.categoria_id = :category_id 
                AND p.id != :exclude_id 
            AND p.ativo = 1 
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
        $sql = "SELECT p.*, c.nome as category_name, c.slug as category_slug 
            FROM {$this->table} p 
            LEFT JOIN categorias c ON p.categoria_id = c.id 
            WHERE (p.nome LIKE :term OR p.descricao LIKE :term) 
            AND p.ativo = 1 
            ORDER BY p.nome ASC";
        
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
        
        $sql = "SELECT p.*, c.nome as category_name, c.slug as category_slug 
            FROM {$this->table} p 
            LEFT JOIN categorias c ON p.categoria_id = c.id 
            WHERE p.ativo = 1";
        $params = [];

        if (!empty($filters['category'])) {
            $sql .= " AND p.categoria_id = :category";
            $params['category'] = $filters['category'];
        }
        
        if (!empty($filters['price_min'])) {
            $sql .= " AND p.preco >= :price_min";
            $params['price_min'] = $filters['price_min'];
        }
        
        if (!empty($filters['price_max'])) {
            $sql .= " AND p.preco <= :price_max";
            $params['price_max'] = $filters['price_max'];
        }
        
        if (!empty($filters['brand'])) {
            $sql .= " AND p.marca = :brand";
            $params['brand'] = $filters['brand'];
        }

        // Ordenação
        switch ($filters['sort'] ?? 'recent') {
            case 'price_asc':
                $sql .= " ORDER BY p.preco ASC"; break;
            case 'price_desc':
                $sql .= " ORDER BY p.preco DESC"; break;
            default:
                $sql .= " ORDER BY p.criado_em DESC"; break;
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
        
        $sql = "SELECT p.*, c.nome as category_name, c.slug as category_slug 
            FROM {$this->table} p 
            LEFT JOIN categorias c ON p.categoria_id = c.id 
            WHERE p.ativo = 1 
            AND (p.nome LIKE ? OR p.descricao LIKE ?)";
        
        $params = [$searchTerm, $searchTerm];
        
        // Adicionar outros filtros se necessário
        if (!empty($filters['category'])) {
            $sql .= " AND p.categoria_id = ?";
            $params[] = $filters['category'];
        }
        
        if (!empty($filters['brand'])) {
            $sql .= " AND p.marca = ?";
            $params[] = $filters['brand'];
        }
        
        if (!empty($filters['price_min'])) {
            $sql .= " AND p.preco >= ?";
            $params[] = $filters['price_min'];
        }
        
        if (!empty($filters['price_max'])) {
            $sql .= " AND p.preco <= ?";
            $params[] = $filters['price_max'];
        }
        
        // Ordenação
        switch ($filters['sort'] ?? 'recent') {
            case 'price_asc':
                $sql .= " ORDER BY p.preco ASC"; break;
            case 'price_desc':
                $sql .= " ORDER BY p.preco DESC"; break;
            default:
                $sql .= " ORDER BY p.criado_em DESC"; break;
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
        $sql = "SELECT DISTINCT marca FROM {$this->table} 
            WHERE marca IS NOT NULL AND marca <> '' 
            ORDER BY marca ASC";
        $stmt = $this->db->query($sql);
        return array_map(function($row){ return $row['marca']; }, $stmt->fetchAll(\PDO::FETCH_ASSOC));
    }
    
    /**
     * Busca produto por ID
     */
    public function findById($id)
    {
        $sql = "SELECT p.*, c.nome as category_name, c.slug as category_slug 
            FROM {$this->table} p 
            LEFT JOIN categorias c ON p.categoria_id = c.id 
            WHERE p.id = :id AND p.ativo = 1";
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