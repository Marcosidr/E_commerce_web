<?php
    require '../config/connection.php';
    require '../config/Users.php';

    class IndexController {
        private $users;
        private $password;

        public function __construct() {

        $connection = new Connection();
        $pdo = $connection->conect();

        $this->users = new Users($pdo);

        }

        public function verify($data){

        $email = $dados['email'] ?? NULL;
        $password = $dados['password'] ?? NULL;

        if(!filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            echo "<script>mensagem('Digite um email válido', 'error', '')<script>";
        }
        else if (empty("password"))
        {
            echo "<script>mensagem('Senha inválida','error','')</script>";
                exit;
        }
        

        $dataUsers = $this->users->getEmailUsers($email);

        print_r($dataUsers);

        if (empty($dataUsers->id))
                { echo "<script>mensagem('Usuário inválido', 'error', '')</script>";
                    exit;
                    
                }else if (!password_verify($password,$dataUsers->password))
                {
                    echo "<script>mensagem('Senha inválida, 'error', '')</script>";
                    exit;
                }else {
                    $_SESSION["users"] = array(
                        "id" => $dataUsers->id,
                        "nome" => $dataUsers->name
                    );

                    echo "<script>location.href = 'index.php'</script>";
                    exit;   
                }



    }
}