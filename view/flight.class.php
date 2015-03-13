<?php
namespace view;

class flight extends view {
    
    /*
     *@method indexView() - default content for the index route
     */
    public function indexView($data) {
        $partial = 'flights';
        include('./public/index.html');
        
        exit; 

    }  
}