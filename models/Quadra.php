<?php
// models/Quadra.php
class Quadra {
  private $db;

  public function __construct() {
      $this->db = Conexao::getInstance();
  }

  public function registerQuadra($ownerId, $nome, $esporte, $quadrac, $rentalType, $price) {
      $sql = "INSERT INTO quadra (proprietario_id, nome, esporte, coberta, tipo_aluguel, valor, imagem_quadra)
              VALUES (:ownerId, :nome, :esporte, :coberta, :rentalType, :price, 'default_image.jpg')";

      $stmt = $this->db->prepare($sql);
      $stmt->bindParam(':ownerId', $ownerId, PDO::PARAM_INT);
      $stmt->bindParam(':nome', $nome, PDO::PARAM_STR);
      $stmt->bindParam(':esporte', $esporte, PDO::PARAM_STR);
      $stmt->bindParam(':coberta', $quadrac, PDO::PARAM_BOOL);
      $stmt->bindParam(':rentalType', $rentalType, PDO::PARAM_STR);
      $stmt->bindParam(':price', $price, PDO::PARAM_STR);

      $stmt->execute();
  }
}

?>