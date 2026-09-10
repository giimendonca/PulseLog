CREATE DATABASE IF NOT EXISTS pulse_log;
USE pulse_log;

CREATE TABLE usuarios (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nome VARCHAR(100) NOT NULL,
    email VARCHAR(150) NOT NULL UNIQUE,
    senha VARCHAR(255) NOT NULL,
    aceite_lgpd BOOLEAN NOT NULL DEFAULT FALSE,
    aceite_lgpd_em DATETIME NULL,
    criado_em DATETIME DEFAULT CURRENT_TIMESTAMP
);

CREATE TABLE nfc_tags (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    codigo VARCHAR(100) NOT NULL UNIQUE,
    ativo BOOLEAN DEFAULT TRUE,

    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);

CREATE TABLE crises (
    id INT PRIMARY KEY AUTO_INCREMENT,
    usuario_id INT NOT NULL,
    inicio DATETIME NOT NULL,
    fim DATETIME NULL,
    intensidade INT NULL,
    observacoes TEXT NULL,
    token_nfc varchar(255) NULL UNIQUE,

    FOREIGN KEY (usuario_id) REFERENCES usuarios(id)
);