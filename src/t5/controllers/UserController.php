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
		
	}
	
	/**
	 * loginAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function loginAction(){
		
		// check if its a valid post request
		$this->_checkRequest('POST');
		
		// check if param exists, else return false, this function can be used anywhere 
		// to check if the required params for this api call does exist
		$this->_checkParam('email');
		
		// check if user exists and passwd is correct
		$check = $this->users->fetchRow("email='".$this->_getParam('email')."' and passwd='".$this->_getParam('passwd')."'");
		
		// if user found generate the key and update in DB and return same key
		// key will always be unique even if user login next time
		// and key once used will never be assigned again 
		// because we are using timestamp + email
		if($check){
		
			// generate unique key
			$key = sha1($check->email.''.time());
			$update['login_key'] 	= $key;
			$update['last_login'] 	= time();
			
			// udpate the user
			$this->users->doUpdate($update,"id='".$check->id."'");
			
			// get user now, so you get the new key to through it back to json 
			$user = $this->users->doRead($check->id);
	
			// send back data
			$this->_send($data = array('success'=>'true','key'=>$update['login_key'],'data'=>$user->toArray()));
		}
		// else return false
		else{
			$this->_send($data = array('success'=>'false','msg'=>'Invalid Email or Password'));
		}
		
	}	
	
	/**
	 * registerAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function registerAction(){
	
		// check if its a valid post request
		$this->_checkRequest('POST');
		
		// check param email
		$this->_checkParam('email');
		
		// check if it does exist
		$check = $this->users->fetchRow("email='".$this->_getParam('email')."'");

		// id it does not exists, then only register
		// else flag for already exists
		if(!$check){
<<<<<<< HEAD
=======
			
>>>>>>> d0ee01d684781a2ac139deb58989c19f2b43954a
			// get all parameter that are sent from app
			$p = $this->getRequest()->getParams();
			//$p['json'] = json_encode($p);
			
			// if image
			if($p['profile_image']){				
				$time = time();
				Model_Custom_File::base64ToFile($p['profile_image'],DOC_ROOT.'/uploads/users/'.$time.'.jpg');
				$p['profile_image'] = HTTP.WWW_ROOT.'/uploads/users/'.$time.'.jpg';
			}
			
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
	 * checkInAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function checkInAction(){
	
		// check last login time gap
		// if its more then the user settings, log him out
		
		// check if its a valid post + put request
<<<<<<< HEAD
		$this->_checkRequest('POST');
=======
		$this->_checkRequest('POST_PUT');
>>>>>>> d0ee01d684781a2ac139deb58989c19f2b43954a
		
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
	 * resetPasswordAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function resetPasswordAction(){
	
		// check if its a valid post request
		$this->_checkRequest('POST');
		
		// check email, must exists
		$this->_checkParam('email');
		
		// email it must exists in db
		$check = $this->users->fetchRow("email='".$this->_getParam('email')."'");

		// if yes, send mail and return true
		if($check){
			// static call to send email
			// it takes the user object
			
			// if mail is fine, send true back else false
			if($this->mails->forgotPassword($check)){
				// send true.
				$data = array('success'=>'true','msg'=>'Reset link has been sent on your email.');
			}
			// something went wrong
			else{
				$data = array('success'=>'false','msg'=>'An error occurred, please try again.');
			}
		}
		// else false
		else{		
			$data = array('success'=>'false','data'=>'Invalid email.');
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
	
		// check if its a valid post request
		$this->_checkRequest('POST_PUT');
		
		// key must be send
		$this->_checkParam('key');
		
		// find user with this key
		$check = $this->users->fetchRow("login_key='".$this->_getParam('key')."'");

		// only if user is found
		if($check){
		
			// get param
			$update['passwd'] 		= $this->_getParam('passwd');
			// update for this user only
			if($this->users->doUpdate($update,"login_key='".$this->_getParam('key')."'")){
				$user = $this->users->doRead($check->id);
				$data = array('success'=>'true');
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
	 * settingsAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function settingsAction(){

		// check if its a valid post request
<<<<<<< HEAD
		$this->_checkRequest('POST_PUT');
=======
		$this->_checkRequest('POST');
>>>>>>> d0ee01d684781a2ac139deb58989c19f2b43954a
		
		// key must be send
		$this->_checkParam('key');
		
		// find user with this key
		$check = $this->users->fetchRow("login_key='".$this->_getParam('key')."'");

		// only if user is found
		if($check){
		
			// get param
			$update['storage'] 		= $this->_getParam('storage');
			$update['distance'] 	= $this->_getParam('distance');
			$update['interval'] 	= $this->_getParam('interval');
			$update['logging_period'] = $this->_getParam('logging_period');
			
			// update for this user only
			if($this->users->doUpdate($update,"login_key='".$this->_getParam('key')."'")){
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
	 * checkInAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function reviewAction(){

		$p = $this->getRequest()->getParams();
		// check if its a valid post request
		$this->_checkRequest('POST');
		
		// key must be send
		$this->_checkParam('key');
		
		// find user with this key
		$check = $this->users->fetchRow("login_key='".$p['key']."'");

		// only if user is found
		if($check){
		
			// check if already exists
			$checkVenue = $this->locations->fetchRow("foursquare_venue_id='".$p['venue_id']."'");
			if($checkVenue){
				$insertReview['locations_id'] = $checkVenue->id;
			}
			else{
				$insertLocation['foursquare_venue_id'] 		= $p['venue_id'];
				$insertLocation['foursquare_venue_name'] 	= $p['venue_name'];
				$insertReview['locations_id'] = $this->locations->doCreate($insertLocation);
			}
			
			// if image
			if($p['location_image']){				
				$time = time();
				Model_Custom_File::base64ToFile($p['location_image'],DOC_ROOT.'/uploads/locations/'.$time.'.jpg');
				$insertReview['location_image'] = HTTP.WWW_ROOT.'/uploads/locations/'.$time.'.jpg';
			}
			
			// get param
			$insertReview['users_id'] 		= $check->id;
			$insertReview['rating'] 		= $p['rating'];
			$insertReview['review_title'] 	= $p['review'];
			
			if($this->users_reviews->doCreate($insertReview)){
				$data = array('success'=>'true');
			}
			else{		
				$data = array('success'=>'false');
			}
		}
		// not logged in
		else{
			$data = array('success'=>'false','error'=>'Cannot check in. Try again.');
		}
		
		// send json
		$this->_send($data);
	
	}
	
	
	/**
	 * voteAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function voteAction(){

		$p = $this->getRequest()->getParams();
		
		// check if its a valid post request
		$this->_checkRequest('POST');
		
		// key must be send
		$this->_checkParam('key');
		
		// find user with this key
		$check = $this->users->fetchRow("login_key='".$this->_getParam('key')."'");

		// only if user is found
		if($check){
		
			// check review
			$review = $this->users_reviews->doRead($p['review_id']);
			
			// if review exists
			if($review){
				// check vote flag
				if($p['vote']){
					$updateReview['total_vote_up'] = $review->total_vote_up + 1;
				}
				else{
					$updateReview['total_vote_down'] = $review->total_vote_down +  1;
				}
				
				// update for this user only
				if($this->users_reviews->doUpdate($updateReview,"id='".$p['review_id']."'")){
					
					$checkUserVote = $this->users_votes->fetchRow("users_id='".$check->id."' and users_reviews_id='".$p['review_id']."' ");
			
					// only if not already given.
					if(!$checkUserVote){
						// update for this user
						$insertVotes['vote_flag'] = $p['vote'];
						$insertVotes['users_id'] = $check->id;
						
						$this->users_votes->update($insertVotes);
						$data = array('success'=>'true');
					}
				}
				else{		
					$data = array('success'=>'false','error'=>'Review deleted');
				}
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
	 * venueAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function venueAction(){
		
		$p = $this->getRequest()->getParams();
		
		// check if its a valid post request
		$this->_checkRequest('GET');
		
		// key must be send
		/$this->_checkParam('key');
		
		// find user with this key
		$check = $this->users->fetchRow("login_key='".$p['key']."'");
		
		$checkVenue = $this->locations->fetchRow("foursquare_venue_id='".$p['venue_id']."'");
			
		// if not found
		if(!$checkVenue){
			//die;
			$data = array('success'=>'false');
		}
		else{
			$data = $this->users_reviews->doquery("
				select 
					first_name,profile_image,last_name,email,rating,review_title review
				from
					t5_users_reviews ur,t5_locations l,t5_users u
				where
					ur.locations_id='".$checkVenue->id."'
					and
					ur.locations_id = l.id
					and
					ur.users_id=u.id
			");
			$data = array('success'=>'true','data'=>$data->fetchAll());
			
		}
		
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
			
			
		}
		else{
			$this->_send(array("success"=>"false","error"=>"Invalid Login Key"));
		}
		
		
	}
	
	/**
	 * logoutAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function logoutAction(){
	
		//get all params
		$p = $this->getRequest()->getParams();
		
		// logout user, but key should be there
		$this->_checkParam('key');
		
		// get user with key
		$check = $this->users->fetchRow("login_key='".$p['key']."'");
		
		// set key empty
		$this->users->doUpdate(array('key'=>''),"id='".$check->id."'");
		
		// send data back , in all cases, TRUE
		$data = array('success'=>'true');
		$this->_send($data);
	
	}
	
	/**
	 * pingAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function pingAction(){
		
		$this->_send(array("success"=>"true"));
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
	
<<<<<<< HEAD
	
	
=======
>>>>>>> d0ee01d684781a2ac139deb58989c19f2b43954a
}