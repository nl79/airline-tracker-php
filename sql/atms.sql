drop database if exists nl79; 
create database nl79; 

use nl79; 

DROP TABLE IF EXISTS aircraft_table; 
CREATE TABLE aircraft_table(
    entity_id		int		not null	auto_increment, 
    tail_number		int(5)	not null, 
    ac_type			char(4)	not null, 							-- aircrapt type
    fuel			int		not null, 
    
    PRIMARY KEY(entity_id)
); 

DROP TABLE IF EXISTS airport_table; 
CREATE TABLE airport_table(
    entity_id		int			not null	auto_increment, 
    `name`			char(50)	not null, 
    city			char(150)	not null, 
    country			char(150)	not null, 
    faa_code		char(4)		null, 
    icoa_code		char(5)		null, 
    latitute		float(8,6)	null, 
    longitude		float(8,6)	null,
    altitude		float(10,2) null, 
    timezone		float(4,2)	null, 
    dst				char(2)		null, 
    tx_db			char(50)	null,
    
    PRIMARY KEY(entity_id)
); 


DROP TABLE IF EXISTS flight_table; 
CREATE TABLE flight_table(
	entity_id		int			not null	auto_increment, 
    origin_id		int			not null, 
    destination_id	int			not null, 
    aircraft_id		int			not null, 
    departure_time	datetime	null		default null, 
    arrivate_time	datetime	null		default null,
    
	PRIMARY KEY(entity_id), 
    FOREIGN KEY(origin_id) REFERENCES airport_table(entity_id), 
    FOREIGN KEY(destination_id) REFERENCES airport_table(entity_id),
    FOREIGN KEY(aircraft_id) REFERENCES aircraft_table(entity_id)
); 

DROP TABLE IF EXISTS crew_type_table; 
CREATE TABLE crew_type_table(
	entity_id			int		not null	auto_increment, 
    `type`				char(15)	not null, 
    description			char(100)	not null,
    
    PRIMARY KEY(entity_id)
); 

DROP TABLE IF EXISTS crew_table; 
CREATE TABLE crew_table(
	entity_id		int			not null	auto_increment, 
    type_id			int			not null,
    aircraft_id		int			not null,
    first_name		char(50)	not null, 
    last_name		char(50)	not null, 
    
    
    PRIMARY KEY(entity_id), 
    FOREIGN KEY(type_id) REFERENCES crew_type_table(entity_id),
    FOREIGN KEY(aircraft_id) REFERENCES aircraft_table(entity_id)
); 

DROP TABLE IF EXISTS cargo_table; 
CREATE TABLE cargo_table(
	entity_id		int			not null	auto_increment, 
    aircraft_id		int			not null, 
    skid_id			int			not null, 
    weight			float(9,2)	not null	default 0, 
    contents		char(250)	not null, 
    mission			char(25)	null, 
    location		char(50)	null, 
    
    PRIMARY KEY(entity_id), 
    FOREIGN KEY(aircraft_id) REFERENCES aircraft_table(entity_id)
); 




-- -----------INTEGRATION SECTION ---------------------------------

-- order-flight table to assocciate order data from another system with the current flight records. 
DROP TABLE IF EXISTS shipment_table; 
CREATE TABLE shipment_table(
	entity_id	int		not null auto_increment, 
	flight_id	 int	not null, 
    order_id	int(5)	not null, 
    
    PRIMARY KEY(entity_id), 
    FOREIGN KEY(flight_id) REFERENCES flight_table(entity_id)

); 


-- ----------------- END INTEGRATION SECTION --------------------------

-- Test data--
-- aircraft table
INSERT INTO aircraft_table(tail_number, ac_type,fuel)
VALUES (11111, 'b747', 5000), 
(11112, 'b777', 6500), 
(11114, 'b767', 3400), 
(11115, 'b737', 2500), 
(11116, 'b737', 2000); 

-- fight data. 
INSERT INTO flight_table(origin_id, destination_id ,aircraft_id,departure_time, arrivate_time) 
VALUES(3396, 3350, 1, now(), null), 
(3396, 261, 2, now(), null), 
(2576, 3396, 3, '2008-11-3', NOW()); 

-- cargo table
INSERT INTO cargo_table(aircraft_id, skid_id, weight, contents, mission, location)
VALUES(1, 1111111, 1234, 'apples', 'apple delivery', 'cargo bay 1'), 
(1, 1111112, 1235, 'peaches', 'peach delivery', 'cargo bay 2'), 
(2, 1111113, 1236, 'gold', 'bail out', 'cargo bay 3'), 
(2, 1111114, 1237, 'cars', 'bmw import', 'cargo bay 1'), 
(3, 1111115, 1238, 'bubble gum', 'export delivery', 'cargo bay 2'); 


-- Crew Type
INSERT INTO crew_type_table (`type`, description)
VALUES ('captain', 'primary pilot'), 
('first officer', 'primary co-pilot'), 
('second officer', 'secondary co-pilot'), 
('flight engineer', 'responsible for technical duties aboard the plate'), 
('navigator', 'responsible for nagivation'), 
('attendant', 'flight crew attendant'), 
('air gunner', 'primary weaponry operations'), 
('Bombardier', 'primary bombing operator'), 
('medic', 'primary flight medic'); 

-- crew members. 
INSERT INTO crew_table(type_id, aircraft_id, first_name , last_name)
VALUES (1, 1, 'John', 'Smith'), 
(2, 1, 'Wanda', 'Willis'), 
(3, 1, 'Bob', 'Bobert'),
(6, 1, 'Steve', 'Jobless'), 
(6, 1, 'Bill', 'Doors'),
(6, 1, 'Caron', 'Lazy'), 
(1, 2, 'Steve', 'Smith'), 
(2, 2, 'Amanda', 'Willis'), 
(3, 2, 'Rob', 'Ruler'),
(6, 2, 'Bob', 'Dart'), 
(6, 2, 'Roney', 'Windows'),
(6, 2, 'Steve', 'Laziness'), 
(1, 3, 'StSmitheve', 'Smith'), 
(2, 3, 'AmaWillisnda', 'Willis'), 
(3, 3, 'Roasdfb', 'RulSmither'),
(6, 3, 'BoWillisb', 'Dart'), 
(6, 3, 'Ronssey', 'WinStevedows'),
(6, 3, 'Stefsve', 'LaziSteveness');

