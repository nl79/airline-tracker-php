<?php
namespace library;

class Router {
    private $_pathUri; 
    private $_nodes = array();
    private $_q = '';
    private $_current = 0; 
    
    public function __construct() {
        
        $this->parse();

    }
    
    public function getNode() {
        
        if(count($this->_nodes) > 0) {
            $this->_current++; 
            return $this->_nodes[$this->_current - 1]; 
        } else {
            return 'index'; 
        }
    }
    
    public function hasNext() {
        
        return (count($this->_nodes) > $this->_current);
    
    }
    
    /*
     *@method parse - parse the raw request uri string into array elements.
     *@return nothing
     */
    /*
    private function parse() {
        
        $r_uri = $_SERVER['REQUEST_URI'];
        $q_str = $_SERVER['QUERY_STRING'];
        
        
        //if the query string is in the URI
        if(strpos($r_uri, "?") > 0) {
            //extract the URI with out the query_string
            $this->_pathUri = substr($r_uri,0,strpos($r_uri, "?"));
        } else {
            $this->_pathUri = $r_uri; 
        }
        
        //split the request_uri
        $request_uri_parts = explode('/', $this->_pathUri);
    
        //array that will hold the action variables that are parsed from the url
        //loop through the array elements and assign them to the actions array 
        foreach($request_uri_parts as $item) {
            if(!empty($item)) { $this->_nodes[] = $item; } 
        }
    }
    */
    private function parse() {
        /*
         * extract the pg and ac variables from the request array.
         */

        $pg = isset($_REQUEST['pg']) && !empty($_REQUEST['pg']) ? $_REQUEST['pg'] : 'index';
        $ac = isset($_REQUEST['ac']) && !empty($_REQUEST['ac']) ? $_REQUEST['ac'] : 'index';

        $this->_nodes[] = $pg;
        $this->_nodes[] = $ac;
    }
    
    
    
}