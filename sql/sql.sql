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
	SET NEW.nombre_canvis = OLD.nombre_canvis + 1;

INSERT INTO Privilegi (descripcio) VALUES ('Administrador'), ('Anunciant');
INSERT INTO Seccio (titol_curt, descripcio, preu, foto_generica_seccio) VALUES ('Vivendes', "Tot tipus d'habitatges, però per favor que siguin legals per evitar problemes.", '10', 'casa.png'), ('Cotxes', 'Cotxes nous o vells a la venda.', '25', 'cotxo.png'), ('Ordinadors', 'Ordenadors o components de primera o segona mà.', '30', 'alienware.png');
INSERT INTO Usuari (userID, password, nom, id_privilegi) VALUES ('admin', '1234', 'Jaume Mas', 1);
INSERT INTO Usuari (userID, password, nom, id_privilegi) VALUES ('anunciant', 'vulldoblers', 'Tofol Gelabert', 2);
INSERT INTO Usuari (userID, password, nom, id_privilegi) VALUES ('barato', 'moltBarato', 'Mado Pereta', 2);
INSERT INTO Anunci (id_usuari, titol_curt, text_anunci, telefon, data_web, data_no_web, codi_seccio, foto, nombre_canvis) VALUES ('2', 'Opel Corsa', 'Vendo Opel Corsa, siempre en garaje. Full equipe. 5000€, no negociables.', '666666666', '2014-12-15', '2015-02-01', '2', 'opel_corsa.jpg', 10);
INSERT INTO Anunci (id_usuari, titol_curt, text_anunci, telefon, data_web, data_no_web, codi_seccio, nombre_canvis) VALUES ('2', 'Xalet amoblat', 'Bé, realment no te molt de mobles. Esta pendent de obtenir els permissos de construcció. 400 metres quadrats, terreno de 2000. 100.000€, només en efectiu per favor.', '666666666', '2014-12-15', '2015-05-01', '1', 5);
INSERT INTO Anunci (id_usuari, titol_curt, text_anunci, foto,telefon, data_web, data_no_web, codi_seccio, nombre_canvis) VALUES ('3', 'Dell Inspiron', 'Portatil 17 polsades com a nou.', 'dell_inspi.jpg', '666666666', '2014-12-15', '2015-02-01', '3', 7);
INSERT INTO Anunci (id_usuari, titol_curt, text_anunci, telefon, data_web, data_no_web, codi_seccio, nombre_canvis, foto) VALUES ('2', 'Ferrari La Ferrari', 'Esta nou, regalat per un cosí que tinc pes Marroc.', '666666666', '2014-12-15', '2015-05-01', '2', 1, 'ferrari_la_ferrari.jpg');
INSERT INTO Anunci (id_usuari, titol_curt, text_anunci, telefon, data_web, data_no_web, codi_seccio, foto, nombre_canvis) VALUES ('1', 'BMW X1 de 2013', "El propietari anterior, Don Gabriel Fontenet, m'ha encarregat vendre aquest vehicle per compra d'un Jaguar. Esta ben nou.", '666666666', '2014-12-15', '2015-02-01', '2', 'bmw_x1.jpg', 5);
INSERT INTO Anunci (id_usuari, titol_curt, text_anunci, telefon, data_web, data_no_web, codi_seccio, nombre_canvis, foto) VALUES ('2', 'Mitsubishi Evo XII', 'Venc perque es va rompre el motor i no tinc doblers per pagar. La resta va perfecte. Cridau per venir a provar-lo.', '666666666', '2014-12-15', '2015-03-01', '2', 6, 'Mitsubishi_Lancer_EVO_X.jpg');
INSERT INTO Anunci (id_usuari, titol_curt, text_anunci, telefon, data_web, data_no_web, codi_seccio, nombre_canvis, foto) VALUES ('3', 'Seat 600', 'Vendo coche familiar, en perfecto estado de consevación. Contactar conmigo para verlo. Está en Artà.', '666666666', '2014-12-15', '2015-02-01', '2', 7, 'Seat_600_red_hl_TCE.jpg');
INSERT INTO Anunci (id_usuari, titol_curt, text_anunci, telefon, data_web, data_no_web, codi_seccio, nombre_canvis, foto) VALUES ('2', 'Palau de Marivent', "En venda perque el poble fa pressió per recuperar els doblers. Criuda per venir a veure'l", '666666666', '2014-12-15', '2015-02-01', '1', 8, 'marivent.jpg');
