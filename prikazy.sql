-- Active: 1696832214451@@127.0.0.1@3306@penzion
DROP DATABASE penzion;

CREATE DATABASE penzion DEFAULT CHARSET utf8mb4;

SHOW DATABASES;

CREATE TABLE stranka (
    id VARCHAR(255) PRIMARY KEY,
    titulek VARCHAR(255),
    menu VARCHAR(255),
    obrazek VARCHAR(255),
    obsah TEXT,
    poradi INT UNSIGNED DEFAULT 0
);

DESC stranka;

INSERT INTO stranka SET id="kocka", titulek="mnau", menu="cici", obrazek="cerna", obsah="maso";

SELECT * FROM stranka;