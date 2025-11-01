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

    public function getByEmail(string $email): ?object
    {
        $sql = 'SELECT id, name, email, email_verified_at, password, phone, birth_date, gender, created_at, updated_at
                FROM users
                WHERE active = 1 AND email = :email LIMIT 1';
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
        $sql = 'SELECT COUNT(*) FROM users WHERE email = :email';
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
            $sql = 'INSERT INTO users (name, email, password, phone, birth_date, gender, newsletter, sms_marketing, created_at, updated_at, active) 
                    VALUES (:name, :email, :password, :phone, :birth_date, :gender, :newsletter, :sms_marketing, NOW(), NOW(), 1)';
            
            $stmt = $this->pdo->prepare($sql);
            $stmt->bindValue(':name', $data['name']);
            $stmt->bindValue(':email', $data['email']);
            $stmt->bindValue(':password', $data['password']);
            $stmt->bindValue(':phone', $data['telefone'] ?? null);
            $stmt->bindValue(':birth_date', $data['data_nascimento'] ?? null);
            $stmt->bindValue(':gender', $data['sexo'] ?? null);
            $stmt->bindValue(':newsletter', $data['newsletter'] ?? 0, PDO::PARAM_INT);
            $stmt->bindValue(':sms_marketing', $data['sms_marketing'] ?? 0, PDO::PARAM_INT);
            
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
        $sql = 'SELECT id, name, email, email_verified_at, password, phone, birth_date, gender, newsletter, sms_marketing, created_at, updated_at
                FROM users
                WHERE active = 1 AND id = :id LIMIT 1';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_OBJ);
        return $row ?: null;
    }

    /**
     * Autentica um usuário (login)
     */
    public function login(string $email, string $password): ?object
    {
        $user = $this->getByEmail($email);
        
        if ($user && password_verify($password, $user->password)) {
            return $user;
        }
        
        return null;
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
                if (in_array($key, ['name', 'email', 'phone', 'birth_date', 'gender', 'newsletter', 'sms_marketing'])) {
                    $fields[] = "$key = :$key";
                    $params[":$key"] = $value;
                }
            }
            
            if (empty($fields)) {
                return false;
            }
            
            $fields[] = 'updated_at = NOW()';
            $sql = 'UPDATE users SET ' . implode(', ', $fields) . ' WHERE id = :id';
            
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
        $sql = 'UPDATE users SET active = 0, updated_at = NOW() WHERE id = :id';
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
