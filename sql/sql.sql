CREATE DATABASE IF NOT EXISTS BDII_08 CHARACTER SET utf8 COLLATE utf8_general_ci;

USE BDII_08;

CREATE TABLE IF NOT EXISTS Seccio (
	codi_seccio INT AUTO_INCREMENT,
	titol_curt VARCHAR(30) NOT NULL,
	descripcio VARCHAR(1000),
	preu DECIMAL(5, 2) NOT NULL,
	foto_generica_seccio VARCHAR(100) NOT NULL,
	PRIMARY KEY(codi_seccio)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS Privilegi (
	id INT AUTO_INCREMENT,
	descripcio VARCHAR(20) NOT NULL,
	PRIMARY KEY (id)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS Usuari (
	id INT AUTO_INCREMENT,
	userID VARCHAR(100) NOT NULL UNIQUE,
	password VARCHAR(100) NOT NULL,
	nom VARCHAR(30) NOT NULL,
	id_privilegi INT NOT NULL DEFAULT 2,
	PRIMARY KEY (id),
	FOREIGN KEY (id_privilegi) REFERENCES Privilegi(id)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS Anunci (
	id INT AUTO_INCREMENT,
	titol_curt VARCHAR(30) NOT NULL,
	text_anunci VARCHAR(150),
	data_publicacio TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	data_web DATETIME NOT NULL,
	data_no_web DATETIME NOT NULL,
	telefon INT(9) NOT NULL,
	foto VARCHAR(50),
	codi_seccio INT NOT NULL,
	nombre_canvis INT DEFAULT 0,
	id_usuari INT NOT NULL,
	PRIMARY KEY(id),
	FOREIGN KEY (codi_seccio) REFERENCES Seccio(codi_seccio),
	FOREIGN KEY (id_usuari) REFERENCES Usuari(id)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TRIGGER augmentar_contador_canvis AFTER UPDATE ON Anunci 
FOR EACH ROW SET @nombre_canvis = @nombre_canvis + 1;

INSERT INTO Privilegi (descripcio) VALUES ('Administrador'), ('Anunciant');
INSERT INTO Seccio (titol_curt, preu, foto_generica_seccio) VALUES ('Vivendes', '1', 'casa.png'), ('Cotxes', '0.5', 'cotxo.png'), ('Ordinadors', '0.25', 'alienware.png');
INSERT INTO Usuari (userID, password, nom, id_privilegi) VALUES ('admin', '1234', 'Jaume Más', 1);