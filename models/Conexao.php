<?php

class Conexao
{
    private static $instance;

    private function __construct()
    {
        // Construtor privado para impedir a instância direta
    }

    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            try {
                self::$instance = new PDO(
                    "mysql:host=localhost;dbname=arenarental",
                    "root",
                    "",
                    array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8')
                );
                self::$instance->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            } catch (PDOException $e) {
                die("Connection failed: " . $e->getMessage());
            }
        }

        return self::$instance;
    }
}

?>

