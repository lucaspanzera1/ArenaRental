create database bd_arenauser;

use bd_arenauser;

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

    CREATE TABLE imagem (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome_imagem VARCHAR(220) NOT NULL,
        id_user INT NOT NULL,
        FOREIGN KEY (id_user) REFERENCES cadastro(id)
    );
    
    CREATE TABLE quadra (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome_quadra VARCHAR(255) NOT NULL,
    esporte VARCHAR(255) NOT NULL,
    localizacao VARCHAR(255) NOT NULL,
    descricao TEXT,
    valor DECIMAL(10, 2) NOT NULL,
    id_user INT NOT NULL,
    nome_dono VARCHAR(255) NOT NULL,
    horario_abre VARCHAR(5) NOT NULL,
    horario_fecha VARCHAR(5) NOT NULL,
    FOREIGN KEY (id_user) REFERENCES cadastro(id),
    FOREIGN KEY (nome_dono) REFERENCES cadastro(nome)
);

   CREATE TABLE imagem_quadra (
        id INT AUTO_INCREMENT PRIMARY KEY,
        nome_imagem VARCHAR(220) NOT NULL,
        id_dono INT NOT NULL,
        FOREIGN KEY (id_dono) REFERENCES quadra(id)
    );

SELECT * FROM cadastro;	
SELECT * FROM quadra;	
SELECT * FROM imagem;
SELECT * FROM imagem_quadra;
