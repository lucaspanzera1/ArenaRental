<?php
require_once 'Client.php';
require_once 'Conexao.php';

class Owner extends Client
{
    private $nomeEspaco;
    private $localizacao;
    private $cep;
    private $descricao;

    public function __construct($id, $name, $email, $type, $registrationDate, $username, $nomeEspaco, $localizacao, $cep, $descricao)
    {
        parent::__construct($id, $name, $email, $type, $registrationDate, $username);
        $this->nomeEspaco = $nomeEspaco;
        $this->localizacao = $localizacao;
        $this->cep = $cep;
        $this->descricao = $descricao;
    }

    public static function fromClientData($clientData, $ownerData)
    {
        return new self(
            $clientData['id'],
            $clientData['nome'],
            $clientData['email'],
            'Dono',
            $clientData['data_registro'],
            $clientData['username'],
            $ownerData['nome_espaco'],
            $ownerData['localizacao'],
            $ownerData['cep'],
            $ownerData['descricao']
        );
    }

    public function saveToSession()
    {
        parent::saveToSession();
        $_SESSION['owner'] = [
            'nome_espaco' => $this->nomeEspaco,
            'localizacao' => $this->localizacao,
            'cep' => $this->cep,
            'descricao' => $this->descricao
        ];
    }

    // Getters para os novos atributos
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
}
?>