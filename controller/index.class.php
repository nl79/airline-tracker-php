<?php
namespace controller;

class index extends controller{
    
    protected function indexAction () {
        
        //load the flight data.
        $dbc = isset($GLOBALS['dbc']) ? $GLOBALS['dbc'] : null; 
        
        //get the inbould flights.
        $sql = 'SELECT t1.*, t2.tail_number, t2.ac_type, t2.fuel, t3.`name`, t3.city, t3.country, t3.faa_code 
                FROM flight_table AS t1, aircraft_table AS t2, airport_table AS t3
                WHERE t1.destination_id = 3396 AND t1.aircraft_id = t2.entity_id AND t1.origin_id = t3.entity_id';
        
        $res1 = $dbc->query($sql);
        print_r($res1->fetch_assoc()); 
        
        //get the outbound flights.
        $sql = 'SELECT t1.*, t2.tail_number, t2.ac_type, t2.fuel, t3.`name`, t3.city, t3.country, t3.faa_code 
                FROM flight_table AS t1, aircraft_table AS t2, airport_table AS t3
                WHERE t1.origin_id = 3396 AND t1.aircraft_id = t2.entity_id AND t1.origin_id = t3.entity_id';
                
        $res2 = $dbc->query($sql);
        print_r($res2->fetch_assoc());
        
        //cargo.
        $sql = 'SELECT * '; 
        
        /*
         *load the view
         */
        $view = new \view\index('index', null); 

    }
    
    protected function infoAction() {
        echo('index-info');
        
        
        //$view = new \view\index($this->_action, $vData);
    }
    
    protected function importAction() {
        /*
         *import some db data.
         */
        
        $filepath = './misc/airports.csv';
        
        if(is_string($filepath) && file_exists($filepath) && is_file($filepath)) {
            
            #open the csv file
	    $file = fopen($filepath, 'r');
            
            /*
             *extract and parse each line
             *set up a count variable to build a sql query and insert
             *in batch mode
             */
            
            $batchCount = 100;
            $count = 0;
            
            $sql = 'INSERT INTO airport_table(`name`, city, country, faa_code, icoa_code,
                    latitute, longitude, altitude, timezone, dst, tx_db) VALUES ';
                
            $values = "";
            
	    while($row = fgetcsv($file)) {
    
                array_shift($row);
            
                $set = '(';
                
                foreach($row as $field) {
                    $set .= "'" . $dbc->escape_string($field) . "',"; 
                }
                
                $set = rtrim($set, ','); 
                
                $set .= '),';
                
                $values .= $set;
                
                $count += 1;
                
                if($count == $batchCount) {
    
                    /*
                     * build and execute the query and reset the counter and the values
                     * variables.
                     */
                     
                    
                    $q = $sql . rtrim($values, ',');
                    $dbc->query($q); 
                    
                    $count = 0;
                    $values = '';
                }
                
            }
            
        }
    }

}