<?php
namespace view;

class index extends view {
    
    /*
     *@method indexView() - default content for the index route
     */
    public function indexView($data) {
             
        $this->_output .= $this->buildUL($data['list']);
        $this->_output .= "
        <div id='div-school-data'>
            <div id='div-content'></div>
        </div>
        ";
      
    }  
}