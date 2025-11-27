<?php

namespace App\Models;

use App\Core\Model;

class Product extends Model
{
    protected $table = 'produtos';

    /**
     * Lista produtos para administração
     */
    public function getAllForAdmin($limit = 100, $offset = 0)
    {
        $sql = "SELECT id, nome, preco, destaque, ativo, estoque
                FROM {$this->table}
                ORDER BY criado_em DESC
                LIMIT :limit OFFSET :offset";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->bindParam(':offset', $offset, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    /**
     * Define/Remove destaque do produto
     */
    public function setFeatured(int $id, bool $featured): bool
    {
        $sql = "UPDATE {$this->table}
                SET destaque = :destaque,
                    atualizado_em = NOW()
                WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        $value = $featured ? 1 : 0;

        $stmt->bindParam(':destaque', $value, \PDO::PARAM_INT);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);

        return $stmt->execute();
    }


    /**
     * Produtos em destaque (Home)
     */
    public function getFeatured($limit = 4)
    {
        $sql = "SELECT p.*, c.nome AS category_name, c.slug AS category_slug
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
     * Atualização básica para o painel admin
     */
    public function updateBasic(int $id, array $data): bool
    {
        $allowed = [
            'nome', 'descricao', 'preco', 'marca',
            'estoque', 'ativo', 'categoria_id', 'destaque'
        ];

        $payload = [];

        foreach ($allowed as $field) {
            if (isset($data[$field])) {
                $payload[$field] = $data[$field];
            }
        }

        if (empty($payload)) return false;

        $payload['atualizado_em'] = date('Y-m-d H:i:s');

        return $this->update($id, $payload);
    }


    /**
     * Buscar produto por ID
     */
    public function findById($id)
    {
        $sql = "SELECT p.*, c.nome AS category_name, c.slug AS category_slug
                FROM {$this->table} p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                WHERE p.id = :id
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }


    public function getById($id)
    {
        return $this->findById($id);
    }


    /**
     * Cria produto no banco
     */
    public function create($data)
{
    $fields = [];
    $placeholders = [];
    $params = [];

    foreach ($data as $k => $v) {
        $fields[] = $k;
        $placeholders[] = ':' . $k;
        $params[':' . $k] = $v;
    }

    $sql = "INSERT INTO {$this->table} (" . implode(',', $fields) . ")
            VALUES (" . implode(',', $placeholders) . ")";

    $stmt = $this->db->prepare($sql);
    $ok = $stmt->execute($params);

    return $ok ? (int)$this->db->lastInsertId() : false;
}


    /**
     * Retorna um registro da galeria por ID
     */
    public function getImageById(int $imageId)
    {
        $sql = "SELECT id, image_path 
                FROM product_images 
                WHERE id = :id 
                LIMIT 1";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':id' => $imageId]);

        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }


    /**
     * Produtos relacionados
     */
    public function getRelated($categoryId, $excludeId, $limit = 4)
    {
        $sql = "SELECT p.*, c.nome AS category_name
                FROM {$this->table} p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                WHERE p.categoria_id = :cat
                AND p.id != :exclude
                AND p.ativo = 1
                ORDER BY RAND()
                LIMIT :limit";

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':cat', $categoryId, \PDO::PARAM_INT);
        $stmt->bindParam(':exclude', $excludeId, \PDO::PARAM_INT);
        $stmt->bindParam(':limit', $limit, \PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    /**
     * Filtros do catálogo
     */
    public function getByFilters($filters)
    {
        if (!empty($filters['search'])) {
            return $this->search($filters['search']);
        }

        $sql = "SELECT p.*, c.nome AS category_name, c.slug AS category_slug
                FROM {$this->table} p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                WHERE p.ativo = 1";

        $params = [];

        if (!empty($filters['category'])) {
            $sql .= " AND p.categoria_id = :category";
            $params['category'] = $filters['category'];
        }

        if (!empty($filters['price_min'])) {
            $sql .= " AND p.preco >= :min";
            $params['min'] = $filters['price_min'];
        }

        if (!empty($filters['price_max'])) {
            $sql .= " AND p.preco <= :max";
            $params['max'] = $filters['price_max'];
        }

        if (!empty($filters['brand'])) {
            $sql .= " AND p.marca = :brand";
            $params['brand'] = $filters['brand'];
        }

        switch ($filters['sort'] ?? 'recent') {
            case 'price_asc':
                $sql .= " ORDER BY p.preco ASC";
                break;
            case 'price_desc':
                $sql .= " ORDER BY p.preco DESC";
                break;
            default:
                $sql .= " ORDER BY p.criado_em DESC";
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    /**
     * Pesquisa por termo
     */
    public function search($term)
    {
        $sql = "SELECT p.*, c.nome AS category_name, c.slug AS category_slug
                FROM {$this->table} p
                LEFT JOIN categorias c ON p.categoria_id = c.id
                WHERE (p.nome LIKE :term OR p.descricao LIKE :term)
                AND p.ativo = 1";

        $stmt = $this->db->prepare($sql);
        $search = "%$term%";
        $stmt->bindParam(':term', $search);
        $stmt->execute();

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }


    /**
     * Lista de marcas
     */
    public function getDistinctBrands()
    {
        $sql = "SELECT DISTINCT marca
                FROM {$this->table}
                WHERE marca IS NOT NULL AND marca <> ''";

        return $this->db->query($sql)->fetchAll(\PDO::FETCH_COLUMN);
    }


    /**
     * -------- GALERIA DE IMAGENS --------
     */

    public function addImage(int $productId, string $path): bool
    {
        $sql = "INSERT INTO product_images (product_id, image_path)
                VALUES (:pid, :path)";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([
            ':pid'  => $productId,
            ':path' => $path
        ]);
    }

    public function getImages(int $productId): array
    {
        $sql = "SELECT id, image_path
                FROM product_images
                WHERE product_id = :pid
                ORDER BY id DESC";

        $stmt = $this->db->prepare($sql);
        $stmt->execute([':pid' => $productId]);

        return $stmt->fetchAll(\PDO::FETCH_ASSOC);
    }

    public function deleteImage(int $imageId): bool
    {
        $sql = "DELETE FROM product_images WHERE id = :id";

        $stmt = $this->db->prepare($sql);

        return $stmt->execute([':id' => $imageId]);
    }


    /**
     * Formatar preço
     */
    public static function formatPrice($price)
    {
        return 'R$ ' . number_format($price, 2, ',', '.');
    }
}
