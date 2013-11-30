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
		
		// init the default parameter from custom controller
		// this can be extended for other stuff in future
		// add more function call to core controller, if needed, and use after init
		parent::init(false);
		
		$this->_helper->layout()->disablelayout();
		
		
	}
	
	
	/**
	 * checkInAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function checkInAction(){
	
		// check last login time gap
		// if its more then the user settings, log him out
		
		// check if its a valid post + put request
		$this->_checkRequest('POST');
		
		// check key, means user is logged in
		$this->_checkParam('key');
		
		// find user with this key, else user not logged in
		$check = $this->users->fetchRow("login_key='".$this->_getParam('key')."'");

		// if found, means logged in
		if($check){
		
			// get param for geo codes
			$update['dated'] 		= $this->_getParam('timestamp');
			$update['venue_id'] 	= $this->_getParam('venue_id');
			$update['users_id']  	= $check->id;
			
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
	 * _sendAction function.
	 * 
	 * @access private
	 * @return void
	 */
	private function _send($data){
		
		// reponse is json only
		$response = $this->getResponse();
		
		// set the correct header
		$response->setHeader('Content-type', 'application/json', true);
		
		// send json
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
	
	/**
	 * _checkRequest function.
	 * 
	 * @access private
	 * @param mixed $type
	 * @return void
	 */
	private function _checkRequest($type){
		
		// default response;
		$response = array('success'=>'false'); 
		
		// check type
		switch($type){
			
			// check if post
			case "POST":
				
				// only post needed 
				if($this->getRequest()->isPost()){
					return true; 
				}
				break;
			// check if get
			case "GET":
				
				return true; 
				break;
			
			case 'POST_PUT':
				// POST + PUT needed
				
				// check post and then put as custom variable
				if($this->getRequest()->isPost()){
					if($this->_getParam('request') == 'PUT'){
						return true;
					}
				}
				
				break;
			
		}
		
		// if nothing works send the default response
		$this->_send($response);
	}
	
	
	
}