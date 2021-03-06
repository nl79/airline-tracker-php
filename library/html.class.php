<?php
namespace library; 

class html {
    
    /*
     *@method a() - build an anchor tag
     *@static
     *@access public
     *@param array $args - parameters array.
     */ 
    public static function a($args = array()) {
        
        $html = '<a href="' . html::getVal($args, 'href') . '">' . html::getVal($args, 'data') . '</a>';
        
        return $html; 
    }
    
    
    public static function li($args = array()) {
        
        $html = "<li id='" . html::getVal($args, 'id') . "' class='" . html::getVal($args, 'class') . "'>" . html::getVal($args, 'data') . "</li>";
        
        return $html; 
    }
    
    public static function table($args = array(), $headings = false, $vertical = false) {
        
        #get the headigs and the data arrays
        $data = html::getVal($args, 'data');
         
        #build the table. 
        $html = "<table id='" . html::getVal($args, 'id') .
            "' class='" . html::getVal($args, 'class') .
            "' border='" . html::getVal($args, 'border') . " '>";
        
        #check if the headings flag is set and extract the headings.
        if($headings == true) {
            
            #extract the keys of the first data array element. 
           // $headings = array_keys($data[0]);
            $headings = array_keys($data[key($data)]);
        }
        
        #check the table orientation and build the heading.
        if($vertical == false) {
            
            $html .= '<thead><tr>';
            
            foreach($headings as $item) {
                $html .= '<th>' . $item . '</th>'; 
            }
            
            $html .= '</tr></thead>';
            
            foreach($data as $row) {
                


                /*
                 * check if id_field argument is specified.
                 * if so, include the id_field value
                 * as the row id value.
                 */
                if(isset($args['id_field']) && !empty($args['id_field'])) {
                    #extract the field_id value.
                    $id_field = html::getVal($args, 'id_field');

                    #set the id_field value and the row field value as the row id.
                    $html .= '<tr id="' . $id_field . '-' . $row[$id_field] . '" >';

                } else {

                    $html .= '<tr>';
                }
                
                if(is_array($row)){
                    
                    foreach($row as $key => $value) {
                        
                        $html .= '<td>'; 
                       
                        $html .= !is_scalar($value) ? '&nbsp;' : $value; 
                            
                        $html .= '</td>'; 
                    }
                }
                
                $html .= '</tr>'; 
            }
            
        } else if( $vertical == true) {
            
            $html .= '<thead><tr>';
            
            $count = count($data);
            
            for($i = 0; $i <= $count; $i++) {
                
                $html .= '<th></th>'; 
            }
            
            $html .= "</tr></thead>";
            
            $vTable = array(); 
            
            foreach($headings as $head) {
                $vTable[$head] = array($head); 
            }
            
            foreach($data as $key => $row) {
                
                if(is_array($row)) {
                    
                    foreach($row as $key => $value) {
                        
                        if(!is_scalar($value)) {

                           $vTable[$key][] = '&nbsp;';

                            continue;
                        }
                        
                        $vTable[$key][] = $value; 
                    }
                    
                } else if(is_string($row)) {

                    $vTable[$key][] = $row; 
                }
                
            }
            
            #loop through the $vTable and build the html controls.
            foreach($vTable as $row) {

                $html .= '<tr>';

                foreach($row as $item) {

                    $html .= '<td>' . $item . '</td>';

                }

                $html .= '</tr>';

            }
        }
        
        $html .= '<tfoot></tfoot></table>';    
        
        return $html; 
    }
    
    
    private static function getVal($args = array(), $val = "") {
        
        #validate if the parameters are valid. 
        if(!is_array($args) || empty($args) ||
           !is_string($val) || empty($val) ||
            !isset($args[$val])) {
            return ""; 
        }
        
        #return the value. 
        return $args[$val]; 
    }
}