<?php
require_once 'User.php';
require_once 'Conexao.php';

class Client extends User
{
    private $id;
    private $name;
    private $email;
    private $type;
    private $registrationDate;

    public function __construct($id, $name, $email, $type, $registrationDate)
    {
        $this->id = $id;
        $this->name = $name;
        $this->email = $email;
        $this->type = $type;
        $this->registrationDate = $registrationDate;
    }

    public static function fromUserData($userData)
    {
        return new self(
            $userData['id'],
            $userData['nome'],
            $userData['email'],
            $userData['tipo'],
            $userData['data_registro'],
        );
    }

    public function saveToSession()
    {
        $_SESSION['client'] = [
            'id' => $this->id,
            'nome' => $this->name,
            'email' => $this->email,
            'tipo' => $this->type,
            'data_registro' => $this->registrationDate
        ];
    }

    // Opcional: Adicionar métodos getters para acessar os atributos fora da classe, se necessário.
    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getEmail() {
        return $this->email;
    }

    public function getType() {
        return $this->type;
    }

    public function getRegistrationDate() {
        return $this->registrationDate;
    }
    // Função para obter a primeira palavra do nom

    public function getProfilePicture()
    {
        $pdo = Conexao::getInstance();
        $stmt = $pdo->prepare("SELECT imagem_perfil FROM cliente WHERE id = :id");
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($result && !empty($result['imagem_perfil'])) {
            return "../" . $result['imagem_perfil'];
        } else {
            return "../../upload/user_pfp/userpfp.png";
        }
    }

    public function logoff()
    {
        session_unset();
        session_destroy();
        echo "Redirecionando...";
        header("refresh: 1.5; url=../../index.php");
        exit();
    }

    public function deleteAccount()
    {
        $pdo = Conexao::getInstance();
    
        try {
            $pdo->beginTransaction();
    
            // Delete reservas
            $stmt = $pdo->prepare("DELETE FROM reservas WHERE cliente_id = :id");
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->execute();
    
            // Delete horarios_disponiveis for quadras owned by this user
            $stmt = $pdo->prepare("DELETE hd FROM horarios_disponiveis hd
                                   INNER JOIN quadra q ON hd.quadra_id = q.id
                                   WHERE q.proprietario_id = :id");
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->execute();
    
            // Delete quadras
            $stmt = $pdo->prepare("DELETE FROM quadra WHERE proprietario_id = :id");
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->execute();
    
            // Delete proprietario
            $stmt = $pdo->prepare("DELETE FROM proprietario WHERE id = :id");
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->execute();
    
            // Finally, delete cliente
            $stmt = $pdo->prepare("DELETE FROM cliente WHERE id = :id");
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->execute();
    
            $pdo->commit();
    
            // Destroy the session
            session_unset();
            session_destroy();
    
            echo "<script>
                alert('Sua conta foi deletada com sucesso.');
                window.location.href = '../../index.php';
            </script>";
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "<script>
                alert('Erro ao deletar a conta: " . $e->getMessage() . "');
                window.location.href = '../../views/client/conta.php';
            </script>";
            exit();
        }
    }

    public function uploadFotoPerfil($origem = null)
    {
        // Configurações de upload
        $_UP['pasta'] = '../upload/user_pfp/';
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
            
            // Atualiza a coluna imagem_perfil na tabela cliente
            $stmt = $pdo->prepare("UPDATE cliente SET imagem_perfil = :imagem_perfil WHERE id = :id_user");
            $imagem_perfil = $_UP['pasta'] . $nome_final;
            $stmt->bindParam(':imagem_perfil', $imagem_perfil);
            $stmt->bindParam(':id_user', $this->id, PDO::PARAM_INT);
            $stmt->execute();
    
            $this->exibirAlerta("Imagem de perfil atualizada!", null);
    
            if ($origem === 'editar_perfil') {
                header("refresh: 0.4; url=../views/client/conta.php");
            } elseif ($this->type === 'Dono') {
                header("refresh: 0.4; url=../views/owner/form.quadra1.php");
            } else {
                header("refresh: 0.4; url=../index.php");
            }
            exit();
        } else {
            $this->exibirAlerta("Não foi possível atualizar a imagem de perfil.", $origem);
        }
    }

private function exibirAlerta($mensagem, $origem = null)
{
    echo "<script type=\"text/javascript\">
        alert(\"$mensagem\");
        " . ($origem ? "window.location.href = '../views/client/$origem.php';" : "") . "
    </script>";
}


public function updateClient($name, $email)
{
    $pdo = Conexao::getInstance();

    // Verifica se o email já está em uso por outro usuário
    if ($this->isEmailInUse($pdo, $email, $this->id)) {
        echo "<script>alert('O email já está em uso.'); window.location.href = '../../views/html/profile.php';</script>";
        exit();
    }

    // Atualiza os dados do cliente
    $sql = "UPDATE cliente SET nome = :nome, email = :email WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(':nome', $name);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':id', $this->id);

    if ($stmt->execute()) {
        // Atualiza os dados da instância do cliente
        $this->name = $name;
        $this->email = $email;

        // Atualiza os dados da sessão
        $this->saveToSession();

        echo "<script type=\"text/javascript\">
            alert(\"Informações alteradas com sucesso!\");
            </script>";
        header("refresh: 0.4; url=../index.php");
        exit();
    } else {
        echo "<script type=\"text/javascript\">
            alert(\"Erro ao alterar!\");
            </script>";
        header("refresh: 0.4; url=../index.php");
        exit();
    }
}
    // Função para verificar se o email está em uso por outro usuário
    public function isEmailInUse($pdo, $email, $id)
    {
        $sql = "SELECT COUNT(*) AS total FROM cliente WHERE email = :email AND id != :id";
        $stmt = $pdo->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);
        return $result['total'] > 0;
    }
    public function changePassword($currentPassword, $newPassword, $confirmPassword)
    {
        $pdo = Conexao::getInstance();

        // Verify if the new password matches the confirmation
        if ($newPassword !== $confirmPassword) {
            return "As senhas não coincidem.";
        }

        // Verify if the new password is different from the current one
        if ($currentPassword === $newPassword) {
            return "A nova senha não pode ser igual à senha atual.";
        }

        // Fetch the current hashed password from the database
        $stmt = $pdo->prepare("SELECT senha FROM cliente WHERE id = :id");
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
        $stmt->execute();
        $result = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verify if the current password is correct
        if (!password_verify($currentPassword, $result['senha'])) {
            return "A senha atual está incorreta.";
        }

        // Hash the new password
        $newPasswordHash = password_hash($newPassword, PASSWORD_DEFAULT);

        // Update the password in the database
        $stmt = $pdo->prepare("UPDATE cliente SET senha = :senha WHERE id = :id");
        $stmt->bindParam(':senha', $newPasswordHash);
        $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);

        if ($stmt->execute()) {
            return "Senha alterada com sucesso!";
        } else {
            return "Erro ao alterar a senha. Tente novamente.";
        }
    }

    public function registerOwner($nomeEspaco, $localizacao, $cep, $descricao)
    {
        $pdo = Conexao::getInstance();
    
        try {
            $pdo->beginTransaction();
    
            // Inserir na tabela Proprietario
            $sql = "INSERT INTO proprietario (id, nome_espaco, localizacao, cep, descricao) 
                    VALUES (:id, :nome_espaco, :localizacao, :cep, :descricao)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->bindParam(':nome_espaco', $nomeEspaco, PDO::PARAM_STR);
            $stmt->bindParam(':localizacao', $localizacao, PDO::PARAM_STR);
            $stmt->bindParam(':cep', $cep, PDO::PARAM_STR);
            $stmt->bindParam(':descricao', $descricao, PDO::PARAM_STR);
            $stmt->execute();
    
            // Atualizar o tipo do cliente para 'Dono'
            $sqlUpdateTipo = "UPDATE cliente SET tipo = 'Dono' WHERE id = :id";
            $stmtUpdateTipo = $pdo->prepare($sqlUpdateTipo);
            $stmtUpdateTipo->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmtUpdateTipo->execute();
    
            $pdo->commit();
    
            // Atualizar o tipo na sessão e redirecionar
            $this->type = 'Dono';
            $this->saveToSession();
    
            echo "<script>alert('Registro de proprietário realizado com sucesso!'); 
                  window.location.href='../views/client/form.owner2.php';</script>";
            exit();
        } catch (Exception $e) {
            $pdo->rollBack();
            echo "<script>alert('Erro ao registrar proprietário. Tente novamente.'); 
                  window.location.href='../views/client/form.owner1.php';</script>";
            exit();
        }
    }
    public function registerOwnerResources($recursos)
    {
        $pdo = Conexao::getInstance();
    
        try {
            // Certifique-se de que $recursos é um array, removendo valores vazios
            $recursos = array_filter((array) $recursos);
    
            // Converter o array de recursos para uma string JSON
            $recursosJson = json_encode($recursos);
    
            // Atualizar a tabela proprietario com os recursos
            $sql = "UPDATE proprietario SET recursos = :recursos WHERE id = :id";
            $stmt = $pdo->prepare($sql);
            $stmt->bindParam(':recursos', $recursosJson, PDO::PARAM_STR);
            $stmt->bindParam(':id', $this->id, PDO::PARAM_INT);
            $stmt->execute();
    
            if ($stmt->rowCount() > 0) {
                echo "<script>alert('Recursos registrados com sucesso!'); 
                      window.location.href='../views/owner/gerenciador.php';</script>";
            } else {
                throw new Exception("Nenhum registro foi atualizado. Verifique se o proprietário existe.");
            }
        } catch (Exception $e) {
            echo "<script>alert('Erro ao registrar recursos: " . $e->getMessage() . "'); 
                  window.location.href='../views/client/form.owner2.php';</script>";
        }
        exit();
    }
    public function reserveCourt($quadraId, $dataReserva, $horarioInicio, $horarioFim)
    {
        $pdo = Conexao::getInstance();
    
        try {
            $pdo->beginTransaction();
    
            // Check if there's any overlap with existing reservations
            $stmt = $pdo->prepare("SELECT * FROM horarios_disponiveis 
                                   WHERE quadra_id = :quadra_id 
                                   AND data = :data 
                                   AND (
                                       (horario_inicio < :horario_fim AND horario_fim > :horario_inicio)
                                       OR
                                       (horario_inicio >= :horario_inicio AND horario_inicio < :horario_fim)
                                   )
                                   AND status != 'disponível'");
            $stmt->execute([
                ':quadra_id' => $quadraId,
                ':data' => $dataReserva,
                ':horario_inicio' => $horarioInicio,
                ':horario_fim' => $horarioFim
            ]);
    
            if ($stmt->rowCount() > 0) {
                throw new Exception("O horário selecionado não está totalmente disponível.");
            }
    
            // Insert the reservation
            $stmt = $pdo->prepare("INSERT INTO reservas (cliente_id, quadra_id, data, horario_inicio, horario_fim) 
                                   VALUES (:cliente_id, :quadra_id, :data, :horario_inicio, :horario_fim)");
            $stmt->execute([
                ':cliente_id' => $this->id,
                ':quadra_id' => $quadraId,
                ':data' => $dataReserva,
                ':horario_inicio' => $horarioInicio,
                ':horario_fim' => $horarioFim
            ]);
    
            // Update the horarios_disponiveis table
            $stmt = $pdo->prepare("UPDATE horarios_disponiveis 
                                   SET status = 'reservado' 
                                   WHERE quadra_id = :quadra_id 
                                   AND data = :data 
                                   AND (
                                       (horario_inicio >= :horario_inicio AND horario_inicio < :horario_fim)
                                       OR
                                       (horario_fim > :horario_inicio AND horario_fim <= :horario_fim)
                                       OR
                                       (horario_inicio <= :horario_inicio AND horario_fim >= :horario_fim)
                                   )");
            $stmt->execute([
                ':quadra_id' => $quadraId,
                ':data' => $dataReserva,
                ':horario_inicio' => $horarioInicio,
                ':horario_fim' => $horarioFim
            ]);
    
            $pdo->commit();
            return "Reserva realizada com sucesso!";
        } catch (Exception $e) {
            $pdo->rollBack();
            return "Erro ao realizar a reserva: " . $e->getMessage();
        }
    }
    
}
?>

