<?php
require_once 'Client.php';
require_once 'Conexao.php';

class Owner extends Client {
    private $id;
    private $nomeEspaco;
    private $localizacao;
    private $cep;
    private $descricao;
    private $recursos;

    public function __construct($id, $name, $email, $type, $registrationDate, $nomeEspaco, $localizacao, $cep, $descricao, $recursos) {
        parent::__construct($id, $name, $email, $type, $registrationDate);
        $this->id = $id;
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
    public static function registerQuadra($ownerId, $nomeQuadra, $esporte, $coberta, $tipoAluguel, $valor) {
        $pdo = Conexao::getInstance();

        try {
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

            $stmt->execute();

            return $pdo->lastInsertId();
        } catch (PDOException $e) {
            error_log("Erro ao registrar quadra: " . $e->getMessage());
            return false;
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
    public function uploadFotoPerfilOwner($quadraId, $origem = null)
{
    // Configurações de upload
    $_UP['pasta'] = '../upload/quadra_img/';
    $_UP['tamanho'] = 1024 * 1024 * 100; // 100MB
    $_UP['extensoes'] = array('png', 'jpg', 'jpeg', 'gif');

    if ($_FILES['arquivo']['error'] != 0) {
        die("Não foi possível fazer o upload, erro: " . $_FILES['arquivo']['error']);
    }

    if ($_UP['tamanho'] < $_FILES['arquivo']['size']) {
        $this->exibirAlerta("Arquivo muito grande.", $origem);
        return;
    }

    $extensao = strtolower(pathinfo($_FILES['arquivo']['name'], PATHINFO_EXTENSION));
    if (!in_array($extensao, $_UP['extensoes'])) {
        $this->exibirAlerta("Extensão não permitida.", $origem);
        return;
    }

    $nome_final = uniqid() . '.' . $extensao;

    if (move_uploaded_file($_FILES['arquivo']['tmp_name'], $_UP['pasta'] . $nome_final)) {
        $pdo = Conexao::getInstance();

        // Atualiza a coluna imagem_quadra na tabela quadra usando o ID da quadra
        $stmt = $pdo->prepare("UPDATE quadra SET imagem_quadra = :imagem_quadra WHERE id = :quadra_id");
        $imagem_quadra = $_UP['pasta'] . $nome_final;
        $stmt->bindParam(':imagem_quadra', $imagem_quadra);
        $stmt->bindParam(':quadra_id', $quadraId, PDO::PARAM_INT);
        $stmt->execute();
    } else {
        $this->exibirAlerta("Não foi possível atualizar a imagem de perfil.", $origem);
    }
}   
public function salvarHorarios($quadraId, $horarios) {
    $db = Conexao::getInstance();
    
    // Preparar a declaração SQL para inserir horários
    $stmt = $db->prepare("INSERT INTO horarios_disponiveis (quadra_id, data, horario_inicio, horario_fim, status) VALUES (?, ?, ?, ?, 'disponível')");
    
    // Obter a data atual
    $dataAtual = new DateTime();
    
    foreach ($horarios as $dia => $dados) {
        // Validar os horários
        if (!$this->validarHorarios($dados['inicio'], $dados['fim'], $dados['intervalo_inicio'], $dados['intervalo_fim'])) {
            throw new Exception("Horários inválidos para o dia: " . ucfirst($dia));
        }
        
        // Converter o dia da semana para um número (0 = Domingo, 1 = Segunda, etc.)
        $diaNumerico = array_search($dia, ['domingo', 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado']);
        
        // Encontrar a próxima data para este dia da semana
        $data = clone $dataAtual;
        while ($data->format('w') != $diaNumerico) {
            $data->modify('+1 day');
        }
        
        // Criar objetos DateTime para o início e fim do período
        $horaInicio = DateTime::createFromFormat('H:i', $dados['inicio']);
        $horaFim = DateTime::createFromFormat('H:i', $dados['fim']);
        
        // Calcular e inserir os intervalos de 1 hora
        $intervaloAtual = clone $horaInicio;
        while ($intervaloAtual < $horaFim) {
            $proximoIntervalo = clone $intervaloAtual;
            $proximoIntervalo->modify('+1 hour');
            
            // Se o próximo intervalo ultrapassar o horário de fim, ajustar para o horário de fim
            if ($proximoIntervalo > $horaFim) {
                $proximoIntervalo = clone $horaFim;
            }
            
            // Inserir o intervalo de 1 hora
            $stmt->execute([
                $quadraId,
                $data->format('Y-m-d'),
                $intervaloAtual->format('H:i'),
                $proximoIntervalo->format('H:i')
            ]);
            
            // Mover para o próximo intervalo
            $intervaloAtual = $proximoIntervalo;
        }
        
        // Tratar o intervalo de indisponibilidade, se existir
        if ($dados['intervalo_inicio'] !== '' && $dados['intervalo_fim'] !== '') {
            $horaIntervaloInicio = DateTime::createFromFormat('H:i', $dados['intervalo_inicio']);
            $horaIntervaloFim = DateTime::createFromFormat('H:i', $dados['intervalo_fim']);
            
            $stmtIntervalo = $db->prepare("UPDATE horarios_disponiveis SET status = 'indisponível' WHERE quadra_id = ? AND data = ? AND horario_inicio >= ? AND horario_fim <= ?");
            $stmtIntervalo->execute([
                $quadraId,
                $data->format('Y-m-d'),
                $horaIntervaloInicio->format('H:i'),
                $horaIntervaloFim->format('H:i')
            ]);
        }
    }
    
    return true;
}

private function validarHorarios($inicio, $fim, $intervaloInicio, $intervaloFim) {
    $horaInicio = DateTime::createFromFormat('H:i', $inicio);
    $horaFim = DateTime::createFromFormat('H:i', $fim);
    
    if ($horaFim <= $horaInicio) {
        return false;
    }
    
    if ($intervaloInicio !== '' && $intervaloFim !== '') {
        $horaIntervaloInicio = DateTime::createFromFormat('H:i', $intervaloInicio);
        $horaIntervaloFim = DateTime::createFromFormat('H:i', $intervaloFim);
        
        if ($horaIntervaloFim <= $horaIntervaloInicio ||
            $horaIntervaloInicio < $horaInicio ||
            $horaIntervaloFim > $horaFim) {
            return false;
        }
    }
    
    return true;
}

}
