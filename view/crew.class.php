<?php
/**
 * Created by PhpStorm.
 * User: nazarlesiv
 * Date: 3/16/15
 * Time: 5:46 PM
 */

namespace view;


class crew extends view {
    /*
    *@method indexView() - default content for the index route
    */
    public function indexView($data) {
        $partial = 'crew';
        include('./public/index.html');

        exit;

    }

}