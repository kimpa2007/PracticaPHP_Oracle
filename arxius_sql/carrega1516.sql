SET echo off
Rem
Rem***************************************************************************
Rem                   Creacio de la Base de Dades per les pràctiques de
Rem                            Bases de Dades, Curs 2015-16
Rem                                     (SQL de ORACLE)
Rem***************************************************************************
Rem
Rem versió 1.0 

PROMPT Construint les taules per la BD de treball...

SET termout on
SET feedback off 
Rem si alguna cosa no va prou bé, podem posara el feedback a on en la línia anterior

REM Es fa un "reSET" de la Base de Dades esborrant i tornant a crear totes les taules
REM el "CASCADE CONSTRAINTS" del DROP TABLE elimina qualsevol restricció de la BD
REM on hi aparagués la Clau Primària de la taula que volem esborrar

REM la definició de claus primàries i foranes es fa de formes diferents. La sintaxi SQL en permet unes quantes 

PROMPT Creem les taules

Rem*************************************
Rem   Definició estructura taula MODEL
Rem*************************************

DROP TABLE model CASCADE CONSTRAINTS;   
CREATE TABLE model
 (codi       number(2) PRIMARY KEY,
  nom        varchar2(20),
  grup       char(1),
  tarifa_dia number(4)
 );

Rem*************************************
Rem   Definicio estructura taula CLIENT
Rem*************************************
DROP TABLE client CASCADE CONSTRAINT;
CREATE TABLE client
 (codi        number(5) CONSTRAINT CP_CLient PRIMARY KEY,
  nom         varchar2(15),
  cognoms     varchar2(30),
  adreca      varchar2(25),
  codi_postal varchar2(10),
  poblacio    varchar2(20),
  dni         varchar2(9),
  email       varchar2(45)
 );


Rem*************************************
Rem   Definició estructura taula VEHICLE
Rem*************************************
DROP TABLE vehicle CASCADE CONSTRAINTS;
CREATE TABLE vehicle
 (codi          number(4) PRIMARY KEY,
  model_codi    number(2), 
  matricula     varchar2(8),
  data_compra   date,
  color         varchar2(10),
  combustible   varchar2(15),
  asseguranca   number(6,2),
  CONSTRAINT CF_vehicle_model_codi FOREIGN KEY (model_codi) REFERENCES model(codi)
 );

Rem***************************************
Rem   Definicio estructura taula DELEGACIO
Rem***************************************
DROP TABLE delegacio CASCADE CONSTRAINT;
CREATE TABLE delegacio
 (codi        number(2) PRIMARY KEY,
  nom         varchar2(20),
  responsable number(2)
 );

Rem*************************************
Rem   Definicio estructura taula VENEDOR
Rem*************************************
DROP TABLE venedor CASCADE CONSTRAINT;
CREATE TABLE venedor
 (codi           number(2),
  nom            varchar2(15),
  cognoms        varchar2(30),
  sou            number(6,2),
  codi_delegacio number(2),
  Km             number,
  data_alta      date,
  parella        VARCHAR2(45),
  CONSTRAINT CP_venedor PRIMARY KEY (codi) 
);

  ALTER TABLE venedor
  ADD CONSTRAINT CF_venedor_delegacio_codi
  FOREIGN KEY (codi_delegacio)
  REFERENCES delegacio(codi);

  ALTER TABLE delegacio
  ADD CONSTRAINT CF_delegacio_responsable
  FOREIGN KEY (responsable)
  REFERENCES venedor(codi);


Rem***************************************
Rem   Definicio estructura taula LLOGUER
Rem***************************************
DROP TABLE lloguer CASCADE CONSTRAINT;  
CREATE TABLE lloguer
 ( codi         number(4) PRIMARY KEY,
   codi_client  number(5),
   codi_vehicle number(4),
   codi_venedor number(2),
   datai        date,
   dataf        date,
   kmi          number(5),
   kmf          number(5),
   retorn       CHAR(1)
 );

  ALTER TABLE lloguer
  ADD CONSTRAINT CF_lloguer_client_codi
  FOREIGN KEY (codi_client)
  REFERENCES client(codi);

  ALTER TABLE lloguer
  ADD CONSTRAINT CF_lloguer_vehicle_codi
  FOREIGN KEY (codi_vehicle)
  REFERENCES vehicle(codi);

  ALTER TABLE lloguer
  ADD CONSTRAINT CF_lloguer_venedor_codi
  FOREIGN KEY (codi_venedor)
  REFERENCES venedor(codi);

Rem***************************************
Rem   Definicio estructura taula ACCESSORI
Rem***************************************

DROP TABLE accessori CASCADE CONSTRAINTS;
CREATE TABLE accessori
 (codi        number(4) PRIMARY KEY,
  descripcio  varchar2(20)
 );
 
Rem***********************************************
Rem   Definicio estructura taula MODEL_ACCESSORI
Rem***********************************************

DROP TABLE model_accessori CASCADE CONSTRAINTS;
CREATE TABLE model_accessori
 (codi_accessori  number(4),
  codi_model      number(2),
  cost            number(5,2)  
 );
 
 ALTER TABLE model_accessori
  ADD CONSTRAINT CP_model_accessori
  PRIMARY KEY (codi_accessori, codi_model);
  
  ALTER TABLE model_accessori
  ADD CONSTRAINT CF_model_accessori_acc
  FOREIGN KEY (codi_accessori)
  REFERENCES accessori (codi);
  
  ALTER TABLE model_accessori
  ADD CONSTRAINT CF_model_accessori_mod
  FOREIGN KEY (codi_model)
  REFERENCES model (codi);
  
Rem************************************************
Rem   Definicio estructura taula ACCESSORIS_LLOGATS
Rem************************************************

DROP TABLE accessoris_llogats CASCADE CONSTRAINTS;
CREATE TABLE accessoris_llogats
( 
  codi_lloguer    number(4), 
  codi_accessori  number(4), 
  CONSTRAINT CP_accessoris_llogats PRIMARY KEY (codi_lloguer, codi_accessori)
);

  ALTER TABLE accessoris_llogats
  ADD CONSTRAINT CF_accessoris_llogats_lloguer
  FOREIGN KEY (codi_lloguer)
  REFERENCES lloguer (codi);

  ALTER TABLE accessoris_llogats
  ADD CONSTRAINT CF_accessoris_llogats_acc
  FOREIGN KEY (codi_accessori)
  REFERENCES accessori (codi);

PROMPT Inserint les dades a les taules
PROMPT model...

Rem*************************************
Rem   Inserció de dades a la taula MODEL
Rem*************************************
INSERT INTO model VALUES (10,'Seat Ibiza','A',65);
INSERT INTO model VALUES (20,'Opel Corsa','A',70);
INSERT INTO model VALUES (30,'Peugeot 207','A',70);
INSERT INTO model VALUES (40,'Opel Astra','B',85);
INSERT INTO model VALUES (50,'Audi A3','B',95);
INSERT INTO model VALUES (60,'Audi A4','C',120);

PROMPT vehicle...
Rem****************************************
Rem   Inserció de dades a la taula VEHICLE
Rem****************************************
INSERT INTO vehicle VALUES
(7369,10,'E2356FBC',to_date('02/17/2009','MM/DD/YYYY'),'Blau','Gasolina',634.2); 
INSERT INTO vehicle VALUES
(7499,10,'E4235FBC',to_date('03/21/2009','MM/DD/YYYY'),'Blanc','Gasolina',634.2); 
INSERT INTO vehicle VALUES
(7521,20,'E1587FCD',to_date('03/22/2009','MM/DD/YYYY'),'Negre','Gasolina',710.3); 
INSERT INTO vehicle VALUES
(7566,20,'E0012FGD',to_date('06/02/2010','MM/DD/YYYY'),'Blau','Gasolina',711); 
INSERT INTO vehicle VALUES
(7654,30,'E0855FGG',to_date('06/19/2010','MM/DD/YYYY'),'Blau','Diesel',715); 
INSERT INTO vehicle VALUES
(7698,30,'E5626FDG',to_date('01/01/2010','MM/DD/YYYY'),'Blanc','Diesel',717); 
INSERT INTO vehicle VALUES
(7782,30,'E0854FGG',to_date('06/19/2010','MM/DD/YYYY'),'Blanc','Elèctric',715); 
INSERT INTO vehicle VALUES
(7788,40,'2445FEG',to_date('03/30/2010','MM/DD/YYYY'),'Blau','Diesel',799.99); 
INSERT INTO vehicle VALUES
(7839,40,'E2458FEF',to_date('02/17/2010','MM/DD/YYYY'),'Negre','Diesel',780); 
INSERT INTO vehicle VALUES
(7844,50,'E1254FHG',to_date('07/01/2010','MM/DD/YYYY'),'Verd','Gasolina',810); 
INSERT INTO vehicle VALUES
(7876,50,'E9959FEG',to_date('05/03/2010','MM/DD/YYYY'),'Blau','Diesel',809); 
INSERT INTO vehicle VALUES
(7900,60,'E6359FDC',to_date('12/03/2006','MM/DD/YYYY'),'Negre','Gasolina',810.34); 

Rem***************************************
Rem   Inserció de dades a la taula CLIENT
Rem***************************************

PROMPT client...

INSERT INTO CLIENT (codi, nom, cognoms, adreca, codi_postal, poblacio, dni, email)
 VALUES (10995, 'Pere','Fontcoberta', 'C/Nou, 10', '17000', 'Girona', '40123123S', 'ppau@gmail.com');
INSERT INTO CLIENT (codi, nom, cognoms, adreca, codi_postal, poblacio, dni, email)
 VALUES (18004, 'Josep', 'Fernández', 'C/Girona, 32', '17001', 'Girona', '40157256F', 'jofer@hotmail.com');
INSERT INTO CLIENT (codi, nom, cognoms, adreca, codi_postal, poblacio, dni, email)
 VALUES (14024, 'Maria', 'Fàbrega', 'C/De la pau, 1', '17800', 'Olot', '38121458T', 'maria.fabrega@fge.es');
INSERT INTO CLIENT (codi, nom, cognoms, adreca, codi_postal, poblacio, dni, email)
 VALUES (36658, 'David','Pérez', 'C/De la pera, 34', '17600', 'Figueres', '42587269G', 'davidp@ddgi.cat');
INSERT INTO CLIENT (codi, nom, cognoms, adreca, codi_postal, poblacio, dni, email)
 VALUES (38702, 'Joan', 'Coll', 'C/Migdia, 2', '17002', 'Girona', '39444221Q', 'joan@coll.cat');
INSERT INTO CLIENT (codi, nom, cognoms, adreca, codi_postal, poblacio, dni, email)
 VALUES (39217, 'Anna', 'Pi', 'Avd Barcelona, 2', '17300', 'Blanes', '41234941D', 'api@yahoo.es');
INSERT INTO CLIENT (codi, nom, cognoms, adreca, codi_postal, poblacio, dni, email)
 VALUES (59076, 'Jaume', 'Albert', 'Passeig Catalunya ', '17300', 'Blanes', '40586221P', 'jaumealbert@yahoo.es');
INSERT INTO CLIENT (codi, nom, cognoms, adreca, codi_postal, poblacio, dni, email)
 VALUES (63827, 'Jordi', 'Esteve', 'Avd Olot, 3', '17600', 'Figueres', '43458222O', 'jesteve@gmail.com');
INSERT INTO CLIENT (codi, nom, cognoms, adreca, codi_postal, poblacio, dni, email)
 VALUES (44231, 'Nuri', 'Mont', 'C/Pujades, 4', '17000', 'Girona', '40439420S', 'numo@hotmail.cat');
INSERT INTO CLIENT (codi, nom, cognoms, adreca, codi_postal, poblacio, dni, email)
 VALUES (47129, 'Albert', 'Roura', 'C/Mayor, 2', '17800', 'Olot', '44518902D', 'aroura@fundacio.cat');
INSERT INTO CLIENT (codi, nom, cognoms, adreca, codi_postal, poblacio, dni, email)
 VALUES (47254, 'Clara', 'Garcia', 'C/Nou, 112', '17600', 'Figueres', '40411512', 'claragarcia@cgirona.cat');


Rem****************************************************************
Rem  Inserció de dades a la taula DELEGACIO
Rem  responsables a null perquè encara no estan creats els venedors
Rem****************************************************************
PROMPT delegacio...

INSERT INTO delegacio VALUES (1,'Girona',NULL);
INSERT INTO delegacio VALUES (2,'Olot',NULL);
INSERT INTO delegacio VALUES (3,'Blanes',NULL);
INSERT INTO delegacio VALUES (4,'Figueres',NULL);

Rem*****************************************
Rem   Inserció de dades a la taula venedors
Rem*****************************************

PROMPT venedor...
INSERT INTO VENEDOR (codi, nom, cognoms, sou, codi_delegacio, km, data_alta, parella)
 VALUES (10, 'Joan','Pérez', 1000, 1, 120, TO_DATE('11/02/2007 10:00:00', 'MM/DD/YYYY HH24:MI:SS'), 'Margarita');
INSERT INTO VENEDOR (codi, nom, cognoms,  sou, codi_delegacio, km, data_alta, parella)
 VALUES (11, 'Anna', 'Sau', 900, 1, 600, TO_DATE('10/01/2012 15:00:00', 'MM/DD/YYYY HH24:MI:SS'), 'Laia');
INSERT INTO VENEDOR (codi, nom, cognoms,  sou, codi_delegacio, km, data_alta, parella)
 VALUES (12, 'Albert','Gou', 950, 2, 425, TO_DATE('03/05/2008 09:00:00', 'MM/DD/YYYY HH24:MI:SS'), 'Joana');
INSERT INTO VENEDOR (codi, nom, cognoms,  sou, codi_delegacio, data_alta, parella)
 VALUES (13, 'Ariadna','Pi', 850, 2, TO_DATE('01/01/2002 11:00:00', 'MM/DD/YYYY HH24:MI:SS'), 'Pere');
INSERT INTO VENEDOR (codi, nom, cognoms,  sou, codi_delegacio, km, data_alta, parella)
 VALUES (14, 'Santi','Lluch', 945, 3, 321, TO_DATE('11/26/1998 09:00:00', 'MM/DD/YYYY HH24:MI:SS'), 'Carla');
INSERT INTO VENEDOR (codi, nom, cognoms,  sou, codi_delegacio, km, data_alta, parella)
 VALUES (15, 'Jacint','Bosch', 955, 3, 900, TO_DATE('10/20/1998 08:00:00', 'MM/DD/YYYY HH24:MI:SS'), 'Clara');
INSERT INTO VENEDOR (codi, nom, cognoms,  sou, codi_delegacio, data_alta, parella)
 VALUES (16, 'Mireia','Costa', 750, 4, TO_DATE('07/15/1998 11:00:00', 'MM/DD/YYYY HH24:MI:SS'), 'Joan Josep');
INSERT INTO VENEDOR (codi, nom, cognoms,  sou, codi_delegacio, km, data_alta, parella)
 VALUES (17, 'Lluisa','Martorell', 760, 1, 600, TO_DATE('09/01/2007 09:00:00', 'MM/DD/YYYY HH24:MI:SS'), 'David');
INSERT INTO VENEDOR (codi, nom, cognoms,  sou, codi_delegacio, data_alta, parella)
 VALUES (1, 'Pau','Quart', 890, 1, TO_DATE('05/01/2001 08:00:00', 'MM/DD/YYYY HH24:MI:SS'), 'Gemma');
INSERT INTO VENEDOR (codi, nom, cognoms,  sou, codi_delegacio, km, data_alta, parella)
 VALUES (2, 'Joaquim','Foix', 600, 1, 10, TO_DATE('10/01/1999 11:00:00', 'MM/DD/YYYY HH24:MI:SS'), 'Marc');
INSERT INTO VENEDOR (codi, nom, cognoms,  sou, codi_delegacio, km, data_alta, parella)
 VALUES (3, 'Lluc','Hugues', 1500, 2, 1200, TO_DATE('05/01/2003 12:00:00', 'MM/DD/YYYY HH24:MI:SS'), 'Laura');
INSERT INTO VENEDOR (codi, nom, cognoms,  sou, codi_delegacio, km, data_alta, parella)
 VALUES (5, 'Ramon','Ximènez', 1100, 3, 500, TO_DATE('12/01/2003 09:00:00', 'MM/DD/YYYY HH24:MI:SS'), 'Anna');

Rem**************************************************************
Rem Actualitzem responsables delegacio un cop creats els venedors
Rem**************************************************************
UPDATE delegacio SET responsable=10 WHERE codi=1;
UPDATE delegacio SET responsable=3 WHERE codi=2;
UPDATE delegacio SET responsable=5 WHERE codi=3;
UPDATE delegacio SET responsable=16 WHERE codi=4;

Rem*****************************************
Rem   Inserció de dades a la taula LLOGUER
Rem*****************************************

PROMPT lloguer...
INSERT INTO LLOGUER (codi, codi_client, codi_vehicle, codi_venedor, datai, dataf, kmi, kmf, retorn)
 VALUES (7001, 10995, 7369, 14, TO_DATE('05/01/2010 08:00:00', 'MM/DD/YYYY HH24:MI:SS'), TO_DATE('05/16/2010 08:00:00', 'MM/DD/YYYY HH24:MI:SS'), 30562, 35763, 'A');
INSERT INTO LLOGUER (codi, codi_client, codi_vehicle, codi_venedor, datai, dataf, kmi, kmf, retorn)
 VALUES (7002, 18004, 7521, 16, TO_DATE('05/02/2010 10:00:00', 'MM/DD/YYYY HH24:MI:SS'), TO_DATE('05/14/2010 11:00:00', 'MM/DD/YYYY HH24:MI:SS'), 21456, 22567, 'B');
INSERT INTO LLOGUER (codi, codi_client, codi_vehicle, codi_venedor, datai, dataf, kmi, kmf, retorn)
 VALUES (7003, 14024, 7782, 10, TO_DATE('05/05/2010 15:00:00', 'MM/DD/YYYY HH24:MI:SS'), TO_DATE('06/01/2010 10:00:00', 'MM/DD/YYYY HH24:MI:SS'), 10321, 18321, 'A');
INSERT INTO LLOGUER (codi, codi_client, codi_vehicle, codi_venedor, datai, dataf, kmi, kmf, retorn)
 VALUES (7004, 39217, 7844, 12, TO_DATE('06/04/2010 09:00:00', 'MM/DD/YYYY HH24:MI:SS'), TO_DATE('06/10/2010 02:00:00', 'MM/DD/YYYY HH24:MI:SS'), 9786, 12675, 'D');
INSERT INTO LLOGUER (codi, codi_client, codi_vehicle, codi_venedor, datai, dataf, kmi, kmf, retorn)
 VALUES (7005, 38702, 7566, 16, TO_DATE('07/01/2010 12:00:00', 'MM/DD/YYYY HH24:MI:SS'), TO_DATE('07/20/2010 10:00:00', 'MM/DD/YYYY HH24:MI:SS'), 10378, 14008, 'A');
INSERT INTO LLOGUER (codi, codi_client, codi_vehicle, codi_venedor, datai, dataf, kmi, kmf, retorn)
 VALUES (7006, 59076, 7654, 13, TO_DATE('07/04/2010 10:00:00', 'MM/DD/YYYY HH24:MI:SS'), TO_DATE('07/23/2010 16:00:00', 'MM/DD/YYYY HH24:MI:SS'), 23789, 33814, 'A');
INSERT INTO LLOGUER (codi, codi_client, codi_vehicle, codi_venedor, datai, dataf, kmi, kmf, retorn)
 VALUES (7007, 14024, 7499, 10, TO_DATE('05/08/2010 08:00:00', 'MM/DD/YYYY HH24:MI:SS'), TO_DATE('05/18/2010 08:00:00', 'MM/DD/YYYY HH24:MI:SS'), 12898, 15815, 'A');
INSERT INTO LLOGUER (codi, codi_client, codi_vehicle, codi_venedor, datai, dataf, kmi, kmf, retorn)
 VALUES (7008, 38702, 7369, 14, TO_DATE('05/25/2010 11:00:00', 'MM/DD/YYYY HH24:MI:SS'), TO_DATE('06/10/2010 23:00:00', 'MM/DD/YYYY HH24:MI:SS'), 35813, 39451, 'C');
INSERT INTO LLOGUER (codi, codi_client, codi_vehicle, codi_venedor, datai, dataf, kmi, kmf, retorn)
 VALUES (7009, 59076, 7788, 13, TO_DATE('05/20/2010 16:00:00', 'MM/DD/YYYY HH24:MI:SS'), TO_DATE('05/29/2010 15:00:00', 'MM/DD/YYYY HH24:MI:SS'), 28567, 29876, 'D');
INSERT INTO LLOGUER (codi, codi_client, codi_vehicle, codi_venedor, datai, dataf, kmi, kmf, retorn)
 VALUES (7010, 39217, 7839, 11, TO_DATE('05/04/2010 08:00:00', 'MM/DD/YYYY HH24:MI:SS'), TO_DATE('05/05/2010 14:00:00', 'MM/DD/YYYY HH24:MI:SS'), 5450, 6200, 'B');
INSERT INTO LLOGUER (codi, codi_client, codi_vehicle, codi_venedor, datai, dataf, kmi, kmf, retorn)
 VALUES (7011, 63827, 7499, 10, TO_DATE('05/24/2010 17:00:00', 'MM/DD/YYYY HH24:MI:SS'), TO_DATE('05/29/2010 11:00:00', 'MM/DD/YYYY HH24:MI:SS'), 29901, 33356, 'A');
INSERT INTO LLOGUER (codi, codi_client, codi_vehicle, codi_venedor, datai, dataf, kmi, kmf, retorn)
 VALUES (7012, 10995, 7788, 13, TO_DATE('05/29/2010 19:00:00', 'MM/DD/YYYY HH24:MI:SS'), TO_DATE('06/05/2010 07:00:00', 'MM/DD/YYYY HH24:MI:SS'), 29877, 33781, 'A');
INSERT INTO LLOGUER (codi, codi_client, codi_vehicle, codi_venedor, datai, dataf, kmi, kmf, retorn)
 VALUES (7013, 36658, 7876, 11, TO_DATE('06/10/2010 08:00:00', 'MM/DD/YYYY HH24:MI:SS'), TO_DATE('07/10/2010 05:00:00', 'MM/DD/YYYY HH24:MI:SS'), 12781, 19651, 'B');
INSERT INTO LLOGUER (codi, codi_client, codi_vehicle, codi_venedor, datai, dataf, kmi, kmf, retorn)
 VALUES (7014, 14024, 7369, 15, TO_DATE('06/24/2010 11:00:00', 'MM/DD/YYYY HH24:MI:SS'), TO_DATE('07/01/2010 20:00:00', 'MM/DD/YYYY HH24:MI:SS'), 39451, 41372, 'B');
INSERT INTO LLOGUER (codi, codi_client, codi_vehicle, codi_venedor, datai, dataf, kmi, kmf, retorn)
 VALUES (7015, 18004, 7521, 16, TO_DATE('06/26/2010 12:00:00', 'MM/DD/YYYY HH24:MI:SS'), TO_DATE('07/22/2010 22:00:00', 'MM/DD/YYYY HH24:MI:SS'), 22767, 24874, 'B');
INSERT INTO LLOGUER (codi, codi_client, codi_vehicle, codi_venedor, datai, dataf, kmi, kmf, retorn)
 VALUES (7016, 39217, 7782, 10, TO_DATE('06/07/2010 08:00:00', 'MM/DD/YYYY HH24:MI:SS'), TO_DATE('06/17/2010 09:00:00', 'MM/DD/YYYY HH24:MI:SS'), 18333, 19438, 'B');
INSERT INTO LLOGUER (codi, codi_client, codi_vehicle, codi_venedor, datai, dataf, kmi, kmf, retorn)
 VALUES (7017, 44231, 7900, 12, TO_DATE('07/02/2010 14:00:00', 'MM/DD/YYYY HH24:MI:SS'), TO_DATE('07/03/2010 11:00:00', 'MM/DD/YYYY HH24:MI:SS'), 12761, 13500, 'A');
INSERT INTO LLOGUER (codi, codi_client, codi_vehicle, codi_venedor, datai, dataf, kmi, kmf, retorn)
 VALUES (7018, 63827, 7369, 15, TO_DATE('07/02/2010 11:00:00', 'MM/DD/YYYY HH24:MI:SS'), TO_DATE('08/01/2010 10:00:00', 'MM/DD/YYYY HH24:MI:SS'), 41390, 45892, 'A');
INSERT INTO LLOGUER (codi, codi_client, codi_vehicle, codi_venedor, datai, dataf, kmi, kmf, retorn)
 VALUES (7019, 10995, 7876, 11, TO_DATE('07/18/2010 10:00:00', 'MM/DD/YYYY HH24:MI:SS'), TO_DATE('07/21/2010 09:00:00', 'MM/DD/YYYY HH24:MI:SS'), 19660, 20998, 'A');

Rem******************************************
Rem   Inserció de dades a la taula ACCESSORI
Rem******************************************

PROMPT accessori...
INSERT INTO ACCESSORI  VALUES ( 1, 'Sostre elèctric'); 
INSERT INTO ACCESSORI  VALUES ( 2, 'Radio MP3'); 
INSERT INTO ACCESSORI  VALUES ( 3, 'Aire condicionat'); 
INSERT INTO ACCESSORI  VALUES ( 4, 'Volant regulable'); 
INSERT INTO ACCESSORI  VALUES ( 5, 'Llums antiboira'); 
INSERT INTO ACCESSORI  VALUES ( 6, 'Climatitzador'); 
INSERT INTO ACCESSORI  VALUES ( 7, 'Tapisseria cuir');

Rem**************************************************
Rem   Inserció de dades a la taula MODEL_ACCESSORI
Rem**************************************************

PROMPT preus accessoris per models...
INSERT INTO   model_accessori VALUES(1, 10, 40);
INSERT INTO   model_accessori VALUES(1, 60, 40);
INSERT INTO   model_accessori VALUES(2, 30, 2);
INSERT INTO   model_accessori VALUES(2, 50, 2);
INSERT INTO   model_accessori VALUES(3, 50, 20);
INSERT INTO   model_accessori VALUES(4, 60, 5);
INSERT INTO   model_accessori VALUES(5, 10, 5);
INSERT INTO   model_accessori VALUES(5, 20, 5);
INSERT INTO   model_accessori VALUES(5, 30, 5);
INSERT INTO   model_accessori VALUES(5, 50, 5);
INSERT INTO   model_accessori VALUES(6, 60, 50);
INSERT INTO   model_accessori VALUES(7, 60, 100);

Rem**************************************************
Rem   Inserció de dades a la taula ACCESSORIS_LLOGATS
Rem**************************************************

PROMPT accessoris llogats...
INSERT INTO accessoris_llogats VALUES(7001, 1);
INSERT INTO accessoris_llogats VALUES(7004, 2);
INSERT INTO accessoris_llogats VALUES(7004, 3);
INSERT INTO accessoris_llogats VALUES(7017, 1);
INSERT INTO accessoris_llogats VALUES(7017, 7);
INSERT INTO accessoris_llogats VALUES(7017, 4);
INSERT INTO accessoris_llogats VALUES(7013, 3);
INSERT INTO accessoris_llogats VALUES(7013, 5);
INSERT INTO accessoris_llogats VALUES(7019, 3);
COMMIT;
 
SET termout on
SET feedback on 
SET echo on

SET linesize 1000;
SET pagesize 2000;
SELECT * FROM model ORDER BY codi;
SELECT * FROM vehicle ORDER BY codi;
SELECT * FROM client ORDER BY codi;
SELECT * FROM delegacio ORDER BY codi;
SELECT * FROM venedor ORDER BY codi;
SELECT * FROM lloguer ORDER BY codi;
SELECT * FROM accessori ORDER BY codi;
SELECT * FROM model_accessori ORDER BY codi_accessori, codi_model;
SELECT * FROM accessoris_llogats ORDER BY codi_lloguer, codi_accessori;

COLUMN TABLE_name FORMAT A50;
COLUMN CONSTRAINT_name FORMAT A50;
SELECT TABLE_NAME, CONSTRAINT_NAME 
 FROM USER_CONSTRAINTS 
 WHERE TABLE_NAME IN ('MODEL', 'VEHICLE', 'CLIENT','DELEGACIO','VENEDOR','LLOGUER','ACCESSORI','MODEL_ACCESSORI','ACCESSORIS_LLOGATS')
 ORDER BY TABLE_NAME;
 
PROMPT Proces finalitzat.



