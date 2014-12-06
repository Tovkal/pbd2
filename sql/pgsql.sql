CREATE DATABASE bdii_08 ENCODING 'UTF8';

\c bdii_08;

CREATE TABLE IF NOT EXISTS Seccio (
	codi_seccio SERIAL,
	descripcio VARCHAR(1000),
	preu NUMERIC(9, 2),
	foto_generica_seccio VARCHAR(100),
	PRIMARY KEY(codi_seccio)
);

CREATE TABLE IF NOT EXISTS Privilegi (
	id SERIAL,
	descripcio VARCHAR(20) NOT NULL,
	PRIMARY KEY (id)
);

CREATE TABLE IF NOT EXISTS Usuari (
	id SERIAL,
	userID VARCHAR(100) NOT NULL UNIQUE,
	nom VARCHAR(30) NOT NULL,
	id_privilegi INT NOT NULL,
	PRIMARY KEY (id),
	FOREIGN KEY (id_privilegi) REFERENCES Privilegi(id)
);

CREATE TABLE IF NOT EXISTS Anunci (
	id SERIAL,
	titol_curt VARCHAR(30),
	text_anunci VARCHAR(1000),
	data_publicacio TIMESTAMP,
	data_web TIMESTAMP,
	data_no_web TIMESTAMP,
	telefon INT,
	imatge VARCHAR(50),
	id_seccio INT,
	nombre_canvis INT DEFAULT 0,
	id_usuari INT,
	PRIMARY KEY(id),
	FOREIGN KEY (id_seccio) REFERENCES Seccio(codi_seccio),
	FOREIGN KEY (id_usuari) REFERENCES Usuari(id)
);

CREATE TRIGGER augmentar_contador_canvis AFTER UPDATE ON Anunci 
FOR EACH ROW SET @nombre_canvis = @nombre_canvis + 1;

INSERT INTO Privilegi (descripcio) VALUES ('Administrador'), ('Anunciant');
INSERT INTO Seccio (descripcio, preu, foto_generica_seccio) VALUES ('Vivendes', '1', 'casa.png'), ('Cotxes', '0.5', 'cotxo.png'), ('Ordinadors', '0.25', 'alienware.png');
INSERT INTO Usuari (userID, nom, id_privilegi) VALUES ('admin', 'Toni Más', 1);