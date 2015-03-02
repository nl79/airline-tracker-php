<?php
namespace view;

abstract class view {
    
    #action value
    protected $_action = '';
    
    #output string
    protected $_output = "";
   
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
        
    }
    
    private function menu() {
     
    }
    
    
}
