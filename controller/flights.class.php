<?php
/**
 * Created by PhpStorm.
 * User: nazarlesiv
 * Date: 3/12/15
 * Time: 5:11 PM
 */

namespace controller;


class flights extends controller{

    protected function indexAction() {

        Global $dbc;

        //output data
        $data = array();

        /*
         * select all of the aircraft records.
         */

        $sql = 'SELECT * FROM aircraft_table';

        $aircrafts = $dbc->query($sql);

        while($row = $aircrafts->fetch_assoc()){
            $data['aircrafts'][] = $row;
        }

        /*
         *load the view
         */
        $view = new \view\flights('index', $data);


    }

    protected function addAction() {

    }

    protected function updateAction() {

    }

    protected function deleteAction() {

    }

    protected function getAction() {

    }

}