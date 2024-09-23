<?php

require_once '../models/Owner.php';
require_once '../models/Client.php';

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verifica qual é a ação solicitada na URL
    if (isset($_GET['action']) && $_GET['action'] === 'etapa1') {
        $descricao = $_POST["descricao"];
        $id_user = $_SESSION['client']['id'];
        $nome_dono = $_SESSION['client']['nome'];

        $owner = new Owner();

        // Insere os dados da quadra no banco
        if ($owner->inserirEtapa1($descricao, $id_user, $nome_dono)) {
            echo "
                <script type=\"text/javascript\">
                    alert('Quadra registrada com sucesso!');
                    window.location.href = '../views/owner/form.quadra2.php';
                </script>
            ";
            exit();
        } else {
            echo "
                <script type=\"text/javascript\">
                    alert('Erro ao registrar a quadra.');
                    window.location.href = '../views/owner/form.quadra1.php';
                </script>
            ";
            exit();
        }
    } elseif (isset($_GET['action']) && $_GET['action'] === 'etapa2') {
        $titulo = $_POST["Titulo"];
        $esporte = $_POST["esporte"];
        $localizacao = $_POST["Localizacao"];
        $descricao = "";
        $valor = $_POST["Valor"];
        $id_user = $_SESSION['client']['id'];
        $nome_dono = $_SESSION['client']['nome'];

        $owner = new Owner();

        // Atualiza ou insere os dados adicionais da quadra
        if ($owner->atualizarQuadra($titulo, $esporte, $localizacao, $descricao, $valor, $id_user, $nome_dono)) {
            echo "
                <script type=\"text/javascript\">
                    alert('Quadra atualizada com sucesso!');
                    window.location.href = '../index.php';
                </script>
            ";
            exit();
        } else {
            echo "
                <script type=\"text/javascript\">
                    alert('Erro ao atualizar a quadra.');
                    window.location.href = '../views/owner/form.quadra2.php';
                </script>
            ";
            exit();
        }
    } if ($_GET['action'] === 'FotoQuadra') {
         $owner = new Owner();
        if (isset($_SESSION['client']['id'])) {
            $owner->uploadFotoQuadra();
        }
        $origem = isset($_POST['origem']) ? $_POST['origem'] : null;
        $owner->uploadFotoQuadra($origem);
        }
    }
?>
