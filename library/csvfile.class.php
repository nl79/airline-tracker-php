<?php
namespace library; 

class csvfile {


	private $_filepath = null; 		#path to the csv file. 
	
	private $_headExists = false; 	#flag that is set if the file has a heading row. 
	
	private $_headings = null; 		#array containing the headings if exist. 
	
	private $_data = null; 		#array containing the data rows. 

	public function __construct($filepath, $headings = false) {
	
			#validate the filepath and headings.  
			$this->_filepath = is_string($filepath) && !empty($filepath) ? $filepath : null;
			
			$this->_headExists = is_bool($headings) ? $headings : false;  
			
			#load the csv file
			$this->loadFile(); 
	
	}
	
	/*
	 *@method getHeadings() - returns the headings array
	 *@access public
	 *@return Mixed - returns an array on success or null on failure
	 */
	public function getHeadings() {
		return $this->_headings; 
	}
	
	/*
	 *@method getData() - returns the data array.
	 *@access public
	 *@return Mixed - returns an array on success or null on failure
	 */
	public function getData() {
		return $this->_data; 
	}
	
	/*
	 *@method length() - returns the number of elements in the data array.
	 *@access public
	 *@return Int - number of elements in the data array.
	 */
	public function length() {
		if(is_null($this->_data)) {
			return 0; 
		} else {
			return count($this->_data); 
		}
	}
	
	/*
	 *@method selectRow($key, $value) - returns the value associted with the given key
	 *@access public
	 *@param String $key - Field name as String
	 *@param Scalar $value - Field value as String or Number. 
	 *@return Mixed - returns an empty array if no matches found. 
	 */
	public function selectRow($key, $value) {
		
		#validate the key and the value.
		if(!is_string($key) || !is_scalar($value)) {
			return null; 
		}
		
		#loop throught the data and match the key with the value.
		foreach($this->_data as $row) {
			
			#loop through each row
			foreach($row as $rKey => $rValue) {
				
				#check if the key and value matches the parameters. 
				if($rKey == $key && $rValue == $value) {
					
					#return the row. 
					return $row; 
				}
			}
		}
		
		return array(); 
	}
	
	/*
	 *@method getFieldsByName() - returns every value of the field which match the supplied filed name.
	 *@access public
	 *@param String $name - Field name as string
	 *@param Int $limit - limit the result set. (at lest 1 result will be returned)
	 *@return array = returns an empty array on failure. 
	 */
	public function getFieldsByName($name, $limit = null) {
		
		#result array. 
		$result = array(); 
		
		#result count
		$count = 0; 
		
		#validate the value
		if(!is_string($name) || empty($name)) { return $result; }
		
		#conver to lower case
		$name = strtolower($name); 
		
	
		/*
		 *loop through each data item and
		 *extract the field matching the supplied value.
		 */
		
		if(!is_null($this->_data)) {
			#loop throught each array element 
			foreach($this->_data as $row) {
				
				#loop through each element in the row array. 
				foreach($row as $key => $value) {
					
					if(strtolower($key) == $name) {
						
						#add the value to the result array. 
						$result[] = $value;
						
						#update the count
						$count++; 
					}
					
					#check if the limit is equalt to the count. 
					if(is_numeric($limit) && $limit == $count) {
						
						#return the result array. 
						return $result; 
					}
				}
			}
		}
		
		#return the result array. 
		return $result; 
	}
	
	/*
	/@method loadFile() - Validate that the csv file exists and build the array. 
	/@return Boolean - returns true on success or false on failure. 
	*/
	private function loadFile() {
		
		ini_set('auto_detect_line_endings',TRUE);
		
		#check if the file exists 
		if(!is_null($this->_filepath) &&
			is_string($this->_filepath) && 
			file_exists($this->_filepath) &&
			is_file($this->_filepath)) {
			
			#open the csv file
			$file = fopen($this->_filepath, 'r'); 
			
			
			#flag that controls if the headings have been read
			$hRead = false; 
			
			#extract and parse each line
			while($row = fgetcsv($file)) {
				#check if the headings flag is set, and if headings have been read.
				if($this->_headExists && $hRead == false) {
				
					#set the row as the headings array
					$this->_headings = $row; 
					
					#set the read flag to true
					$hRead = true;
					
				} else {
					
					#if the heading is set combine the row and head array. 
					if($this->_headExists && 
						is_array($this->_headings) && 
						!empty($this->_headings)) {
					
						#combine the arrays. 
						$this->_data[] = array_combine($this->_headings, $row); 
					
					} else {
						$this->_data[] = $row; 
					}
				
				}
				
			}
			
			#close the file
			fclose($file); 

		} else {
			#throw an exception 
			throw new Exception('Invalid Filepath supplied: ' . $this->_filepath); 
			return null; 
		}
	}
}
