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
	
	/**
	 * checkLastLoginTimeout function.
	 * 
	 * @access public
	 * @param mixed $key
	 * @return void
	 */
	public function checkLastLoginTimeout($key){
		
		// get user
		$user = $this->fetchRow("login_key='".$key."'");
		
		// request came within the valid session
		if(($user->logout_session_time * 60) > (time() - $this->last_request) ){
			
			$update['last_request'] = time();
			$this->doUpdate($update);
			return true;
			
		}
		else{
			return false;
		}
	
	}
}