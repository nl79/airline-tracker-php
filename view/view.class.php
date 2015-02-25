<?php
namespace view;

abstract class view {
    
    #action value
    protected $_action = '';
    
    #output string
    protected $_output = "";
    
    #stylessheets
    protected $_stylesheets = "";
    
    #javascript files
    protected $_scripts = ""; 
    
    public function __construct($action, $data) {
        
        #validate the action variable is a string
        if(is_string($action) &&
           method_exists($this, $method = $action.'view')) {
            
            $this->_action = $action; 
            $this->$method($data);
            
        } else {
            
            $this->_action = 'error'; 
            $this->errorView('404'); 
            
        }
        
        
    }
    
    protected function errorView($code) {
        
    }
    
    public function __destruct() {
        $html = $this->_output;

        #if its an ajax call. do not load the index file and just echo out the htmls
        if(isset($_REQUEST['ajax']) && $_REQUEST['ajax'] == 'ajax') {
            echo($html);
            exit; 
        }
        
        #include the template file. 
        include('./public/index.phtml');
        
    }
    
    private function menu() {
     
    }
    
    
}
