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
		
		$this->_checkParam('email');
		$check = $this->users->fetchRow("email='".$_POST['email']."' and passwd='".$_POST['passwd']."'");
		
		// check email
		if($check){
			$key = sha1($check->email.''.time());
			$update['login_key'] = $key;
			$this->users->doUpdate($update,"id='".$check->id."'");
			
			$this->_send($data = array('success'=>'true','key'=>$key));
		}
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
	
		$this->_checkParam('email');
		$check = $this->users->fetchRow("email='".$_POST['email']."'");

		// check
		if(!$check){
			if($this->users->doCreate($_POST)){
				$data = array('success'=>'true');
			}
			else{		
				$data = array('success'=>'false');
			}
		}
		// 
		else{
			$data = array('success'=>'false','error'=>'Email already exists.');
		}
		
		$this->_send($data);
	
	}
	
	/**
	 * geoPushAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function geoPushAction(){
	
		$this->_checkParam('key');
		// find user
		$check = $this->users->fetchRow("key='".$_POST['key']."'");

		// check
		if($check){
		
			// get param
			$update['latitude'] = $_POST['latitude'];
			$update['longitude'] = $_POST['longitude'];
			$update['users_id']  = $check->id;
			
			if($this->locations->doCreate($update)){
				$data = array('success'=>'true');
			}
			else{		
				$data = array('success'=>'false');
			}
		}
		// 
		else{
			$data = array('success'=>'false','error'=>'An Error Occurred.');
		}
		
		$this->_send($data);
	}
	
	/**
	 * changePasswordAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function changePasswordAction(){
	
		$this->_checkParam('email');
		$check = $this->users->fetchRow("email='".$_POST['email']."'");

		if($check){
			$data = array('success'=>'true');
		}
		else{		
			$data = array('success'=>'false');
		}
	}
	
	/**
	 * settingsAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function settingsAction(){
		// find user
		$this->_checkParam('key');
		
		$check = $this->users->fetchRow("key='".$_POST['key']."'");

		// check
		if($check){
		
			// get param
			$update['storage'] 		= $_POST['storage'];
			$update['distance'] 	= $_POST['distance'];
			$update['interval'] 	= $_POST['interval'];
			$update['logging_period'] = $_POST['logging_period'];
			
			if($this->users->doUpdate($update,"key='".$_POST['key']."'")){
				$data = array('success'=>'true');
			}
			else{		
				$data = array('success'=>'false');
			}
		}
		// 
		else{
			$data = array('success'=>'false','error'=>'An Error Occured.');
		}
		$this->_send($data);
	
	}

	/**
	 * logoutAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function logoutAction(){
	
		$this->_checkParam('key');
		$check = $this->users->fetchRow("key='".$_POST['key']."'");
		$this->users->doUpdate(array('key'=>'',"id='".$check->id."'"));
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
	
		if(!$_POST[$param]){
			$this->_send(array('success'=>'false'));
		}
	}
	
}