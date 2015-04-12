<?php
namespace controller;

abstract class controller {
    
    #save the action value
    protected $_action = ""; 
    
    public function __construct($router) {
        
        #get the action parameter.
        //$action = isset($_REQUEST['ac']) && !empty($_REQUEST['ac']) ? $_REQUEST['ac'] : 'index';
        
        $action = $router->hasNext() ? $router->getNode() : 'index'; 

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

    protected function getDBC2() {
        // external database connection
        $hostname	=	"sql.njit.edu";
        $username	=	"rp373";
        $password	=	"JoWkzAkw";
        $project	=	"rp373";

        // Make the connection:
        $dbc = @mysqli_connect ($hostname, $username, $password, $project);

        // If no connection could be made, trigger an error:
        if (!$dbc) {
            trigger_error ('Could not connect to MySQL: ' . mysqli_connect_error() );
        } else { // Otherwise, set the encoding:
            mysqli_set_charset($dbc, 'utf8');

            //return the dbc object.
            return $dbc;
        }
    }
}
