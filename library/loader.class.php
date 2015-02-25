<?php
namespace library;

class loader {
    
    public static function load($class) {
        $class = trim($class, '\\');
        
        #split
        $parts = explode('\\', $class);
        
        #build the path
        $filepath = '.' . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $parts);
        
        #add the extension
        $filepath .= '.class.php';
            
        #check if the file exists.
        if(file_exists($filepath)) {
             
            #include the file 
            include_once($filepath);
            
            #return true; 
            return true; 
        }
       
        #return false. 
        return false;    
    }
}