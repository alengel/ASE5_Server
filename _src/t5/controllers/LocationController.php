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
		
		// disable master layout
		// all return are json unless explicitly layout declared
		$this->_helper->layout()->disablelayout();
		
		// check last login gap and return of gap exceeded more the login sesson
		// common for all calls
		$p = $this->geRequest()->getParams();
		// if request delayed logout!
		if(!$this->users->checkLastLoginTimeout($p['key']){
			$this->_send(array("success"=>"false","error"=>"session expired"));		
		}
		 
	}
	
	
	/**
	 * checkInAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function checkInAction(){
	
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
	 * reviewsAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function reviewsAction(){

		// get all params
		$p = $this->getRequest()->getParams();
		
		// check if its a valid post request
		$this->_checkRequest('GET');
		
		// key must be send
		$this->_checkParam('key');
		
		// find user with this key
		$check = $this->users->fetchRow("login_key='".$this->_getParam('key')."'");

		// only if user is found
		if($check){
		
			// get all user of this review
			$reviewer = $this->users->doquery(
				"select id as users_id,first_name,last_name,email,profile_image from t5_users where id='".$p['reviewer_id']."'"
			);
			
			// get all users who posted reviews for this venue
			$data = $this->users_reviews->doquery("
				select 
					rating,review_title review,
					l.foursquare_venue_name location_name, review_picture location_image
				from
					t5_users_reviews ur,t5_locations l,t5_users u
				where
					ur.users_id='".$p['reviewer_id']."'
					and
					ur.locations_id = l.id
					and
					ur.users_id=u.id
			");
			
			// build the return param
			$data = array(
				"success"=>"true",
				"profile"=>$reviewer->fetchAll(),
				"details"=>$data->fetchAll()
			);

		}
		// not logged in
		else{
			$data = array('success'=>'false','error'=>'You are not logged in.');
		}
		
		// send json
		$this->_send($data);
	
	}
	
	
		/**
	 * putCommentsAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function putCommentsAction(){
		
		$p = $this->getRequest()->getParams();
		
		// check post
		$this->_checkRequest('POST');
		
		// check key
		$this->_checkParam('key');
		
		// check key else exit
		if($user = $this->users->checkKey($p['key'])){
			
			// set insert params for comments
			$insert['comments'] 	= $p['comments'];
			$insert['users_id'] 	= $user->id;
			$insert['reviews_id'] 	= $p['reviews_id'];
			
			// insert into db
			$this->users_reviews_comments->doCreate($insert);
		}
		else{
			$this->_send(array("success"=>"false","error"=>"Invalid Login Key"));
		}
		
		
	}
	
	/**
	 * getCommentsAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function getCommentsAction(){
	
		// get all params
		$p = $this->getRequest()->getParams();
		
		// check post
		$this->_checkRequest('POST');
		
		// check key
		$this->_checkParam('key');
		
		// check key else exit
		if($user = $this->users->checkKey($p['key'])){
			
			// get all comments for the reviews
			$data - $this->users_reviews_comments->fetchAll("reviews_id='".$p['reviews_id']."'");
			$this->_send(array('success'=>'true','data'=$data->toArray()));
		}
		else{
			$this->_send(array("success"=>"false","error"=>"Invalid Login Key"));
		}
		
		
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