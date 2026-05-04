-- Active: 1720789923369@@127.0.0.1@3306@primakavarna
create database primakavarna COLLATE utf8_czech_ci;

create table stranka (
    id varchar(100) PRIMARY KEY,
    titulek TEXT,
    menu TEXT,
    obsah text,
    poradi int
);

INSERT INTO stranka set
    id = "uvod",
    titulek = "PrimaKavárna",
    menu = "Domů",
    obsah = "...",
    poradi = 1;

INSERT INTO stranka set
id = "nabidka",
titulek = "PrimaKavárna - Nabídka",
menu = "Nabídka",
obsah = "...",
poradi = 2;

INSERT INTO stranka set
id = "galerie",
titulek = "PrimaKavárna - Galerie",
menu = "Galerie",
obsah = "...",
poradi = 3;

INSERT INTO stranka set
id = "rezervace",
titulek = "PrimaKavárna - Rezervace",
menu = "Rezervace",
obsah = "...",
poradi = 4;

INSERT INTO stranka set
id = "kontakt",
titulek = "PrimaKavárna - - Kontakt",
menu = "Kontakt",
obsah = "...",
poradi = 5;

INSERT INTO stranka set
id = "error",
titulek = "PrimaKavárna",
menu = "",
obsah = "...",
poradi = 6;

INSERT INTO stranka set
id = "test",
titulek = "Test",
menu = "Test",
obsah = "...",
poradi = 7;

delete from stranka where id = "test";
update stranka set titulek = "PrimaKavárna - Kontakt" where id = 'kontakt';

create table admindata (
    id int UNSIGNED PRIMARY KEY AUTO_INCREMENT,
    login varchar(100) NOT NULL,
    password char(60) NOT NULL
);

