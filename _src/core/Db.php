<?php
/*
	
	PLEASE DO NOT EDIT ANYTHING IN THIS CLASS
	
	Copyright NARWALSANDEEP@GMAIL.COM

*/

/**
 * Code_Db class.
 * 
 * @extends Zend_Db_Table
 */
class Core_Db extends Zend_Db_Table{
	
	
	/**
	 * _businessRules
	 * 
	 * (default value: array())
	 * 
	 * @var array
	 * @access private
	 */
	private $_businessRules = array();
	
	/**
	 * _params
	 * 
	 * @var mixed
	 * @access private
	 */
	private $_params;
	
	
	/**
	 * getColumns function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getColumns(){
		
		return $this->info(Zend_Db_Table_Abstract::COLS);
	}
	
	/**
	 * getCount function.
	 * 
	 * @access public
	 * @param mixed $where (default: null)
	 * @return void
	 */
	public function getCount($where = null){
		
		$select = $this->select();
		if($where){
			$select->from($this->_name,"COUNT(id) AS total")->where($where);
		}
		else
			$select->from($this->_name,"COUNT(id) AS total");
		
		$row=$this->fetchRow($select);
		return $row->total;

	}
	
	
	/**
	 * fetchPaginatorAdapter function.
	 * 
	 * @access public
	 * @param array $filters (default: array())
	 * @param mixed $sortField (default: null)
	 * @return void
	 */
	public function fetchPaginatorAdapter($filters = array(), $sortField = null) {
		
		$select = $this->select(); 
		// add any filters which are set
	
		if(count($filters) > 0) { 
			foreach ($filters as $field => $filter) {
				$select->where($field . ' ?', $filter);
			}
		} 
		// add the sort field is it is set 
		if(null != $sortField) {
			$select->order($sortField);
		} 
		
		// create a new instance of the paginator adapter and return it 
		$adapter = new Zend_Paginator_Adapter_DbTableSelect($select); 
		return $adapter;
	}
	
	/**
	 * fetchRecords function.
	 * 
	 * @access public
	 * @param string $filters (default: array())
	 * @param mixed $sortField (default: null)
	 * @param mixed $limit (default: null)
	 * @param int $page (default: 1)
	 * @return void
	 */
	public function fetchRecords($filters = array(), $sortField = null, $limit = null,$page = 1){
	
		$select = $this->select(); 
		// add any filters which are set 
		if(count($filters) > 0) {
			foreach ($filters as $field => $filter) { 
				$select->where($field . ' ?', $filter);
			}
		} 
		// add the sort field is it is set 
		if(null != $sortField) {
			$select->order($sortField); 
		}
		
		// add limit 
		if($limit['start']!='' && $limit['limit']!='') {
			$select->limit($limit['limit'],$limit['start']); 
		}
		
		return $this->fetchAll($select);	
		
	}
	
	/**
	 * doQuery function.
	 * 
	 * @access public
	 * @param mixed $qry
	 * @return void
	 */
	public function doQuery($qry){
		
		return $this->getDefaultAdapter()->query($qry);
		
	}
	
	/**
	 * truncate function.
	 * 
	 * @access public
	 * @return void
	 */
	public function truncate(){
       $this->getDefaultAdapter()->query('truncate table '.$this->_name);
    } 
    
	/**
	 * getSearchCount function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getSearchCount($qry){
		
		$stmt = $this->getDefaultAdapter()->query($qry);
		$row  = $stmt->fetchObject();
		return $row->total;
	
	}    
		
	//#### CRUD ####//
	
	
	/**
	 * isUnique function.
	 * 
	 * @access public
	 * @param mixed $field
	 * @param mixed $data
	 * @return void
	 */
	public function isUnique($field,$data){
		
		$data = $this->fetchRow("{$field}='".$data."'");
		if($data){
			return false;
		}
		return true;
		
	}
	
	
	/**
	 * doCreate function.
	 * 
	 * @access public
	 * @param mixed $params
	 * @return void
	 */
	public function doCreate($params){

		//try{
			// create a new row in the table
			$row = $this->createRow();
			
			foreach($this->getColumns() as $key=>$value){
				if(isset($params[$value])){
										
					$row->{$value} = $params[$value];
				}
			}
			
				
			// save the new row 
			$row->save();
			
			// now fetch the id of the row you just created and return it 
			$id = $this->_db->lastInsertId(); 
			return $id;
		//}
		//catch(Exception $e){
		//	return false;
			//throw new Zend_Exception("Create Record Failed.");
		//}
		
	}
	
	/**
	 * doRead function.
	 * 
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	public function doRead($id){
		// find the row that matches the id 
		$row = $this->find($id)->current(); 
		if($row) {
			return $row; 
		} 
		else {
			return false;
			//throw new Zend_Exception("Read function failed; could not find row!");
		}
	}
	
	/**
	 * doUpdate function.
	 * 
	 * @access public
	 * @param mixed $params
	 * @return void
	 */
	public function doUpdate($params,$where){
	
		// find the row that matches the id 
		$row = $this->fetchAll(
				$this->select()->where($where))->current();
		
		if($row) { 
			
			// assign data for each column to update
			foreach($this->getColumns() as $key=>$value){
				if(isset($params[$value]))
					$row->{$value} = $params[$value];
			}

			// save the updated row
			$row->save(); 
			return true;
		} 
		else {
			return false; 
			//throw new Zend_Exception("Update function failed; could not find row!");
		}
	}
	
	/**
	 * doDelete function.
	 * 
	 * @access public
	 * @param mixed $id
	 * @return void
	 */
	public function doDelete($id,$CheckCascadingFlag = false){
	
		$this->find($id)->current()->delete();
		
	}
	

	
}