CREATE DATABASE IF NOT EXISTS sistema_ramais; 

-- USE sistema_ramais

CREATE TABLE IF NOT EXISTS sistema_ramais.ramais(
    nome VARCHAR(8) NOT NULL,
    ramal VARCHAR(8) NOT NULL,
    ip VARCHAR(16) NOT NULL,
    on_line BOOLEAN NOT NULL,
    status VARCHAR(8) NOT NULL,
    PRIMARY KEY(nome)
);

CREATE TABLE IF NOT EXISTS sistema_ramais.callcenter(
    agente VARCHAR(32) NOT NULL,
    status VARCHAR(16) NOT NULL,
    nome VARCHAR(8) NOT NULL,
    PRIMARY KEY(nome)
);