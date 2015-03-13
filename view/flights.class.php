<?php
namespace view;

class flights extends view {
    
    /*
     *@method indexView() - default content for the index route
     */
    public function indexView($data) {
        $partial = 'flights';
        include('./public/index.html');
        
        exit; 

    }  
}