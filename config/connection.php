<?php
    Class Connection {
        
        private static $host = 'localhost';

        private static $dbname = 'urbanstreet_db';

        private static $user = 'root';

        private static $pass = '';

        public static function conect() {
            try {
                $pdo = new PDO(
                    "mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=utf8mb4",
                    self::$user,
                    self::$pass,
                    [PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8mb4"]
                );
                $pdo->exec("SET CHARACTER SET utf8mb4");
                return $pdo;
        } 

            catch(PDOException $e) {
                die ("<p> Erro ao conectar no banco: {$e->getMessage()}</p>");
            }



        }
    }