<?php
    Class Connection {
        
        private static $host = 'localhost';

        private static $dbname = 'urbanstreet_db';

        private static $user = 'root';

        private static $pass = '';

        public static function conect() {
            try {
                return new PDO(
                    "mysql:host=" . self::$host . ";dbname=" . self::$dbname . ";charset=utf8",
                    self::$user,
                    self::$pass
);
        } 

            catch(PDOException $e) {
                die ("<p> Erro ao conectar no banco: {$e->getMessage()}</p>");
            }



        }
    }