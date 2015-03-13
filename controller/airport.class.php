<?php
/**
 * Created by PhpStorm.
 * User: nazarlesiv
 * Date: 3/12/15
 * Time: 6:44 PM
 */

namespace controller;


class airport extends controller {

    protected function indexAction() {

    }

    /*
     * @method findAction() - search of a record using the supplied query.
     */
    protected function findAction() {

        #include the global connection object.
        global $dbc;

        #extract the search query.
        $query = isset($_REQUEST['query']) ? $_REQUEST['query'] : null;

        /*
         * if query is not null, build a sql string and execute.
         */
        if(!is_null($query)) {

            #escape the query string
            $sanitized = $dbc->escape_string($query);

            $sql = "SELECT entity_id, `name`, city, country, faa_code
                FROM airport_table
                WHERE `name` OR city like '" . $sanitized . "%'
                LIMIT 10";

            $result = $dbc->query($sql);

            /*
             * if the result is not empty, build a data array
             */
            if($result && $result->num_rows > 0) {
                $data = array();

                while($row = $result->fetch_assoc()){
                    /*
                     * transform the data to be acceptable
                     * to jQuery autocomplete widget.
                     */
                    $temp = array();
                    $temp['value'] = $row['entity_id'] . ': ' .
                        $row['name'] . ' - ' .
                        $row['city'] . ', ' .
                        $row['country'];

                    $temp['label'] = implode(',', $row);
                    $data[] = $temp;

                }

                #echo a json object.
                echo(json_encode($data));
            }
        }

    }
}