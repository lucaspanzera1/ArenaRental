<?php
require_once 'Client.php';
require_once 'Notification.php';
require_once 'Conexao.php';

class Owner extends Client
{
    private $id;
    private $nomeEspaco;
    private $localizacao;
    private $cep;
    private $descricao;
    private $recursos;

    public function __construct($id, $name, $email, $type, $registrationDate, $nomeEspaco, $localizacao, $cep, $descricao, $recursos)
    {
        parent::__construct($id, $name, $email, $type, $registrationDate);
        $this->id = $id;
        $this->nomeEspaco = $nomeEspaco;
        $this->localizacao = $localizacao;
        $this->cep = $cep;
        $this->descricao = $descricao;
        $this->recursos = $recursos;
    }
    // Getters para acessar os atributos
    public function getNomeEspaco()
    {
        return $this->nomeEspaco;
    }

    public function getLocalizacao()
    {
        return $this->localizacao;
    }

    public function getCep()
    {
        return $this->cep;
    }

    public function getDescricao()
    {
        return $this->descricao;
    }

    public function getRecursos()
    { // Novo getter para 'recursos'
        return $this->recursos;
    }
    public static function getOwnerById($ownerId)
    {
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
    public static function registerQuadra($ownerId, $nomeQuadra, $esporte, $coberta, $tipoAluguel, $valor)
    {
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


    public function getQuadras()
    {
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
    public function salvarHorarios($quadraId, $horarios)
    {
        $db = Conexao::getInstance();
    
        // Preparar a declaração SQL para inserir horários
        $stmt = $db->prepare("INSERT INTO horarios_disponiveis (quadra_id, data, dia_da_semana, horario_inicio, horario_fim, status) VALUES (?, ?, ?, ?, ?, ?)");
    
        // Obter o primeiro e último dia do mês atual
        $dataAtual = new DateTime();
        $primeiroDiaMes = new DateTime($dataAtual->format('Y-m-01'));
        $ultimoDiaMes = new DateTime($dataAtual->format('Y-m-t'));
    
        // Array para mapear números do dia da semana para nomes em português
        $diasSemana = ['Domingo', 'Segunda-feira', 'Terça-feira', 'Quarta-feira', 'Quinta-feira', 'Sexta-feira', 'Sábado'];
    
        foreach ($horarios as $dia => $dados) {
            // Validar os horários
            if (!$this->validarHorarios($dados['inicio'], $dados['fim'], $dados['intervalo_inicio'], $dados['intervalo_fim'])) {
                throw new Exception("Horários inválidos para o dia: " . ucfirst($dia));
            }
    
            // Converter o dia da semana para um número (0 = Domingo, 1 = Segunda, etc.)
            $diaNumerico = array_search($dia, ['domingo', 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado']);
    
            // Iterar sobre todos os dias do mês atual
            $dataIteracao = clone $primeiroDiaMes;
            while ($dataIteracao <= $ultimoDiaMes) {
                // Verificar se o dia da semana corresponde
                if ($dataIteracao->format('w') == $diaNumerico) {
                    // Obter o nome do dia da semana em português
                    $diaDaSemana = $diasSemana[$diaNumerico];
    
                    // Criar objetos DateTime para o início e fim do período
                    $horaInicio = DateTime::createFromFormat('H:i', $dados['inicio']);
                    $horaFim = DateTime::createFromFormat('H:i', $dados['fim']);
                    
                    // Criar objetos DateTime para o intervalo, se existir
                    $horaIntervaloInicio = !empty($dados['intervalo_inicio']) ? DateTime::createFromFormat('H:i', $dados['intervalo_inicio']) : null;
                    $horaIntervaloFim = !empty($dados['intervalo_fim']) ? DateTime::createFromFormat('H:i', $dados['intervalo_fim']) : null;
    
                    // Calcular e inserir os intervalos de 1 hora
                    $intervaloAtual = clone $horaInicio;
                    while ($intervaloAtual < $horaFim) {
                        $proximoIntervalo = clone $intervaloAtual;
                        $proximoIntervalo->modify('+1 hour');
    
                        // Se o próximo intervalo ultrapassar o horário de fim, ajustar para o horário de fim
                        if ($proximoIntervalo > $horaFim) {
                            $proximoIntervalo = clone $horaFim;
                        }
    
                        // Verificar se este horário está dentro do período de intervalo
                        $status = 'disponível';
                        if ($horaIntervaloInicio && $horaIntervaloFim) {
                            if ($intervaloAtual >= $horaIntervaloInicio && $proximoIntervalo <= $horaIntervaloFim) {
                                $status = 'intervalo';
                            }
                        }
    
                        // Inserir o intervalo de 1 hora com o status apropriado
                        $stmt->execute([
                            $quadraId,
                            $dataIteracao->format('Y-m-d'),
                            $diaDaSemana,
                            $intervaloAtual->format('H:i'),
                            $proximoIntervalo->format('H:i'),
                            $status
                        ]);
    
                        // Mover para o próximo intervalo
                        $intervaloAtual = $proximoIntervalo;
                    }
                }
                // Avançar para o próximo dia
                $dataIteracao->modify('+1 day');
            }
        }
    
        return true;
    }
    
    private function validarHorarios($inicio, $fim, $intervaloInicio, $intervaloFim)
    {
        $horaInicio = DateTime::createFromFormat('H:i', $inicio);
        $horaFim = DateTime::createFromFormat('H:i', $fim);
    
        if ($horaFim <= $horaInicio) {
            return false;
        }
    
        if ($intervaloInicio !== '' && $intervaloFim !== '') {
            $horaIntervaloInicio = DateTime::createFromFormat('H:i', $intervaloInicio);
            $horaIntervaloFim = DateTime::createFromFormat('H:i', $intervaloFim);
    
            if (
                $horaIntervaloFim <= $horaIntervaloInicio ||
                $horaIntervaloInicio < $horaInicio ||
                $horaIntervaloFim > $horaFim
            ) {
                return false;
            }
        }
    
        return true;
    }
    public static function updateQuadra($quadraId, $nomeQuadra, $esporte, $coberta, $tipoAluguel, $valor)
{
    $pdo = Conexao::getInstance();

    try {
        $stmt = $pdo->prepare("
            UPDATE quadra 
            SET nome = :nome, esporte = :esporte, coberta = :coberta, tipo_aluguel = :tipo_aluguel, valor = :valor
            WHERE id = :quadra_id
        ");

        $stmt->bindParam(':quadra_id', $quadraId, PDO::PARAM_INT);
        $stmt->bindParam(':nome', $nomeQuadra, PDO::PARAM_STR);
        $stmt->bindParam(':esporte', $esporte, PDO::PARAM_STR);
        $stmt->bindParam(':coberta', $coberta, PDO::PARAM_BOOL);
        $stmt->bindParam(':tipo_aluguel', $tipoAluguel, PDO::PARAM_STR);
        $stmt->bindParam(':valor', $valor, PDO::PARAM_STR);

        $stmt->execute();

        return true;
    } catch (PDOException $e) {
        error_log("Erro ao atualizar quadra: " . $e->getMessage());
        return false;
    }
}

public static function getHorariosDisponiveis($quadraId, $data)
{
    $pdo = Conexao::getInstance();

    // Consulta SQL
    $stmt = $pdo->prepare("
        SELECT 
            hd.horario_inicio, 
            hd.horario_fim, 
            hd.status,
            r.id AS reserva_id,
            r.valor AS valor_reserva,
            c.nome AS nome_cliente,
            c.username AS username_cliente
        FROM 
            horarios_disponiveis hd
        LEFT JOIN 
            reservas r ON hd.quadra_id = r.quadra_id 
            AND hd.data = r.data 
            AND hd.horario_inicio = r.horario_inicio
        LEFT JOIN 
            cliente c ON r.cliente_id = c.id
        WHERE 
            hd.quadra_id = :quadra_id AND hd.data = :data
        ORDER BY 
            hd.horario_inicio
    ");

    $stmt->bindParam(':quadra_id', $quadraId, PDO::PARAM_INT);
    $stmt->bindParam(':data', $data, PDO::PARAM_STR);
    $stmt->execute();

    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}


public static function reservarQuadra($quadra_id, $data, $horario_inicio, $horario_fim)
    {
        try {
            $pdo = Conexao::getInstance();
            $pdo->beginTransaction();

            // Verificar se o cliente "por fora" já existe
            $stmt = $pdo->prepare("SELECT id FROM cliente WHERE nome = 'Cliente por fora' LIMIT 1");
            $stmt->execute();
            $cliente = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$cliente) {
                // Se não existe, criar o cliente "por fora"
                $stmt = $pdo->prepare("INSERT INTO cliente (cpf, nome, email, senha, tipo, username, imagem_perfil) 
                                        VALUES ('00000000000', 'Cliente por fora', 'cliente@porfora.com', 
                                                '', 'cliente', 'Cliente por fora', 'default.jpg')");
                $stmt->execute();
                $cliente_id = $pdo->lastInsertId();
            } else {
                $cliente_id = $cliente['id'];
            }

            // Inserir a reserva
            $stmt = $pdo->prepare("INSERT INTO reservas (cliente_id, quadra_id, data, horario_inicio, horario_fim, status) 
                                  VALUES (:cliente_id, :quadra_id, :data, :horario_inicio, :horario_fim, 'confirmada')");
            $stmt->execute([
                ':cliente_id' => $cliente_id,
                ':quadra_id' => $quadra_id,
                ':data' => $data,
                ':horario_inicio' => $horario_inicio,
                ':horario_fim' => $horario_fim
            ]);

            // Atualizar status da tabela de horários disponíveis
            $stmt = $pdo->prepare("UPDATE horarios_disponiveis 
                                  SET status = 'reservado' 
                                  WHERE quadra_id = :quadra_id 
                                  AND data = :data 
                                  AND horario_inicio = :horario_inicio 
                                  AND horario_fim = :horario_fim");
            $stmt->execute([
                ':quadra_id' => $quadra_id,
                ':data' => $data,
                ':horario_inicio' => $horario_inicio,
                ':horario_fim' => $horario_fim
            ]);

            $pdo->commit();
            return "Reserva realizada com sucesso!";
        } catch (Exception $e) {
            $pdo->rollBack();
            return "Erro ao realizar a reserva: " . $e->getMessage();
        }
    }

    public function getReservasPendentes() {
        $pdo = Conexao::getInstance();

        $stmt = $pdo->prepare("
                SELECT 
                    r.id as reserva_id,
                    r.data,
                    r.horario_inicio,
                    r.horario_fim,
                    r.valor,
                    q.nome as nome_quadra,
                    c.nome as nome_cliente,
                    c.email as email_cliente,
                    c.telefone as telefone_cliente
                FROM reservas r
                INNER JOIN quadra q ON r.quadra_id = q.id
                INNER JOIN cliente c ON r.cliente_id = c.id
                WHERE q.proprietario_id = :proprietario_id 
                AND r.status = 'pendente'
                ORDER BY r.data ASC, r.horario_inicio ASC
            ");
            
            $stmt->bindValue(':proprietario_id', $this->id);
            $stmt->execute();
            
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } 

        public function confirmarReserva($reservaId)
{
    $pdo = Conexao::getInstance();

    try {
        $pdo->beginTransaction();

        // Obtém os detalhes da reserva
        $stmt = $pdo->prepare("SELECT r.*, c.nome as cliente_nome 
                              FROM reservas r 
                              JOIN cliente c ON r.cliente_id = c.id 
                              WHERE r.id = :reserva_id");
        $stmt->execute([':reserva_id' => $reservaId]);
        $reserva = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$reserva) {
            throw new Exception("Reserva não encontrada.");
        }

        $stmt = $pdo->prepare("UPDATE reservas SET status = 'confirmada' WHERE id = :reserva_id");
        $stmt->execute([':reserva_id' => $reservaId]);

        // Atualiza o status na tabela horarios_disponiveis
        $stmt = $pdo->prepare("UPDATE horarios_disponiveis 
                             SET status = 'reservado' 
                             WHERE quadra_id = :quadra_id 
                             AND data = :data 
                             AND horario_inicio = :horario_inicio 
                             AND horario_fim = :horario_fim");
        
        $stmt->execute([
            ':quadra_id' => $reserva['quadra_id'],
            ':data' => $reserva['data'],
            ':horario_inicio' => $reserva['horario_inicio'],
            ':horario_fim' => $reserva['horario_fim']
        ]);
        
        // Criar notificação para o cliente
        $notification = new Notification();
        $mensagem = "Sua reserva para o dia " . date('d/m/Y', strtotime($reserva['data'])) . 
                   " às " . date('H:i', strtotime($reserva['horario_inicio'])) . 
                   " foi confirmada!";
                   
        $notification->criarNotificacao(
            $reserva['cliente_id'],  // destinatário (cliente)
            $this->id,               // remetente (proprietário)
            'confirmacao_reserva',
            $mensagem,
            $reservaId
        );

        $pdo->commit();
        return "Reserva confirmada com sucesso!";
    } catch (Exception $e) {
        $pdo->rollBack();
        return "Erro ao confirmar a reserva: " . $e->getMessage();
    }
}

public function cancelarReserva($reservaId)
{
    $pdo = Conexao::getInstance();

    try {
        $pdo->beginTransaction();

        // Obtém os detalhes da reserva
        $stmt = $pdo->prepare("SELECT r.*, c.nome as cliente_nome 
                              FROM reservas r 
                              JOIN cliente c ON r.cliente_id = c.id 
                              WHERE r.id = :reserva_id");
        $stmt->execute([':reserva_id' => $reservaId]);
        $reserva = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$reserva) {
            throw new Exception("Reserva não encontrada.");
        }

        $quadraId = $reserva['quadra_id'];
        $dataReserva = $reserva['data'];
        $horarioInicio = $reserva['horario_inicio'];
        $horarioFim = $reserva['horario_fim'];

        // Exclui a reserva
        $stmt = $pdo->prepare("DELETE FROM reservas WHERE id = :reserva_id");
        $stmt->execute([':reserva_id' => $reservaId]);

        // Atualiza os horários disponíveis
        $stmt = $pdo->prepare("SELECT * FROM horarios_disponiveis 
                               WHERE quadra_id = :quadra_id 
                               AND data = :data 
                               AND (
                                   (horario_inicio <= :horario_fim AND horario_fim >= :horario_inicio)
                               )");
        $stmt->execute([
            ':quadra_id' => $quadraId,
            ':data' => $dataReserva,
            ':horario_inicio' => $horarioInicio,
            ':horario_fim' => $horarioFim
        ]);

        $horariosDisponiveis = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($horariosDisponiveis as $horario) {
            $inicioDisponivel = strtotime($horario['horario_inicio']);
            $fimDisponivel = strtotime($horario['horario_fim']);
            $inicioReserva = strtotime($horarioInicio);
            $fimReserva = strtotime($horarioFim);

            // Atualiza o status para 'disponível'
            if ($inicioReserva <= $inicioDisponivel && $fimReserva >= $fimDisponivel) {
                $stmt = $pdo->prepare("UPDATE horarios_disponiveis 
                                       SET status = 'disponível' 
                                       WHERE id = :horario_id");
                $stmt->execute([':horario_id' => $horario['id']]);
            } else {
                if ($inicioReserva > $inicioDisponivel) {
                    // Atualiza o horário de fim do horário disponível
                    $stmt = $pdo->prepare("UPDATE horarios_disponiveis 
                                           SET horario_fim = :novo_fim 
                                           WHERE id = :horario_id");
                    $stmt->execute([
                        ':novo_fim' => date('H:i:s', $inicioReserva),
                        ':horario_id' => $horario['id']
                    ]);
                }

                if ($fimReserva < $fimDisponivel) {
                    // Insere um novo horário disponível após o fim da reserva
                    $stmt = $pdo->prepare("INSERT INTO horarios_disponiveis 
                                           (quadra_id, data, horario_inicio, horario_fim, status) 
                                           VALUES (:quadra_id, :data, :novo_inicio, :horario_fim, 'disponível')");
                    $stmt->execute([
                        ':quadra_id' => $quadraId,
                        ':data' => $dataReserva,
                        ':novo_inicio' => date('H:i:s', $fimReserva),
                        ':horario_fim' => $horario['horario_fim']
                    ]);
                }
            }
        }


        // Criar notificação para o cliente
        $notification = new Notification();
        $mensagem = "Sua reserva para o dia " . date('d/m/Y', strtotime($reserva['data'])) . 
                   " às " . date('H:i', strtotime($reserva['horario_inicio'])) . 
                   " foi cancelada.";
                   
        $notification->criarNotificacao(
            $reserva['cliente_id'],  // destinatário (cliente)
            $this->id,               // remetente (proprietário)
            'cancelamento_reserva',
            $mensagem,
            $reservaId
        );

        $pdo->commit();
        return "Reserva cancelada com sucesso!";
    } catch (Exception $e) {
        $pdo->rollBack();
        return "Erro ao cancelar a reserva: " . $e->getMessage();
    }
}
    }
    