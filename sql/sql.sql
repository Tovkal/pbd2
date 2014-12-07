CREATE DATABASE IF NOT EXISTS BDII_08 CHARACTER SET utf8 COLLATE utf8_general_ci;

USE BDII_08;

CREATE TABLE IF NOT EXISTS Seccio (
	codi_seccio INT AUTO_INCREMENT,
	titol_curt VARCHAR(15) NOT NULL,
	descripcio VARCHAR(1000),
	preu DECIMAL(5, 2) NOT NULL,
	foto_generica_seccio VARCHAR(100) NOT NULL,
	activa BIT(1) DEFAULT 1,
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
	actiu BIT(1) DEFAULT 1,
	PRIMARY KEY(id),
	FOREIGN KEY (codi_seccio) REFERENCES Seccio(codi_seccio),
	FOREIGN KEY (id_usuari) REFERENCES Usuari(id)
) CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TRIGGER increment_nombre_canvis_set_hora_data_no_web 
BEFORE UPDATE ON Anunci FOR EACH ROW 
	SET NEW.nombre_canvis = OLD.nombre_canvis + 1, NEW.data_no_web = CONCAT_WS(' ', DATE(OLD.data_no_web), '23:59:59');

CREATE TRIGGER set_hora_data_no_web_insert
BEFORE INSERT ON Anunci FOR EACH ROW
	SET NEW.data_no_web = CONCAT_WS(' ', DATE(NEW.data_no_web), '23:59:59');

INSERT INTO Privilegi (descripcio) VALUES ('Administrador'), ('Anunciant');
INSERT INTO Seccio (titol_curt, preu, foto_generica_seccio) VALUES ('Vivendes', '1', 'casa.png'), ('Cotxes', '0.5', 'cotxo.png'), ('Ordinadors', '0.25', 'alienware.png');
INSERT INTO Usuari (userID, password, nom, id_privilegi) VALUES ('admin', '1234', 'Jaume Más', 1);
INSERT INTO Anunci (id_usuari, titol_curt, telefon, data_web, data_no_web, codi_seccio) VALUES ('1', 'asdasdsadsadas', '123123123', '2014-12-06', '2014-12-06', '1');