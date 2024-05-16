drop database demo;
create database demo;


use demo;


create table client (
    id int AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(100),
    prenom VARCHAR(100),
    ville VARCHAR(100)
);

-- CREATE INDEX idx1 on client(nom);