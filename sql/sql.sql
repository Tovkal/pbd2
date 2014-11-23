CREATE DATABASE IF NOT EXISTS BDII_08 CHARACTER SET utf8 COLLATE utf8_general_ci;

USE BDII_08;

CREATE TABLE IF NOT EXISTS Seccio (
	codi_seccio INT AUTO_INCREMENT,
	descripcio VARCHAR(1000),
	preu DECIMAL(5, 2),
	foto_generica_seccio VARCHAR(100),
	PRIMARY KEY(codi_seccio)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS Privilegi (
	id INT AUTO_INCREMENT,
	nivellPrivilegi VARCHAR(20),
	PRIMARY KEY (id)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS Usuari (
	id INT AUTO_INCREMENT,
	userID VARCHAR(100) NOT NULL UNIQUE,
	nom VARCHAR(30) NOT NULL,
	nivellPrivilegi INT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (nivellPrivilegi) REFERENCES Privilegi(id)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS Anunci (
	id INT AUTO_INCREMENT,
	titol_curt VARCHAR(30),
	text_anunci VARCHAR(1000),
	data_publicacio DATETIME,
	data_web DATETIME,
	data_no_web DATETIME,
	telefon INT(9),
	imatge VARCHAR(50),
	id_seccio INT,
	nombre_canvis INT DEFAULT 0,
	id_usuari INT,
	PRIMARY KEY(id),
	FOREIGN KEY (id_seccio) REFERENCES Seccio(codi_seccio),
	FOREIGN KEY (id_usuari) REFERENCES Usuari(id)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TRIGGER augmentar_contador_canvis AFTER UPDATE ON Anunci 
FOR EACH ROW SET @nombre_canvis = @nombre_canvis + 1;

INSERT INTO Privilegi (nivellPrivilegi) VALUES ('Administrador'), ('Anunciant'), ('Internauta');
INSERT INTO Seccio (descripcio, preu, foto_generica_seccio) VALUES ('Vivendes', '1', 'casa.png'), ('Cotxes', '0.5', 'cotxo.png'), ('Ordinadors', '0.25', 'alienware.png');
INSERT INTO Usuari (userID, nom, nivellPrivilegi) VALUES ('admin', 'Toni Más', 1);