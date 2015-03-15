<?php
/**
 * Created by PhpStorm.
 * User: nazarlesiv
 * Date: 3/15/15
 * Time: 10:39 AM
 */

namespace view;


class aircraft extends view {

    /*
    *@method indexView() - default content for the index route
    */
    public function indexView($data) {
        $partial = 'aircrafts';
        include('./public/index.html');

        exit;

    }

}