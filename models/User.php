<?php
require_once 'Client.php';

class User
{
    public static function getAllQuadras($esporte = null, $valor_min = null, $valor_max = null) {
        $pdo = Conexao::getInstance();
        $sql = "SELECT * FROM quadra WHERE 1=1";
        
        // Adiciona a cláusula de esporte somente se $esporte for diferente de 'todos' e não for null
        if ($esporte && $esporte !== 'todos') {
            $sql .= " AND esporte = :esporte";
        }
    
        // Filtro por faixa de valores
        if ($valor_min !== null && $valor_max !== null) {
            $sql .= " AND valor BETWEEN :valor_min AND :valor_max";
        }
    
        // Ordenar de forma aleatória
        $sql .= " ORDER BY RAND()";
        $statement = $pdo->prepare($sql);
    
        // Vincula o parâmetro esporte se necessário
        if ($esporte && $esporte !== 'todos') {
            $statement->bindValue(':esporte', $esporte);
        }
    
        // Vincula os parâmetros de valor mínimo e máximo
        if ($valor_min !== null && $valor_max !== null) {
            $statement->bindValue(':valor_min', $valor_min, PDO::PARAM_STR);
            $statement->bindValue(':valor_max', $valor_max, PDO::PARAM_STR);
        }
    
        $statement->execute();
        return $statement->fetchAll(PDO::FETCH_ASSOC);
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