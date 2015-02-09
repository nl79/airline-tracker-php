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
    identifier		char(3)		not null, 					-- Identifier EX: (NWK - Newark Airport)
    `name`			char(50)	not null, 
    
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


