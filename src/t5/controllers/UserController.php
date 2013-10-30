<?php

/**
 * T5_UserController class.
 * 
 * @extends Core_Controller
 */
class T5_UserController extends Core_Controller{

	
	/**
	 * init function.
	 * 
	 * @access public
	 * @return void
	 */
	public function init(){	
		
		parent::init(false);
		
	}
	
	/**
	 * loginAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function loginAction(){
		
		// check if param exists, else return false, this function can be used anywhere for param check
		$this->_checkParam('email');
		
		// check if user exists
		$check = $this->users->fetchRow("email='".$this->_getParam('email')."' and passwd='".$this->_getParam('passwd')."'");
		
		// if user found generate the key and update in DB and return same key
		// key will always be unique even if user login next time
		// it will be unique for all use, even if they login same time
		if($check){
		
			// generate unique key
			$key = sha1($check->email.''.time());
			$update['login_key'] = $key;
			$update['last_login'] = time();
			
			// udpate the user
			$this->users->doUpdate($update,"id='".$check->id."'");
			
			// get user now, so you get the new key 
			$user = $this->users->doRead($check->id);
			// send back data
			$this->_send($data = array('success'=>'true','key'=>$update['login_key'],'data'=>$user->toArray()));
		}
		// else return false
		else{
			$this->_send($data = array('success'=>'false'));
		}
	}	
	
	/**
	 * registerAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function registerAction(){
	
		// check param email
		$this->_checkParam('email');
		
		// check if it does exist
		$check = $this->users->fetchRow("email='".$this->_getParam('email')."'");

		// id it does not exists, then only register
		// else flag for already exists
		if(!$check){
			
			$p = $this->getRequest()->getParams();
			
			// create now onlt if POST
			if($this->users->doCreate($p)){
				$data = array('success'=>'true');
			}
			else{		
				$data = array('success'=>'false');
			}
		}
		// else already exists
		else{
			$data = array('success'=>'false','error'=>'Email already exists.');
		}
		
		// send back json
		$this->_send($data);
	
	}
	
	/**
	 * geoPushAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function geoPushAction(){
	
		// check key, means user is logged in
		$this->_checkParam('key');
		
		// find user with this key, else user not logged in
		$check = $this->users->fetchRow("key='".$this->_getParam('key')."'");

		// if found, means logged in
		if($check){
		
			// get param for geo codes
			$update['latitude'] = $this->_getParam('latitude');
			$update['longitude'] = $this->_getParam('longitude');
			$update['users_id']  = $check->id;
			
			// create new record for this user for the location
			if($this->locations->doCreate($update)){
				$data = array('success'=>'true');
			}
			else{		
				$data = array('success'=>'false');
			}
		}
		// else not logged in
		else{
			$data = array('success'=>'false','error'=>'An Error Occurred. You are logged out.');
		}
		
		// send json
		$this->_send($data);
	}
	
	/**
	 * changePasswordAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function changePasswordAction(){
	
		// check email, must exists
		$this->_checkParam('email');
		
		// email it must exists in db
		$check = $this->users->fetchRow("email='".$this->_getParam('email')."'");

		// if yes, send mail and return true
		if($check){
			$data = array('success'=>'true');
		}
		// else false
		else{		
			$data = array('success'=>'false','data'=>'Invalid email.');
		}
		
		$this->_send($data);
	}
	
	/**
	 * settingsAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function settingsAction(){

		// key must be send
		$this->_checkParam('key');
		
		// find user with this key
		$check = $this->users->fetchRow("key='".$this->_getParam('key')."'");

		// only if user is found
		if($check){
		
			// get param
			$update['storage'] 		= $this->_getParam('storage');
			$update['distance'] 	= $this->_getParam('distance');
			$update['interval'] 	= $this->_getParam('interval');
			$update['logging_period'] = $this->_getParam('logging_period');
			
			// update for this user only
			if($this->users->doUpdate($update,"key='".$this->_getParam('key')."'")){
				$user = $this->users->doRead($check->id);
				$data = array('success'=>'true','data'=>$user->toArray());
			}
			else{		
				$data = array('success'=>'false');
			}
		}
		// not logged in
		else{
			$data = array('success'=>'false','error'=>'You are not logged in.');
		}
		
		// send json
		$this->_send($data);
	
	}

	/**
	 * logoutAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function logoutAction(){
	
		// logout user, but key should be there
		$this->_checkParam('key');
		
		// get user with key
		$check = $this->users->fetchRow("key='".$this->_getParam('key')."'");
		
		// set key empty
		$this->users->doUpdate(array('key'=>'',"id='".$check->id."'"));
		
		// send data back , in all cases, TRUE
		$data = array('success'=>'true');
		$this->_send($data);
	
	}
	
	/**
	 * _sendAction function.
	 * 
	 * @access private
	 * @return void
	 */
	private function _send($data){
		
		// reponse is json only
		$response = $this->getResponse();
		$response->setHeader('Content-type', 'application/json', true);
		$this->_helper->json->sendJson($data);

	}
	
	/**
	 * _checkKey function.
	 * 
	 * @access private
	 * @return void
	 */
	private function _checkParam($param){
	
		//echo $param;
		$param = $this->_getParam($param);
		//die;
		
		// common function to check param
		if(!$param){
			$this->_send(array('success'=>'false'));
		}
	}
	
}