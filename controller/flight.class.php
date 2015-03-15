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
                WHERE t1.aircraft_id = t2.entity_id
                ORDER BY t1.entity_id DESC';

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

        global $dbc;

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

            if($valid) {
                $data['departure-date'] += ($data['departure-hour'] * 60 * 60);
                $data['departure-date'] += ($data['departure-minute'] * 60);
                $data['departure-date'] = date("Y-m-d H:i:s", $data['departure-date']);

                $data['arrival-date'] += ($data['arrival-hour'] * 60 * 60);
                $data['arrival-date'] += ($data['arrival-minute'] * 60);
                $data['arrival-date'] = date("Y-m-d H:i:s", $data['arrival-date']);

                /*
                 *check if the entity_id is supplied.
                 * if so, create an update query.
                 * else create an insert query.
                 */

                $entity_id = isset($_REQUEST['entity-id'])
                    && !empty($_REQUEST['entity-id'])
                    && is_numeric($_REQUEST['entity-id']) ? $_REQUEST['entity-id'] : null;

                #ARRAY OF REQUIRED FIELD NAMES
                $fields = array('origin_id',
                    'destination_id',
                    'aircraft_id',
                    'departure_time',
                    'arrivate_time');

                if(is_null($entity_id)) {
                    # generate and insert query.
                    $sql = 'INSERT INTO flight_table(' . implode(',', $fields) . ')
                            VALUES(';

                    $sql .= "'" . $dbc->escape_string($data['origin-id']) . "', ";
                    $sql .= "'" . $dbc->escape_string($data['destination-id']) . "', ";
                    $sql .= "'" . $dbc->escape_string($data['aircraft-id']) . "', ";
                    $sql .= "'" . $dbc->escape_string($data['departure-date']) . "', ";
                    $sql .= "'" . $dbc->escape_string($data['arrival-date']) . "'";
                    $sql .= ')';

                    #execute the query
                    $result = $dbc->query($sql);

                    if($result) {

                        /*
                         * if sucessfull query the database to get the new data in the
                         * required format and return it. (pretty inefficient!).
                         */

                        $q = 'SELECT t1.*, t2.ac_type as "aircraft type" , t2.tail_number as "tail number"
                                FROM flight_table AS t1, aircraft_table AS t2
                                WHERE t1.aircraft_id = t2.entity_id and t1.entity_id =' . $dbc->insert_id;

                        $result = $dbc->query($q);


                        #build an output json object with a status code of 200 and the newly created record.

                        $output = array('statusCode' => '200',
                            'op' => 'new',
                            'data' => $result->fetch_assoc());

                        echo(json_encode($output));

                        exit;

                    } else {
                        echo($dbc->error);
                    }

                } else {
                    # generate an update query.
                }

            }

        }



    }

    protected function deleteAction() {

        /*
         * get the entity_id
         */
        $id = isset($_REQUEST['entity_id']) &&
            !empty($_REQUEST['entity_id']) &&
            is_numeric($_REQUEST['entity_id']) ? $_REQUEST['entity_id'] : null;

        if(!is_null($id)){

            global $dbc;

            /*
             * build the delete query.
             */
            $sql = 'DELETE FROM flight_table WHERE entity_id =' . $id;

            /*
             * execute the query and build a responce object.
             */
            $result = $dbc->query($sql);

            if($result && $dbc->affected_rows > 0) {
                $output = array('statusCode' => 200,
                    'op' => 'delete',
                    'message' => 'sucess',
                    'affected_rows' => $dbc->affected_rows,
                    'entity_id' => $id);


            } else {
                $output = array('statusCode' => 500,
                    'op' => 'delete',
                    'message' => $dbc->error,
                    'affected_rows' => $dbc->affected_rows,
                    'entity_id' => $id);
            }


        } else {
            $output = array('statusCode' => 500,
                'op' => 'delete',
                'message' => 'invalid id supplied');
        }

        echo(json_encode($output));

        exit;

    }

    protected function getAction() {

    }

}