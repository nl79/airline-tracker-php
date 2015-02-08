use nl79; 

DROP TABLE IF EXISTS aircraft_table; 
CREATE TABLE aircraft_table(
	entity_id		int		not null	auto_increment, 
    tail_number		int(5)	not null	, 
    ac_type			char(4)	not null, 
    fuel			int		not null, 
    
    PRIMARY KEY(entity_id)
); 

DROP TABLE IF EXISTS airport_table; 
CREATE TABLE airport_table(
	entity_id		int		not null	auto_increment, 
    identifier		char(3)	not null, 					-- Identifier EX: (NWK - Newark Airport)
    
    PRIMARY KEY(entity_id)
); 

DROP TABLE IF EXISTS crew_type_table; 
CREATE TABLE crew_type_table(
	entity_id			int		not null	auto_increment, 
    type_name		char(15)	not null, 
    
    PRIMARY KEY(entity_id)
); 

DROP TABLE IF EXISTS crew_table; 
CREATE TABLE crew_table(
	entity_id		int		not null	auto_increment, 
    `type`			int		not null,
    first_name		char(50)	not null, 
    last_name		char(50)	not null, 
    
    
    PRIMARY KEY(entity_id), 
    FOREIGN KEY(`type`) REFERENCES crew_type_table(entity_id)
); 


