<?php
/**
 * Created by PhpStorm.
 * User: nazarlesiv
 * Date: 3/12/15
 * Time: 5:11 PM
 */

namespace controller;


class flight extends controller{

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
         * select all of the most recent flights.
         */
        $sql = 'SELECT t1.*, t2.ac_type as "aircraft type" , t2.tail_number as "tail number"
                FROM flight_table AS t1, aircraft_table AS t2
                WHERE t1.aircraft_id = t2.entity_id';

        $flights = $dbc->query($sql);

        while($row = $flights->fetch_assoc()){
            $data['flights'][] = $row;
        }

        /*
         *load the view
         */
        $view = new \view\flight('index', $data);


    }

    protected function saveAction() {
        /*
         * save method will insert a new record
         * if entity_id parameter is not supplied.
         * If the entity_id parameter is supplied, the
         * data will be updated instead.
         */

        print_r($_REQUEST);

        #valid flag.
        $valid = true;

        #require fields
        $required = array('aircraft-id',
            'origin-id',
            'destination-id',
            'departure-hour',
            'departure-minute',
            'arrival-hour',
            'arrival-minute',
            'departure-date',
            'arrival-date');

        $data = array();

        foreach($required as $field) {
            if(!isset($_REQUEST[$field]) || empty($_REQUEST[$field])) {
                $valid = false;
            } else {
                $data[$field] = $_REQUEST[$field];
            }
        }

        if($valid == true) {

            //extract the airport id values from the supplied strings by spliting on the ':' character.
            $data['destination-id'] = substr($_REQUEST['destination-id'], 0, strpos($_REQUEST['destination-id'],':'));
            $data['origin-id'] = substr($_REQUEST['origin-id'], 0, strpos($_REQUEST['origin-id'],':'));

            $data['departure-date'] = strtotime($data['departure-date']);
            $data['arrival-date'] = strtotime($data['arrival-date']);

            /*
             * loop over the array again and validate that each field is a numeric value.
             */
            foreach($required as $field) {
                if(empty($data[$field]) || !is_numeric($data[$field])) {
                    $valid = false;
                }
            }

            /*
             * if valid, convert the hours and minutes
             * to seconds and add it to the departure and arrival values. 
             */

        }



    }

    protected function deleteAction() {

    }

    protected function getAction() {

    }

}