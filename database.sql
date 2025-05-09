
CREATE DATABASE IF NOT EXISTS studio_dentistico;

USE studio_dentistico;

CREATE TABLE IF NOT EXISTS clienti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    email VARCHAR(100),
    telefono VARCHAR(20)
);

CREATE TABLE IF NOT EXISTS appuntamenti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    cliente INT,
    data DATE,
    ora TIME,
    FOREIGN KEY (cliente) REFERENCES clienti(id)
);

CREATE TABLE IF NOT EXISTS trattamenti (
    id_trattamento INT AUTO_INCREMENT PRIMARY KEY,
    nome VARCHAR(100),
    descrizione TEXT,
    costo DECIMAL(10, 2)
);
