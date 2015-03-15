<?php
/**
 * Created by PhpStorm.
 * User: nazarlesiv
 * Date: 3/15/15
 * Time: 10:37 AM
 */

namespace controller;


class aircraft extends controller{

    protected function indexAction() {

        Global $dbc;

        //output data
        $data = array();

        /*
         * select all of the aircraft records.
         */

        $sql = 'SELECT * FROM aircraft_table ORDER BY entity_id DESC';

        $aircrafts = $dbc->query($sql);

        while($row = $aircrafts->fetch_assoc()){
            $data['aircrafts'][] = $row;
        }


        /*
         *load the view
         */
        $view = new \view\aircraft('index', $data);


    }

    /*
     * get the record for the supplied entity_id
     */
    protected function viewAction() {

        $id = isset($_REQUEST['entity_id'])
            && !empty($_REQUEST['entity_id'])
                && is_numeric($_REQUEST['entity_id']) ? $_REQUEST['entity_id'] : null;

        if(!is_null($id)) {
            global $dbc;

            $sql = 'SELECT * FROM aircraft_table WHERE entity_id=' . $dbc->escape_string($id);

            $res = $dbc->query($sql);

            if($res) {

                $output = array(
                    'statusCode' => 200,
                    'op' => 'get',
                    'data' => $res->fetch_assoc()
                );

            } else {

                $output = array(
                    'statusCode' => 500,
                    'op' => 'get',
                    'error' => $dbc->error);

            }

        } else {
            $output = array(
                'statusCode' => 400,
                'op' => 'get',
                'error' => "invalid entity_id supplied"
            );
        }

        echo(json_encode($output));
        exit;
    }

    protected function saveAction() {

        global $dbc;

        #require fields
        $required = array('tail_number' => 'i',
            'ac_type' => 's',
            'fuel' => 'i');

        $data = array() ;

        #valid flag.
        $valid = true;

        #error obj.
        $error = array();

        #loop and validate the supplied data.
        foreach($required as $field => $type) {

            if(isset($_REQUEST[$field]) && !empty($_REQUEST[$field])) {

                #validate the datatype
                $func = null;

                switch($type) {
                    case 'i':
                        $func = 'is_numeric';
                        break;
                    case 's':
                        $func = 'is_scalar';
                        break;

                }
                if(!is_null($func) && $func($_REQUEST[$field])) {

                    /*
                     * if type == 'i' cast the data to an integer explicitly.
                     */
                    $data[$field] = $type == 'i'? (int) $_REQUEST[$field] : $_REQUEST[$field];

                } else {

                    echo('failed - ' . $field);
                    $valid = false;

                    $error[$field] = $field . " - Is Invalid";
                }


            } else {

                $valid = false;

            }
        }

        /*
         * if the supplied data is valid
         * build the sql query depending on weather
         * the emtity_id is supplied.
         */
        if($valid) {

            #get the entity_id
            $id = isset($_REQUEST['entity_id'])
                && !empty($_REQUEST['entity_id'])
                && is_numeric($_REQUEST['entity_id']) ? $_REQUEST['entity_id'] : null;

            #if entity_id is not null and is numeric, build an update query.
            if(!is_numeric($id) && is_numeric($id)) {

            } else if(is_null($id)) {

                #if entity_id is null, build an insert query.
                $sql = 'INSERT INTO aircraft_table (' . implode(',', array_keys($required)) . ') VALUES (';

                foreach($data as $field) {
                    $sql .= "'" . $dbc->escape_string($field) . "',";
                }

                $sql = rtrim($sql, ',') . ')';

                /*
                 * execute the query and return the result.
                 */

                $res = $dbc->query($sql);

                if($res) {

                    /*
                     *array_merge is required to have entity_id as the first key in the array.
                     * This will maintain consistency between output to the front-end.
                     */
                    $row = array_merge(array('entity_id' => $dbc->insert_id), $data);

                    $output = array('statusCode' => 200,
                        'op' => 'new',
                        'data' => $row);

                } else {

                    # return an error object.
                    $output = array('statusCode' => 500,
                        'op' => 'new',
                        'error' => $dbc->error);
                }
            }
        }

        echo(json_encode($output));

        exit;
    }


}