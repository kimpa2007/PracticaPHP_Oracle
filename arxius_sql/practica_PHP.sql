--  transformar el codi del vehicle en auto_increment gràcies a un trigger

CREATE SEQUENCE vehicle_id
 	START WITH 8000
  	INCREMENT BY 1
  	CACHE 100;

CREATE OR REPLACE TRIGGER vehicle_auto_increment 
	BEFORE INSERT ON vehicle 
	FOR EACH ROW

	BEGIN
	  SELECT vehicle_id.NEXTVAL
	  INTO   :new.codi
	  FROM   dual;
	END;
/

-- Per poder afegir model cal poder posar un codi més llarg, amb un number(2) es quedar-se molt curt. Tot i que number
-- (4)no es la millor solució.

ALTER TABLE model MODIFY (codi number(4) not null);
ALTER TABLE vehicle  MODIFY (model_codi number(4) not null);


--  transformar el codi del model en auto_increment gràcies a un trigger

CREATE SEQUENCE model_id
 	START WITH 80
  	INCREMENT BY 1
  	CACHE 100;

CREATE OR REPLACE TRIGGER model_auto_increment 
	BEFORE INSERT ON model 
	FOR EACH ROW

	BEGIN
	  SELECT model_id.NEXTVAL
	  INTO   :new.codi
	  FROM   dual;
	END;
/


--  transformar el codi del lloguer en auto_increment gràcies a un trigger

CREATE SEQUENCE lloguer_id
 	START WITH 8000
  	INCREMENT BY 1
  	CACHE 100;

CREATE OR REPLACE TRIGGER lloguer_auto_increment 
	BEFORE INSERT ON lloguer 
	FOR EACH ROW

	BEGIN
	  SELECT lloguer_id.NEXTVAL
	  INTO   :new.codi
	  FROM   dual;
	END;
/

--  creació de la taula per guardar les differents revisions dels vehicles.
CREATE TABLE revisio(
	codi number(4) primary key,
	codi_vehicle number(4),
	data_l date,
	kms number(7),
	codi_venedor number(2),
	CONSTRAINT fk_revisio_a_vehicle FOREIGN KEY (codi_vehicle) REFERENCES vehicle (codi),
	CONSTRAINT fk_revisio_a_venedor FOREIGN KEY (codi_venedor) REFERENCES venedor (codi)
);


--  transformar el codi del lloguer en auto_increment gràcies a un trigger
CREATE SEQUENCE revisio_id
 	START WITH 1
  	INCREMENT BY 1
  	CACHE 100;

CREATE OR REPLACE TRIGGER revisio_auto_increment 
	BEFORE INSERT ON revisio 
	FOR EACH ROW

	BEGIN
	  SELECT revisio_id.NEXTVAL
	  INTO   :new.codi
	  FROM   dual;
	END;
/

