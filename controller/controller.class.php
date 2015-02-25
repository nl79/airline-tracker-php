<?php
namespace controller;

abstract class controller {
    
    #save the action value
    protected $_action = ""; 
    
    public function __construct() {
        
        #get the action parameter.
        $action = isset($_REQUEST['ac']) && !empty($_REQUEST['ac']) ? $_REQUEST['ac'] : 'index';
        
        #call the appropriate action handler if it exists
        if(method_exists($this, $method = $action.'action')) {
            
            #store the action variable. 
            $this->_action = $action;
            
            #call the method. 
            $this->$method();
            
        } else {
            #set the action to default
            $this->_action = 'index';
            
            #else call the default method. 
            $this->defaultAction(); 
        }
    }
}
