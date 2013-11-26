<?php

/**
 * Model_DbTable_Users class.
 * 
 * @extends Core_Db
 */
class Model_DbTable_Users extends Core_Db{

	/**
	 * _name
	 * 
	 * (default value: 't5_users')
	 * 
	 * @var string
	 * @access protected
	 */
	protected $_name = 't5_users';

	/**
	 * checkKey function.
	 * 
	 * @access public
	 * @param mixed $key
	 * @return void
	 */
	public function checkKey($key){
		
		// get user with key
		return $this->fetchRow("login_key='".$key."'");
		
	}	
	
}