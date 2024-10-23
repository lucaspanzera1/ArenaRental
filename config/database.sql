create database arenarental;

use arenarental;

select * from cliente;
select * from quadra;
select * from proprietario;
select * from horarios_disponiveis;
select * from horarios_disponiveis where status = "reservado";
select * from reservas;
select * from  notificacoes;

CREATE TABLE cliente (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cpf VARCHAR(14) NOT NULL,
    nome VARCHAR(255) NOT NULL,
    email VARCHAR(255) NOT NULL,
    senha VARCHAR(255) NOT NULL,
    tipo VARCHAR(10) NOT NULL,
    data_registro DATETIME,
    telefone VARCHAR(20),
    data_nascimento DATE,
    username VARCHAR(50) NOT NULL,
    imagem_perfil VARCHAR(220) NOT NULL
);
CREATE TABLE proprietario (
    id INT PRIMARY KEY,
    nome_espaco VARCHAR(255) NOT NULL,
    localizacao VARCHAR(255) NOT NULL,
    cep VARCHAR(10) NOT NULL,
    bairro VARCHAR(255) NOT NULL,
    regiao VARCHAR(255) NOT NULL,
    descricao TEXT,
    recursos TEXT,
    imagem1 VARCHAR(220) NOT NULL,
    imagem2 VARCHAR(220) NOT NULL,
    imagem3 VARCHAR(220) NOT NULL,
    imagem4 VARCHAR(220) NOT NULL,
    FOREIGN KEY (id) REFERENCES Cliente(id)
);
CREATE TABLE quadra (
    id INT PRIMARY KEY AUTO_INCREMENT,
    proprietario_id INT,
    nome VARCHAR(255) NOT NULL,
    esporte VARCHAR(100) NOT NULL,
    coberta BOOLEAN NOT NULL,
    tipo_aluguel ENUM('day use', 'por hora') NOT NULL,
	valor DECIMAL(10, 2) NOT NULL,
    imagem_quadra VARCHAR(220) NOT NULL,
    FOREIGN KEY (proprietario_id) REFERENCES proprietario(id)
);
CREATE TABLE horarios_disponiveis (
    id INT PRIMARY KEY AUTO_INCREMENT,
    quadra_id INT,
    data DATE,
    dia_da_semana VARCHAR(100) NOT NULL,
    horario_inicio TIME,
    horario_fim TIME,
	status ENUM('disponível', 'reservado', 'pendente','intervalo') DEFAULT 'disponível',
    FOREIGN KEY (quadra_id) REFERENCES quadra(id)
);
CREATE TABLE reservas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cliente_id INT,
    quadra_id INT,
    data DATE,
    horario_inicio TIME,
    horario_fim TIME,
    valor DECIMAL(10, 2),  -- Adiciona a coluna para armazenar o valor da reserva
    status ENUM('pendente', 'confirmada', 'cancelada') DEFAULT 'pendente',
    FOREIGN KEY (cliente_id) REFERENCES cliente(id),
    FOREIGN KEY (quadra_id) REFERENCES quadra(id)
);
CREATE TABLE notificacoes (
    id INT PRIMARY KEY AUTO_INCREMENT,
    destinatario_id INT NOT NULL,
    remetente_id INT NOT NULL,
    tipo ENUM('nova_reserva', 'confirmacao_reserva', 'cancelamento_reserva') NOT NULL,
    mensagem TEXT NOT NULL,
    reserva_id INT,
    data_criacao DATETIME DEFAULT CURRENT_TIMESTAMP,
    lida BOOLEAN DEFAULT FALSE,
    FOREIGN KEY (destinatario_id) REFERENCES cliente(id),
    FOREIGN KEY (remetente_id) REFERENCES cliente(id),
    FOREIGN KEY (reserva_id) REFERENCES reservas(id)
);