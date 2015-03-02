<?php
namespace controller;

class about extends controller{
    
    protected function indexAction () {
        
        /*
         *load the view
         */
        $view = new \view\about('index', null); 

    }

}