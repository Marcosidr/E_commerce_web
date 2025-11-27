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
     * Buscar usuário por email (LOGIN)
     */
    public function getByEmail(string $email): ?object
    {
        $sql = "SELECT 
                    id, nome, email, senha, role, ativo, telefone,
                    data_nascimento, genero, newsletter, sms_marketing,
                    criado_em, atualizado_em
                FROM usuarios
                WHERE email = :email 
                LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();

        $row = $stmt->fetch(PDO::FETCH_OBJ);

        return $row ?: null;
    }

    /**
     * Verifica se email existe
     */
    public function emailExists(string $email): bool
    {
        $sql = "SELECT COUNT(*) FROM usuarios WHERE email = :email AND ativo = 1";
        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':email', $email);
        $stmt->execute();
        return (int)$stmt->fetchColumn() > 0;
    }

    /**
     * Criar usuário
     */
    public function create(array $data): ?object
    {
        try {
            $sql = "INSERT INTO usuarios 
                (nome, email, senha, telefone, data_nascimento, genero, newsletter, sms_marketing, role, criado_em, atualizado_em, ativo) 
                VALUES 
                (:nome, :email, :senha, :telefone, :data_nascimento, :genero, :newsletter, :sms_marketing, :role, NOW(), NOW(), 1)";

            $stmt = $this->pdo->prepare($sql);

            $stmt->execute([
                ':nome'            => $data['nome'],
                ':email'           => $data['email'],
                ':senha'           => $data['senha'],
                ':telefone'        => $data['telefone'],
                ':data_nascimento' => $data['data_nascimento'],
                ':genero'          => $data['genero'],
                ':newsletter'      => $data['newsletter'],
                ':sms_marketing'   => $data['sms_marketing'],
                ':role'            => $data['role']
            ]);

            return $this->getById((int)$this->pdo->lastInsertId());

        } catch (\Throwable $e) {
            error_log("Erro ao criar usuário: " . $e->getMessage());
            return null;
        }
    }

    /**
     * Buscar usuário por ID
     */
    public function getById(int $id): ?object
    {
        $sql = "SELECT * FROM usuarios WHERE id = :id AND ativo = 1 LIMIT 1";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_OBJ) ?: null;
    }

    /**
     * Atualizar usuário
     */
    public function update(int $id, array $data): bool
    {
        try {
            $sql = "UPDATE usuarios SET 
                nome = :nome,
                email = :email,
                telefone = :telefone,
                data_nascimento = :data_nascimento,
                genero = :genero,
                newsletter = :newsletter,
                sms_marketing = :sms_marketing,
                role = :role,
                atualizado_em = NOW()
                WHERE id = :id";

            $stmt = $this->pdo->prepare($sql);

            return $stmt->execute([
                ':id'              => $id,
                ':nome'            => $data['nome'],
                ':email'           => $data['email'],
                ':telefone'        => $data['telefone'],
                ':data_nascimento' => $data['data_nascimento'],
                ':genero'          => $data['genero'],
                ':newsletter'      => $data['newsletter'],
                ':sms_marketing'   => $data['sms_marketing'],
                ':role'            => $data['role']
            ]);

        } catch (\Throwable $e) {
            error_log("Erro ao atualizar usuário: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Desativar usuário
     */
    public function deactivate(int $id): bool
    {
        $sql = "UPDATE usuarios SET ativo = 0, atualizado_em = NOW() WHERE id = :id";

        $stmt = $this->pdo->prepare($sql);
        $stmt->bindValue(':id', $id);

        return $stmt->execute();
    }
}
