<?php
namespace controller;

class index extends controller{
    
    protected function indexAction () {
        echo("index-index"); 

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