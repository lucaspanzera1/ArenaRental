create database arenarental;

use arenarental;

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
    descricao TEXT,
    FOREIGN KEY (id) REFERENCES Cliente(id)
);
CREATE TABLE recursos_espaco (
    id INT PRIMARY KEY AUTO_INCREMENT,
    proprietario_id INT,
    recurso VARCHAR(50) NOT NULL,
    FOREIGN KEY (proprietario_id) REFERENCES proprietario(id)
);

CREATE TABLE quadra (
    id INT PRIMARY KEY AUTO_INCREMENT,
    proprietario_id INT,
    nome VARCHAR(255) NOT NULL,
    esporte VARCHAR(100) NOT NULL,
    coberta BOOLEAN NOT NULL,
    tipo_aluguel ENUM('day_use', 'por_hora') NOT NULL,
    FOREIGN KEY (proprietario_id) REFERENCES proprietario(id)
);

CREATE TABLE horario_funcionamento (
    id INT PRIMARY KEY AUTO_INCREMENT,
    quadra_id INT,
    dia_semana ENUM('domingo', 'segunda', 'terca', 'quarta', 'quinta', 'sexta', 'sabado') NOT NULL,
    hora_abertura TIME NOT NULL,
    hora_fechamento TIME NOT NULL,
    FOREIGN KEY (quadra_id) REFERENCES quadra(id)
);

