<?php
/**
 * Created by PhpStorm.
 * User: nazarlesiv
 * Date: 4/12/15
 * Time: 10:48 AM
 */

namespace controller;


class shipment  extends controller{

    protected function indexAction() {

        //get the main dbc object.
        global $dbc;

        //get the secondary db connection
        $dbc2 = $this->getDBC2();

        $data = array();


        /*get the shipment data from the data base */
        $shipments = $dbc->query('SELECT * FROM shipment_table');

        //order ids that have been shipped.
        $order_ids = array();

        while($row = $shipments->fetch_assoc()){
            array_push($order_ids, $row['order_id']);

            $data['shipments'][$row['entity_id']] = $row;
        }


        /*
        * select all of the most recent flights departing after now.
        */
        $sql = 'SELECT t1.entity_id, t1.origin_id as Origin, t1.destination_id as Dest,
                t1.departure_time as Departure, t1.arrivate_time as Arrival,
                t2.ac_type as "Ac Type" , t2.tail_number as "Tail #"
                FROM flight_table AS t1, aircraft_table AS t2
                WHERE t1.aircraft_id = t2.entity_id and departure_time >= NOW()
                ORDER BY t1.entity_id DESC';

        $flights = $dbc->query($sql);

        while($row = $flights->fetch_assoc()){
            $data['flights'][$row['entity_id']] = $row;
        }


        /*
         * get all of the orders and their assocciated IDs.
         */

        //if there are shipments, limit the orders to those not yet shipped.
        if(!empty($order_ids)) {

            $sql = 'SELECT t1.*, t2.CUSTOMER_ZIP AS Zipcode, t2.CUSTOMER_PHONE AS Phone, t2.CUSTOMER_EMAIL as Email
                FROM CUSTOMER_ORDER AS t1
                JOIN _CUSTOMER AS t2 ON t1.CUSTOMER_ID = t2.CUSTOMER_ID
                WHERE t1.ORDER_ID NOT IN (' . implode(',', $order_ids) . ')';
        } else {

            $sql = 'SELECT t1.*, t2.CUSTOMER_ZIP AS Zipcode, t2.CUSTOMER_PHONE AS Phone, t2.CUSTOMER_EMAIL as Email
                FROM CUSTOMER_ORDER AS t1
                JOIN _CUSTOMER AS t2 ON t1.CUSTOMER_ID = t2.CUSTOMER_ID';
        }


        $orders = $dbc2->query($sql);

        while($row = $orders->fetch_assoc()){

            $data['orders'][$row['ORDER_ID']] = $row;
        }


        /*
         *load the view
         */
        $view = new \view\shipment('index', $data);

    }


    protected function saveAction() {

        /* valid flag */
        $valid = true;

        /*required fields array*/
        $required = array('order_id', 'flight_id');

        foreach($required as $item) {
            if(!isset($_REQUEST[$item]) || empty($_REQUEST[$item]) ||
                !is_numeric($_REQUEST[$item])) {

                $valid = false;
            }
        }

        $output = array();

        //get the dbc reference
        global $dbc;

        if($valid) {
            $sql = "INSERT INTO shipment_table(flight_id, order_id)
                    VALUES('" .$dbc->escape_string($_REQUEST['flight_id']) .
                        "','" .$dbc->escape_string($_REQUEST['order_id']) . "')";

            $res = $dbc->query($sql);

            if($res) {

                /*
                 *array_merge is required to have entity_id as the first key in the array.
                 * This will maintain consistency between output to the front-end.
                 */
                $data = array('entity_id' => $dbc->insert_id,
                    'flight_id' => $_REQUEST['flight_id'],
                    'order_id' => $_REQUEST['order_id']);

                $output = array('statusCode' => 200,
                    'op' => 'insert',
                    'data' => $data);

            } else {

                # return an error object.
                $output = array('statusCode' => 500,
                    'op' => 'insert',
                    'error' => $dbc->error);
            }

        }

    }

}