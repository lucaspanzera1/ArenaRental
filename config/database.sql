create database arenarental;

use arenarental;

select * from cliente;
select * from proprietario;
select * from recursos_espaco;

-- Tabela Cliente
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

-- Tabela Proprietario (herda de Cliente)
CREATE TABLE proprietario (
    id INT PRIMARY KEY,
    nome_espaco VARCHAR(255) NOT NULL,
    localizacao VARCHAR(255) NOT NULL,
    cep VARCHAR(10) NOT NULL,
    descricao TEXT,
    recursos TEXT,
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
    horario_inicio TIME,
    horario_fim TIME,
    status ENUM('disponível', 'reservado') DEFAULT 'disponível',
    FOREIGN KEY (quadra_id) REFERENCES quadra(id)
);
CREATE TABLE reservas (
    id INT PRIMARY KEY AUTO_INCREMENT,
    cliente_id INT,
    quadra_id INT,
    data DATE,
    horario_inicio TIME,
    horario_fim TIME,
    status ENUM('pendente', 'confirmada', 'cancelada') DEFAULT 'pendente',
    FOREIGN KEY (cliente_id) REFERENCES cliente(id),
    FOREIGN KEY (quadra_id) REFERENCES quadra(id)
);