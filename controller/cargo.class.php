<?php
/**
 * Created by PhpStorm.
 * User: nazarlesiv
 * Date: 3/15/15
 * Time: 10:37 AM
 */

namespace controller;


class cargo extends controller{

    protected function indexAction() {

        Global $dbc;

        //output data
        $data = array();

        /*
         * select all of the crew records.
         */

        $sql = 'SELECT * FROM cargo_table ORDER BY entity_id DESC';

        $cargo = $dbc->query($sql);

        while($row = $cargo->fetch_assoc()) {
            $data['cargo'][] = $row;
        }

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
        $view = new \view\cargo('index', $data);


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

            $sql = 'SELECT * FROM cargo_table WHERE entity_id=' . $dbc->escape_string($id);

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
            $sql = 'DELETE FROM cargo_table WHERE entity_id =' . $id;

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


    /*
     * @mthod saveAction() - saves or updates record data based on the method called.
     */
    protected function saveAction() {

        global $dbc;

        #require fields
        $required = array('aircraft_id' => 'i',
            'skid_id' => 'i',
            'weight' => 'd',
            'contents' => 's',
            'mission' => 's',
            'location' => 's');

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
                    case 'd':
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
            if(!is_null($id) && is_numeric($id)) {

                /*
                 * build an update query for the supplied entity_id.
                 */

                $sql = 'UPDATE cargo_table SET ';

                foreach($required as $key => $type) {
                    $sql .= $key . "='" . $dbc->escape_string($data[$key]) . "',";
                }

                #trim off the trailing comma.
                $sql = rtrim($sql, ',');

                $sql .= ' WHERE entity_id=' . $dbc->escape_string($id);

                $res = $dbc->query($sql);

                if($res && $dbc->affected_rows > 0) {

                    /*
                   *array_merge is required to have entity_id as the first key in the array.
                   * This will maintain consistency between output to the front-end.
                   */
                    $row = array_merge(array('entity_id' => $id), $data);

                    $output = array('statusCode' => 200,
                        'op' => 'update',
                        'data' => $row,
                        'entity_id' => $id);

                } else {
                    # return an error object.
                    $output = array('statusCode' => 500,
                        'op' => 'update',
                        'error' => $dbc->error);
                }



            } else if(is_null($id)) {

                #if entity_id is null, build an insert query.
                $sql = 'INSERT INTO cargo_table (' . implode(',', array_keys($required)) . ') VALUES (';

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
                        'op' => 'insert',
                        'data' => $row);

                } else {

                    # return an error object.
                    $output = array('statusCode' => 500,
                        'op' => 'insert',
                        'error' => $dbc->error);
                }
            }
        } else {

            $output = array(
                'statusCode' => 400,
                'op' => 'save',
                'error' => "invalid data supplied"
            );
        }

        echo(json_encode($output));

        exit;
    }


}