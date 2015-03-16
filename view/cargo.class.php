<?php
namespace view;

class cargo extends view {

    /*
     *@method indexView() - default content for the index route
     */
    public function indexView($data) {
        $partial = 'cargo';

        include('./public/index.html');
        exit;

    }
}