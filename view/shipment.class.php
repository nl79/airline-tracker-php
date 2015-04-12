<?php
/**
 * Created by PhpStorm.
 * User: nazarlesiv
 * Date: 3/16/15
 * Time: 5:46 PM
 */

namespace view;


class shipment extends view {
    /*
    *@method indexView() - default content for the index route
    */
    public function indexView($data) {
        $partial = 'shipment';
        include('./public/index.html');

        exit;

    }

}