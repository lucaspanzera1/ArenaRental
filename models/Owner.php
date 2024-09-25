<?php
require_once 'Client.php';
require_once 'Conexao.php';

class Owner extends Client {
    private $nomeEspaco;
    private $localizacao;
    private $cep;
    private $descricao;
    private $recursos; // Nova propriedade

    public function __construct($id, $name, $email, $type, $registrationDate, $nomeEspaco, $localizacao, $cep, $descricao, $recursos) {
        parent::__construct($id, $name, $email, $type, $registrationDate);
        $this->nomeEspaco = $nomeEspaco;
        $this->localizacao = $localizacao;
        $this->cep = $cep;
        $this->descricao = $descricao;
        $this->recursos = $recursos; // Inicializa a nova propriedade
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
    
}
