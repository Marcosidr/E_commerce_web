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
}
