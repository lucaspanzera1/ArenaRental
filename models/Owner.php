<?php
require_once 'Client.php';
require_once 'Conexao.php';

class Owner extends Client {
    private $id; // Declare a propriedade id
    private $nomeEspaco;
    private $localizacao;
    private $cep;
    private $descricao;
    private $recursos;

    public function __construct($id, $name, $email, $type, $registrationDate, $nomeEspaco, $localizacao, $cep, $descricao, $recursos) {
        parent::__construct($id, $name, $email, $type, $registrationDate);
        $this->id = $id; // Atribua o valor do id
        $this->nomeEspaco = $nomeEspaco;
        $this->localizacao = $localizacao;
        $this->cep = $cep;
        $this->descricao = $descricao;
        $this->recursos = $recursos;
    }
    // Getters para acessar os atributos
    public function getNomeEspaco() {
        return $this->nomeEspaco;
    }

    public function getLocalizacao() {
        return $this->localizacao;
    }

    public function getCep() {
        return $this->cep;
    }

    public function getDescricao() {
        return $this->descricao;
    }

    public function getRecursos() { // Novo getter para 'recursos'
        return $this->recursos;
    }
    public static function getOwnerById($ownerId) {
        $pdo = Conexao::getInstance();
    
        // Consulta que faz JOIN entre cliente e proprietario
        $stmt = $pdo->prepare("
            SELECT p.id, c.nome, c.email, c.data_registro, 
                   p.nome_espaco, p.localizacao, p.cep, p.descricao, p.recursos
            FROM proprietario p
            JOIN cliente c ON p.id = c.id
            WHERE p.id = :id
        ");
        $stmt->bindParam(':id', $ownerId, PDO::PARAM_INT);
        $stmt->execute();
    
        $ownerData = $stmt->fetch(PDO::FETCH_ASSOC);
    
        if ($ownerData) {
            return new self(
                $ownerData['id'], // Certifique-se de que o 'id' agora será retornado corretamente
                $ownerData['nome'],
                $ownerData['email'],
                'Dono',
                $ownerData['data_registro'],
                $ownerData['nome_espaco'],
                $ownerData['localizacao'],
                $ownerData['cep'],
                $ownerData['descricao'],
                $ownerData['recursos']
            );
        }
        return null;
    }
    public function registerQuadra($ownerId, $nomeQuadra, $esporte, $coberta, $tipoAluguel, $valor) {
        $pdo = Conexao::getInstance();
    
        $stmt = $pdo->prepare("
            INSERT INTO quadra (proprietario_id, nome, esporte, coberta, tipo_aluguel, valor, imagem_quadra)
            VALUES (:proprietario_id, :nome, :esporte, :coberta, :tipo_aluguel, :valor, 'default.jpg')
        ");
    
        $stmt->bindParam(':proprietario_id', $ownerId, PDO::PARAM_INT);
        $stmt->bindParam(':nome', $nomeQuadra, PDO::PARAM_STR);
        $stmt->bindParam(':esporte', $esporte, PDO::PARAM_STR);
        $stmt->bindParam(':coberta', $coberta, PDO::PARAM_BOOL);
        $stmt->bindParam(':tipo_aluguel', $tipoAluguel, PDO::PARAM_STR);
        $stmt->bindParam(':valor', $valor, PDO::PARAM_STR);
    
        if ($stmt->execute()) {
            echo "Quadra cadastrada com sucesso!";
        } else {
            echo "Erro ao cadastrar quadra.";
            // Para depuração: exibir erros SQL
            print_r($stmt->errorInfo());
        }
    }
    public function getQuadras() {
        $pdo = Conexao::getInstance();
        $stmt = $pdo->prepare("
            SELECT id, nome, esporte, coberta, tipo_aluguel, valor, imagem_quadra
            FROM quadra
            WHERE proprietario_id = :proprietario_id
        ");
        
        $id = $this->getId(); // Armazena o ID em uma variável
        $stmt->bindParam(':proprietario_id', $id, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    public function uploadFotoPerfilOwner($origem = null)
{
    // Configurações de upload
    $_UP['pasta'] = '../upload/quadra_img/';
    $_UP['tamanho'] = 1024 * 1024 * 100; // 100MB
    $_UP['extensoes'] = array('png', 'jpg', 'jpeg', 'gif');

    // Verifica se houve algum erro no upload
    if ($_FILES['arquivo']['error'] != 0) {
        die("Não foi possível fazer o upload, erro: " . $_FILES['arquivo']['error']);
    }

    // Verifica o tamanho do arquivo
    if ($_UP['tamanho'] < $_FILES['arquivo']['size']) {
        $this->exibirAlerta("Arquivo muito grande.", $origem);
        return;
    }

    // Verifica a extensão do arquivo
    $extensao = strtolower(pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION));
    if (!in_array($extensao, $_UP['extensoes'])) {
        $this->exibirAlerta("Extensão não permitida.", $origem);
        return;
    }

    // Define o nome do arquivo
    $nome_final = uniqid() . '.' . $extensao;

    // Tenta mover o arquivo para a pasta de upload
    if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $nome_final)) {
        $pdo = Conexao::getInstance();
        
        // Atualiza a coluna imagem_quadra na tabela proprietario
        $stmt = $pdo->prepare("UPDATE quadra SET imagem_quadra = :imagem_quadra WHERE id = :id_user");
        $imagem_quadra = $_UP['pasta'] . $nome_final;
        $stmt->bindParam(':imagem_quadra', $imagem_quadra);
        $stmt->bindParam(':id_user', $this->id, PDO::PARAM_INT);
        $stmt->execute();
    
        // Não coloque o exit aqui, permitindo o redirecionamento após o upload
    } else {
        $this->exibirAlerta("Não foi possível atualizar a imagem de perfil.", $origem);
    }
}

}
