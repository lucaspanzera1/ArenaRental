<?php

include '../models/Client.php';

class HomeController
{
    private $clientModel;

    public function __construct()
    {
        $this->clientModel = new Client();
    }

    public function index()
    {
        session_start();

        if (isset($_SESSION['id'])) {
            $userId = $_SESSION['id'];
            $user = $this->clientModel->getClientById($userId);

            if ($user) {
                // Passa os dados do usuário para a view
                require '../views/home/index.php';
            } else {
                // Redireciona se não encontrar o usuário
                header("Location: ../views/login.php");
                exit();
            }
        } else {
            // Redireciona para a página de login se não estiver logado
            header("Location: ../views/login.php");
            exit();
        }
    }
}

?>

