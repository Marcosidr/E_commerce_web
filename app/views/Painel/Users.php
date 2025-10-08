<?php
require_once 'vendor/autoload.php';
use \PDO;

class Users {
    private $pdo;

    public function __construct($pdo)
    {
        $this->pdo = $pdo;
    }

    public function getEmailUsers($email){
        $sql = 'select id, name, email, email_verified_at, password, phone, birth_date, gender, created_at, updated_at from users
                where active = 1 and email = :email limit 1';

            $consult = $this->pdo->prepare($sql);
            $consult->bindParam(':email', $email);
            $consult->execute();

            return $consult->fetch(PDO::FETCH_OBJ);
    }
}