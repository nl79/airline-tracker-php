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
        * select all of the most recent flights.
        */
        $sql = 'SELECT t1.*, t2.ac_type as "aircraft type" , t2.tail_number as "tail number"
                FROM flight_table AS t1, aircraft_table AS t2
                WHERE t1.aircraft_id = t2.entity_id
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

            $sql = 'SELECT * FROM CUSTOMER_ORDER AS t1
                JOIN _CUSTOMER AS t2 ON t1.CUSTOMER_ID = t2.CUSTOMER_ID
                WHERE t1.ORDER_ID NOT IN (' . implode(',', $order_ids) . ')';
        } else {

            $sql = 'SELECT * FROM CUSTOMER_ORDER AS t1
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

}