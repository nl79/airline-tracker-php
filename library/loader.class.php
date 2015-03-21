<?php
namespace library;

class loader {
    
    public static function load($class) {
        //learstatcache(); 
        $class = strtolower($class); 
        $class = trim($class, '\\');
        
        #split
        $parts = explode('\\', $class);
        
        #build the path
        //$filepath = './~nl79/it490' . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $parts);
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