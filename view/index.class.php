<?php
namespace view;

class index extends view {
    
    /*
     *@method indexView() - default content for the index route
     */
    public function indexView($data) {
             
        $this->_output .= $this->buildUL($data['list']);
        $this->_output .= "
        <div id='div-school-data'>
            <div id='div-content'></div>
        </div>
        ";
      
    }
    
    /*
     *@method infoView() - html for the info action.
     */
    public function infoView($data) {
        
        if(isset($data['record']) && is_array($data['record'])) {
            
            
            $this->_output .= \library\html::table(array('id'=>'table-school-data',
                                           'border' => '1',
                                           'data' => $data['record']), true, true);
        }
        
    }
    
    
    /*
     *@method buildUL() - Build the UL element for the school list
     */
    
    private function buildUL($list) {
                $html = '';
                
                $html .= "<div id='div-school-list'>
                            <h1>School List</h1>    
                            <ul id='ul-links'>"; 
                
                #count used to index each record for faster look up.
                $index = 0; 
                foreach($list as $row) {
                
                        #build the anchor tag.
                        /*
                        $a = \library\html::a(array('href' => "./?UNITID=" . $row['UNITID'] . '&ac=info',
                                              'data' => $row['INSTNM']));
                                              */
                        $a = \library\html::a(array('href' => "./?UNITID=" .$row['UNITID'] .
                                                    '&ac=info&id=' . $index .
                                                    '&page=index',
                                              'data' => $row['INSTNM']));
                        
                        #increment index
                        $index++;
                        
                        #build the li element. 
                        $li = \library\html::li(array('class' => 'li-item',
                                             'data' => $a)); 
                        $html .= $li; 
                        
                }
                
                $html .= "</ul></div>"; 
                
                $this->_output .= $html; 
    }
     
}