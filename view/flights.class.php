<?php
namespace view;

class flights extends view {
    
    /*
     *@method indexView() - default content for the index route
     */
    public function indexView($data) {
        include('/public/index.html');
        
        exit; 
        /*
        $this->_output .= $this->buildUL($data['list']);
        $this->_output .= "
        <div id='div-school-data'>
            <div id='div-content'></div>
        </div>
        ";
      */
    }  
}