<?php
namespace App\Models;

use PDO;

class Users
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    /**
     * Busca usuário por email
     */
    public function getByEmail(string $email): ?object
    {
        $sql = 'SELECT 
                    id, nome, email, email_verificado_em, senha, telefone, 
                    data_nascimento, genero, role, newsletter, sms_marketing,
                    criado_em, atualizado_em
                FROM usuarios
                WHERE ativo = 1 AND email = :email 
                LIMIT 1';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_OBJ);
        return $row ?: null;
    }

    /**
     * Verifica se um email já existe na base de dados
     */
    public function emailExists(string $email): bool
    {
        $sql = 'SELECT COUNT(*) FROM usuarios WHERE email = :email';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    /**
     * Cria um novo usuário
     */
    public function create(array $data): ?object
    {
        try {
            $sql = 'INSERT INTO usuarios (
                        nome, email, senha, telefone, data_nascimento, genero,
                        newsletter, sms_marketing, role, criado_em, atualizado_em, ativo
                    ) VALUES (
                        :nome, :email, :senha, :telefone, :data_nascimento, :genero,
                        :newsletter, :sms_marketing, :role, NOW(), NOW(), 1
                    )';

            $stmt = $this->pdo->prepare($sql);

            $stmt->bindValue(':nome', $data['name']);
            $stmt->bindValue(':email', $data['email']);
            $stmt->bindValue(':senha', $data['password']);
            $stmt->bindValue(':telefone', $data['telefone'] ?? null);
            $stmt->bindValue(':data_nascimento', $data['data_nascimento'] ?? null);
            $stmt->bindValue(':genero', $data['sexo'] ?? null);
            $stmt->bindValue(':newsletter', $data['newsletter'] ?? 0, PDO::PARAM_INT);
            $stmt->bindValue(':sms_marketing', $data['sms_marketing'] ?? 0, PDO::PARAM_INT);
            $stmt->bindValue(':role', $data['role'] ?? 'cliente');

            if ($stmt->execute()) {
                $userId = $this->pdo->lastInsertId();
                return $this->getById($userId);
            }

            return null;

        } catch (\PDOException $e) {
            error_log("Erro ao criar usuário: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Busca usuário por ID
     */
    public function getById(int $id): ?object
    {
        $sql = 'SELECT 
                    id, nome, email, email_verificado_em, senha, telefone,
                    data_nascimento, genero, role, newsletter, sms_marketing,
                    criado_em, atualizado_em
                FROM usuarios
                WHERE ativo = 1 AND id = :id 
                LIMIT 1';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_OBJ);
        return $row ?: null;
    }

    /**
     * Atualiza os dados do usuário
     */
    public function update(int $id, array $data): bool
    {
        try {
            $fields = [];
            $params = [':id' => $id];

            foreach ($data as $key => $value) {
                if (in_array($key, ['nome', 'email', 'telefone', 'data_nascimento', 'genero', 'newsletter', 'sms_marketing', 'role'])) {
                    $fields[] = "$key = :$key";
                    $params[":$key"] = $value;
                }
            }

            if (empty($fields)) {
                return false;
            }

            $fields[] = 'atualizado_em = NOW()';

            $sql = 'UPDATE usuarios SET ' . implode(', ', $fields) . ' WHERE id = :id';

            $stmt = $this->pdo->prepare($sql);
            return $stmt->execute($params);

        } catch (\PDOException $e) {
            error_log("Erro ao atualizar usuário: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Desativa um usuário (soft delete)
     */
    public function deactivate(int $id): bool
    {
        $sql = 'UPDATE usuarios 
                SET ativo = 0, atualizado_em = NOW() 
                WHERE id = :id';

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }
}
