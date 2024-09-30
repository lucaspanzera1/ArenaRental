<?php
require_once 'Client.php';

class User
{
    public static function getAllQuadras($esporte = null, $valor_min = null, $valor_max = null) {
        try {
            $pdo = Conexao::getInstance();
            $sql = "SELECT q.*, p.nome_espaco as nome_proprietario 
                    FROM quadra q
                    LEFT JOIN proprietario p ON q.proprietario_id = p.id
                    WHERE 1=1";
            
            $params = [];
    
            if ($esporte && $esporte !== 'todos') {
                $sql .= " AND q.esporte = :esporte";
                $params[':esporte'] = $esporte;
            }
    
            if ($valor_min !== null) {
                $sql .= " AND q.valor >= :valor_min";
                $params[':valor_min'] = $valor_min;
            }
    
            if ($valor_max !== null) {
                $sql .= " AND q.valor <= :valor_max";
                $params[':valor_max'] = $valor_max;
            }
    
            $sql .= " ORDER BY RAND()";
            $statement = $pdo->prepare($sql);
    
            foreach ($params as $key => $value) {
                $statement->bindValue($key, $value, is_numeric($value) ? PDO::PARAM_INT : PDO::PARAM_STR);
            }
    
            $statement->execute();
            $result = $statement->fetchAll(PDO::FETCH_ASSOC);
            
            error_log("SQL Query: " . $sql);
            error_log("Params: " . print_r($params, true));
            error_log("Número de quadras retornadas: " . count($result));
            
            return $result;
        } catch (PDOException $e) {
            error_log("Erro na função getAllQuadras: " . $e->getMessage());
            return false;
        }
    }
    public static function getQuadraById($id) {
        try {
            $pdo = Conexao::getInstance();
            
            // Consulta SQL para buscar todas as informações das tabelas cliente, proprietario e quadra
            $sql = "SELECT 
                        q.*, 
                        p.nome_espaco, 
                        p.localizacao, 
                        p.cep, 
                        p.descricao as descricao_proprietario, 
                        p.recursos, 
                        c.nome as nome_proprietario, 
                        c.email as email_proprietario, 
                        c.telefone as telefone_proprietario, 
                        c.imagem_perfil as imagem_proprietario
                    FROM quadra q
                    LEFT JOIN proprietario p ON q.proprietario_id = p.id
                    LEFT JOIN cliente c ON p.id = c.id
                    WHERE q.id = :id";
    
            $statement = $pdo->prepare($sql);
            $statement->bindValue(':id', $id, PDO::PARAM_INT);
            $statement->execute();
            
            $result = $statement->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                error_log("Nenhuma quadra encontrada com o ID: " . $id);
                return false;
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Erro ao buscar quadra por ID: " . $e->getMessage());
            return false;
        }
    }
    public static function getProprietarioById($id) {
        try {
            $pdo = Conexao::getInstance();
            $sql = "SELECT c.*, p.*, 
                           GROUP_CONCAT(
                               CONCAT_WS('|', 
                                   q.id, 
                                   q.nome, 
                                   q.esporte, 
                                   q.coberta, 
                                   q.tipo_aluguel, 
                                   q.valor, 
                                   q.imagem_quadra
                               ) SEPARATOR ';;'
                           ) AS quadras
                    FROM cliente c 
                    JOIN proprietario p ON c.id = p.id 
                    LEFT JOIN quadra q ON p.id = q.proprietario_id
                    WHERE c.id = :id
                    GROUP BY c.id";
            
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':id', $id, PDO::PARAM_INT);
            $stmt->execute();
            
            $result = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if (!$result) {
                error_log("Nenhum proprietário encontrado com o ID: " . $id);
                return false;
            }
            
            // Process the quadras string into an array of quadra objects
            if ($result['quadras']) {
                $quadrasArray = [];
                $quadras = explode(';;', $result['quadras']);
                foreach ($quadras as $quadra) {
                    $quadraData = explode('|', $quadra);
                    $quadrasArray[] = [
                        'id' => $quadraData[0],
                        'nome' => $quadraData[1],
                        'esporte' => $quadraData[2],
                        'coberta' => $quadraData[3],
                        'tipo_aluguel' => $quadraData[4],
                        'valor' => $quadraData[5],
                        'imagem_quadra' => $quadraData[6]
                    ];
                }
                $result['quadras'] = $quadrasArray;
            } else {
                $result['quadras'] = [];
            }
            
            return $result;
        } catch (PDOException $e) {
            error_log("Erro ao buscar proprietário por ID: " . $e->getMessage());
            return false;
        }
    }
    

    // Função para login
    public function login($data)
    {
        $pdo = Conexao::getInstance();
        $sql = "SELECT id, nome, email, tipo, data_registro, senha, username FROM cliente WHERE cpf = :cpf";
        $statement = $pdo->prepare($sql);
        $statement->bindValue(":cpf", $data['cpf'], PDO::PARAM_STR);
        $statement->execute();
        $user = $statement->fetch(PDO::FETCH_ASSOC);
    
        if ($user && password_verify($data['password'], $user['senha'])) {
            session_start();
            session_regenerate_id(true);
    
            $client = Client::fromUserData($user);
            $client->saveToSession();
    
            echo "<script type=\"text/javascript\">
            alert(\"Login bem-sucedido, " . htmlspecialchars($user['nome']) . "!\");
                </script>";
            header("refresh: 0.4; url=../index.php");
            exit();
        } else {
            echo "<script type=\"text/javascript\">
            alert(\"Usuário ou senha incorretos!\");
                </script>";
            header("refresh: 0.4; url=../views/auth/login.php");
            exit();
        }
    }


    public function register($data)
    {
        $pdo = Conexao::getInstance();

        // Validação e formatação do CPF
        $cpf = preg_replace('/\D/', '', $data['cpf']);
        if (!$this->validaCPF($cpf)) {
            $this->showErrorAndRedirect("CPF inválido.", "../views/auth/registrar.php");
        }
        $cpfFormatado = vsprintf('%s.%s.%s-%s', str_split($cpf, 3));

        // Verificações de existência
        if ($this->usuarioExiste($pdo, 'cpf', $cpf)) {
            $this->showErrorAndRedirect("CPF já cadastrado.", "../views/auth/registrar.php");
        }
        if ($this->usuarioExiste($pdo, 'email', $data['email'])) {
            $this->showErrorAndRedirect("Email já cadastrado.", "../views/auth/registrar.php");
        }

        // Formatação do telefone (assumindo formato brasileiro)
        $telefone = preg_replace('/\D/', '', $data['telefone']);
        $telefoneFormatado = vsprintf('(%s) %s-%s', [
            substr($telefone, 0, 2), // Código de área
            substr($telefone, 2, 5), // Primeiros 5 dígitos
            substr($telefone, 7, 4)  // Últimos 4 dígitos
        ]);
        

        // Preparação dos dados para inserção
        $tipo = isset($data['tipo']) ? $data['tipo'] : 'cliente';
        $dataNascimento = date('Y-m-d', strtotime($data['nascimento']));

        // Inserção dos dados do usuário no banco de dados
        $sql = "INSERT INTO cliente (cpf, nome, email, telefone, data_nascimento, tipo, data_registro) 
                VALUES (:cpf, :nome, :email, :telefone, :data_nascimento, :tipo, NOW())";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":cpf", $cpfFormatado, PDO::PARAM_STR);
        $stmt->bindValue(":nome", $data['nome'], PDO::PARAM_STR);
        $stmt->bindValue(":email", $data['email'], PDO::PARAM_STR);
        $stmt->bindValue(":telefone", $telefoneFormatado, PDO::PARAM_STR);
        $stmt->bindValue(":data_nascimento", $dataNascimento, PDO::PARAM_STR);
        $stmt->bindValue(":tipo", $tipo, PDO::PARAM_STR);

        if ($stmt->execute()) {
            $userId = $pdo->lastInsertId();
            $user = $this->getUserById($pdo, $userId);

            if ($user) {
                $this->startUserSession($user);
                $this->showSuccessAndRedirect("Registro bem-sucedido, " . htmlspecialchars($user['nome']) . "!", "../views/auth/registrar.user.php");
            }
        } else {
            $this->showErrorAndRedirect("Erro ao registrar. Tente novamente.", "../views/auth/registrar.php");
        }
    }
    // Função para validação de CPF
    private function validaCPF($cpf)
    {
        // Limpa caracteres especiais do CPF
        $cpf = preg_replace('/\D/', '', $cpf);

        // Verifica se o CPF tem 11 dígitos ou é uma sequência repetida
        if (strlen($cpf) != 11 || preg_match('/(\d)\1{10}/', $cpf)) {
            return false;
        }

        // Cálculo para validar os dígitos verificadores do CPF
        for ($t = 9; $t < 11; $t++) {
            for ($d = 0, $c = 0; $c < $t; $c++) {
                $d += $cpf[$c] * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf[$c] != $d) {
                return false;
            }
        }

        return true;
    }

    // Função para verificar se o usuário já existe no banco de dados
    private function usuarioExiste($pdo, $campo, $valor) {
        $sql = "SELECT COUNT(*) FROM cliente WHERE $campo = :valor";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":valor", $valor, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchColumn() > 0;
    }

    private function getUserById($pdo, $id) {
        $sql = "SELECT id, nome, email, tipo, data_registro FROM cliente WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    private function startUserSession($user) {
        session_start();
        session_regenerate_id(true);
        $client = Client::fromUserData($user);
        $client->saveToSession();
    }

    private function showErrorAndRedirect($message, $redirect) {
        echo "<script type='text/javascript'>
            alert('" . addslashes($message) . "');
            window.location.href = '$redirect';
        </script>";
        exit();
    }

    private function showSuccessAndRedirect($message, $redirect) {
        echo "<script type='text/javascript'>
            alert('" . addslashes($message) . "');
            window.location.href = '$redirect';
        </script>";
        exit();
    }
    public function registerAdditionalInfo($data)
    {
        $pdo = Conexao::getInstance();

        // Verificar se o username já existe
        if ($this->usuarioExiste($pdo, 'username', $data['nomeuser'])) {
            $this->showErrorAndRedirect("Nome de usuário já existe.", "../views/auth/registrar.user.php");
        }

        // Verificar se as senhas coincidem
        if ($data['senha'] !== $data['confirmarsenha']) {
            $this->showErrorAndRedirect("As senhas não coincidem.", "../views/auth/registrar.user.php");
        }

        // Hash da senha
        $senhaHash = password_hash($data['senha'], PASSWORD_DEFAULT);

        // Obter o ID do usuário da sessão usando a classe Client
        session_start();
        if (!isset($_SESSION['client'])) {
            $this->showErrorAndRedirect("Sessão de usuário não encontrada.", "../views/auth/login.php");
        }
        $clientData = $_SESSION['client'];
        $client = new Client($clientData['id'], $clientData['nome'], $clientData['email'], $clientData['tipo'], $clientData['data_registro']);
        $userId = $client->getId();

        // Atualizar o registro do usuário
        $sql = "UPDATE cliente SET username = :username, senha = :senha WHERE id = :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(":username", $data['nomeuser'], PDO::PARAM_STR);
        $stmt->bindValue(":senha", $senhaHash, PDO::PARAM_STR);
        $stmt->bindValue(":id", $userId, PDO::PARAM_INT);

        if ($stmt->execute()) {
            $this->showSuccessAndRedirect("Registro completo com sucesso!", "../views/client/foto_perfil.php");
        } else {
            $this->showErrorAndRedirect("Erro ao completar o registro. Tente novamente.", "../views/auth/registrar.user.php");
        }
    }
}
?>