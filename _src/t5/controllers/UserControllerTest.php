<?php

/**
 * T5_UserControllerTest class.
 * 
 * @extends Zend_Test_PHPUnit_ControllerTestCase
 */
class T5_UserControllerTest extends Zend_Test_PHPUnit_ControllerTestCase {
	
	private $_connectionMock;	

	/**
	 * setUp function.
	 * 
	 * @access public
	 * @return void
	 */
	public function setUp() {
		
		// bootstrap the application for testing
		$this->bootstrap = new Zend_Application(
			'testing',
			APPLICATION_PATH . '/../_src/cfg/application.ini'
		);
		
		// run parent setup
		parent::setUp();
	}

	/**
	* Returns the test database connection.
	*
	* @return PHPUnit_Extensions_Database_DB_IDatabaseConnection
	*/
	protected function getConnection(){

		// only if connections is null
		if($this->_connectionMock == null) {

			// get the connection params and connect to db
			$connection = Zend_Db::factory($this->config->database);
			
			// create connection with zftest
			$this->_connectionMock = $this->createZendDbConnection(
			    $connection, 'zfunittests'
			);
			
			// get the adapter
			Zend_Db_Table_Abstract::setDefaultAdapter($connection);
		}
		
		// return mock connection
		return $this->_connectionMock;
	}
	
	/**
	 * testValidLoginApiPostCall function.
	 * 
	 * @access public
	 * @return void
	 */
	public function testValidLoginApiPostCall(){
	
		// reset both request and response
		$this->resetRequest()->resetResponse();
		
		// Set headers, even:
        $this->request->setHeader('X-Requested-With', 'XmlHttpRequest');
 		// its a POST method 
		$this->request->setMethod('POST')->setPost(array());
		
		// dispatch method to calls
		$this->dispatch('/user/login');
		
		// route to same path
		$this->assertRoute('/user/login');
		// module name to test
		$this->assertModule('t5');
		// controller to check
		$this->assertController('user');
		// action to check
		$this->assertAction('login');
		// do not redirect
		$this->assertNotRedirect();
		
		
		// queries assertion check
		$this->assertQuery('email');
		$this->assertQuery('passwd');
		$this->assertQueryContentContains('email','passwd');
		
	}
 	
 	
 	/**
	 * testValidRegisterApiPostCall function.
	 * 
	 * @access public
	 * @return void
	 */
	public function testValidRegisterApiPostCall(){
	
		// reset both request and response
		$this->resetRequest()->resetResponse();
		
		// Set headers, even:
        $this->request->setHeader('X-Requested-With', 'XmlHttpRequest');
 		// its a POST method 
		$this->request->setMethod('POST')->setPost(array());
		
		// dispatch method to calls
		$this->dispatch('/user/register');
		
		// route to same path
		$this->assertRoute('/user/register');
		// module name to test
		$this->assertModule('t5');
		// controller to check
		$this->assertController('user');
		// action to check
		$this->assertAction('register');
		// do not redirect
		$this->assertNotRedirect();
		
		
		// queries assertion check
		$this->assertQuery('email');
		$this->assertQuery('first_name');
		$this->assertQuery('last_name');
		$this->assertQuery('passwd');
		$this->assertQueryContentContains('email','passwd','first_name','last_name');
		
	}

 	
 	/**
	 * testValidProfileApiPostCall function.
	 * 
	 * @access public
	 * @return void
	 */
	public function testValidProfileApiPostCall(){
	
		// reset both request and response
		$this->resetRequest()->resetResponse();
		
		// Set headers, even:
        $this->request->setHeader('X-Requested-With', 'XmlHttpRequest');
 		// its a POST method 
		$this->request->setMethod('POST')->setPost(array());
		
		// dispatch method to calls
		$this->dispatch('/user/profile');
		
		// route to same path
		$this->assertRoute('/user/profile');
		// module name to test
		$this->assertModule('t5');
		// controller to check
		$this->assertController('user');
		// action to check
		$this->assertAction('profile');
		// do not redirect
		$this->assertNotRedirect();
		
		
		// queries assertion check
		$this->assertQuery('key');
		$this->assertQueryContentContains('key');
		
	}


 	/**
	 * testValidUpdateApiPostCall function.
	 * 
	 * @access public
	 * @return void
	 */
	public function testValidUpdateApiPostCall(){
	
		// reset both request and response
		$this->resetRequest()->resetResponse();
		
		// Set headers, even:
        $this->request->setHeader('X-Requested-With', 'XmlHttpRequest');
 		// its a POST method 
		$this->request->setMethod('POST')->setPost(array());
		
		// dispatch method to calls
		$this->dispatch('/user/update');
		
		// route to same path
		$this->assertRoute('/user/update');
		// module name to test
		$this->assertModule('t5');
		// controller to check
		$this->assertController('user');
		// action to check
		$this->assertAction('update');
		// do not redirect
		$this->assertNotRedirect();
		
		
		// queries assertion check
		$this->assertQuery('key');
		$this->assertQuery('first_name');
		$this->assertQuery('last_name');
		$this->assertQueryContentContains('key','first_name','last_name');
		
	}

	/**
	 * testValidResetPasswordApiPostCall function.
	 * 
	 * @access public
	 * @return void
	 */
	public function testValidResetPasswordApiPostCall(){
	
		// reset both request and response
		$this->resetRequest()->resetResponse();
		
		// Set headers, even:
        $this->request->setHeader('X-Requested-With', 'XmlHttpRequest');
 		// its a POST method 
		$this->request->setMethod('POST')->setPost(array());
		
		// dispatch method to calls
		$this->dispatch('/user/reset-password');
		
		// route to same path
		$this->assertRoute('/user/reset-password');
		// module name to test
		$this->assertModule('t5');
		// controller to check
		$this->assertController('user');
		// action to check
		$this->assertAction('reset-password');
		// do not redirect
		$this->assertNotRedirect();
		
		
		// queries assertion check
		$this->assertQuery('email');
		$this->assertQueryContentContains('email');
		
	}

	/**
	 * testValidChangePasswordApiPostCall function.
	 * 
	 * @access public
	 * @return void
	 */
	public function testValidChangePasswordApiPostCall(){
	
		// reset both request and response
		$this->resetRequest()->resetResponse();
		
		// Set headers, even:
        $this->request->setHeader('X-Requested-With', 'XmlHttpRequest');
 		// its a POST method 
		$this->request->setMethod('POST')->setPost(array());
		
		// dispatch method to calls
		$this->dispatch('/user/change-password');
		
		// route to same path
		$this->assertRoute('/user/change-password');
		// module name to test
		$this->assertModule('t5');
		// controller to check
		$this->assertController('user');
		// action to check
		$this->assertAction('change-password');
		// do not redirect
		$this->assertNotRedirect();
		
		// queries assertion check
		$this->assertQuery('key');
		$this->assertQuery('passwd');
		$this->assertQueryContentContains('key','passwd');
		
	}


	/**
	 * testValidSettingsApiPostCall function.
	 * 
	 * @access public
	 * @return void
	 */
	public function testValidSettingsApiPostCall(){
	
		// reset both request and response
		$this->resetRequest()->resetResponse();
		
		// Set headers, even:
        $this->request->setHeader('X-Requested-With', 'XmlHttpRequest');
 		// its a POST method 
		$this->request->setMethod('POST')->setPost(array());
		
		// dispatch method to calls
		$this->dispatch('/user/settings');
		
		// route to same path
		$this->assertRoute('/user/settings');
		// module name to test
		$this->assertModule('t5');
		// controller to check
		$this->assertController('user');
		// action to check
		$this->assertAction('settings');
		// do not redirect
		$this->assertNotRedirect();
		
		// queries assertion check
		$this->assertQuery('key');
		$this->assertQuery('login_interval');
		$this->assertQuery('distance');
		$this->assertQueryContentContains('key','login_interwal','distance');
		
	}

	/**
	 * testValidFollowApiPostCall function.
	 * 
	 * @access public
	 * @return void
	 */
	public function testValidFollowApiPostCall(){
	
		// reset both request and response
		$this->resetRequest()->resetResponse();
		
		// Set headers, even:
        $this->request->setHeader('X-Requested-With', 'XmlHttpRequest');
 		// its a POST method 
		$this->request->setMethod('POST')->setPost(array());
		
		// dispatch method to calls
		$this->dispatch('/user/follow');
		
		// route to same path
		$this->assertRoute('/user/follow');
		// module name to test
		$this->assertModule('t5');
		// controller to check
		$this->assertController('user');
		// action to check
		$this->assertAction('follow');
		// do not redirect
		$this->assertNotRedirect();
		
		// queries assertion check
		$this->assertQuery('key');
		$this->assertQuery('users_id');
		$this->assertQueryContentContains('key','users_id');
		
	}

	/**
	 * testValidUnfollowApiPostCall function.
	 * 
	 * @access public
	 * @return void
	 */
	public function testValidUnfollowApiPostCall(){
	
		// reset both request and response
		$this->resetRequest()->resetResponse();
		
		// Set headers, even:
        $this->request->setHeader('X-Requested-With', 'XmlHttpRequest');
 		// its a POST method 
		$this->request->setMethod('POST')->setPost(array());
		
		// dispatch method to calls
		$this->dispatch('/user/unfollow');
		
		// route to same path
		$this->assertRoute('/user/unfollow');
		// module name to test
		$this->assertModule('t5');
		// controller to check
		$this->assertController('user');
		// action to check
		$this->assertAction('unfollow');
		// do not redirect
		$this->assertNotRedirect();
		
		// queries assertion check
		$this->assertQuery('key');
		$this->assertQuery('users_id');
		$this->assertQueryContentContains('key','users_id');
		
	}

	/**
	 * testValidFollowingApiGetCall function.
	 * 
	 * @access public
	 * @return void
	 */
	public function testValidFollowingApiGetCall(){
	
		// reset both request and response
		$this->resetRequest()->resetResponse();
		
		// Set headers, even:
        $this->request->setHeader('X-Requested-With', 'XmlHttpRequest');
 		// its a POST method 
		$this->request->setMethod('GET')->setPost(array());
		
		// dispatch method to calls
		$this->dispatch('/user/get-followings');
		
		// route to same path
		$this->assertRoute('/user/get-followings');
		// module name to test
		$this->assertModule('t5');
		// controller to check
		$this->assertController('user');
		// action to check
		$this->assertAction('get-followings');
		// do not redirect
		$this->assertNotRedirect();
		
		// queries assertion check
		$this->assertQuery('key');
		$this->assertQueryContentContains('key');
		
	}

	/**
	 * testValidFollowerApiGetCall function.
	 * 
	 * @access public
	 * @return void
	 */
	public function testValidFollowerApiGetCall(){
	
		// reset both request and response
		$this->resetRequest()->resetResponse();
		
		// Set headers, even:
        $this->request->setHeader('X-Requested-With', 'XmlHttpRequest');
 		// its a POST method 
		$this->request->setMethod('GET')->setPost(array());
		
		// dispatch method to calls
		$this->dispatch('/user/get-follower');
		
		// route to same path
		$this->assertRoute('/user/get-followers');
		// module name to test
		$this->assertModule('t5');
		// controller to check
		$this->assertController('user');
		// action to check
		$this->assertAction('get-followers');
		// do not redirect
		$this->assertNotRedirect();
		
		// queries assertion check
		$this->assertQuery('key');
		$this->assertQueryContentContains('key');
		
	}
	
	/**
	 * testValidVenueApiPostCall function.
	 * 
	 * @access public
	 * @return void
	 */
	public function testValidVenueApiPostCall(){
	
		// reset both request and response
		$this->resetRequest()->resetResponse();
		
		// Set headers, even:
        $this->request->setHeader('X-Requested-With', 'XmlHttpRequest');
 		// its a POST method 
		$this->request->setMethod('POST')->setPost(array());
		
		// dispatch method to calls
		$this->dispatch('/user/venue');
		
		// route to same path
		$this->assertRoute('/user/venue');
		// module name to test
		$this->assertModule('t5');
		// controller to check
		$this->assertController('user');
		// action to check
		$this->assertAction('venue');
		// do not redirect
		$this->assertNotRedirect();
		
		// queries assertion check
		$this->assertQuery('key');
		$this->assertQuery('venue_id');
		$this->assertQueryContentContains('key','venue_id');
		
	}

	/**
	 * testValidPutCommentsApiPostCall function.
	 * 
	 * @access public
	 * @return void
	 */
	public function testValidPutCommentsApiPostCall(){
	
		// reset both request and response
		$this->resetRequest()->resetResponse();
		
		// Set headers, even:
        $this->request->setHeader('X-Requested-With', 'XmlHttpRequest');
 		// its a POST method 
		$this->request->setMethod('POST')->setPost(array());
		
		// dispatch method to calls
		$this->dispatch('/user/put-comments');
		
		// route to same path
		$this->assertRoute('/user/put-comments');
		// module name to test
		$this->assertModule('t5');
		// controller to check
		$this->assertController('user');
		// action to check
		$this->assertAction('put-comments');
		// do not redirect
		$this->assertNotRedirect();
		
		// queries assertion check
		$this->assertQuery('key');
		$this->assertQuery('review_id');
		$this->assertQueryContentContains('key','review_id');
		
	}

	/**
	 * testValidGetCommentsApiGetCall function.
	 * 
	 * @access public
	 * @return void
	 */
	public function testValidGetCommentsApiGetCall(){
	
		// reset both request and response
		$this->resetRequest()->resetResponse();
		
		// Set headers, even:
        $this->request->setHeader('X-Requested-With', 'XmlHttpRequest');
 		// its a POST method 
		$this->request->setMethod('GET')->setPost(array());
		
		// dispatch method to calls
		$this->dispatch('/user/get-comments');
		
		// route to same path
		$this->assertRoute('/user/get-comments');
		// module name to test
		$this->assertModule('t5');
		// controller to check
		$this->assertController('user');
		// action to check
		$this->assertAction('get-comments');
		// do not redirect
		$this->assertNotRedirect();
		
		// queries assertion check
		$this->assertQuery('key');
		$this->assertQuery('review_id');
		$this->assertQueryContentContains('key','review_id');
		
	}

	/**
	 * testValidFindUsersApiGetCall function.
	 * 
	 * @access public
	 * @return void
	 */
	public function testValidFindUsersApiGetCall(){
	
		// reset both request and response
		$this->resetRequest()->resetResponse();
		
		// Set headers, even:
        $this->request->setHeader('X-Requested-With', 'XmlHttpRequest');
 		// its a POST method 
		$this->request->setMethod('GET')->setPost(array());
		
		// dispatch method to calls
		$this->dispatch('/user/find-users');
		
		// route to same path
		$this->assertRoute('/user/find-users');
		// module name to test
		$this->assertModule('t5');
		// controller to check
		$this->assertController('user');
		// action to check
		$this->assertAction('find-users');
		// do not redirect
		$this->assertNotRedirect();
		
		// queries assertion check
		$this->assertQuery('key');
		$this->assertQuery('timestamp');
		$this->assertQueryContentContains('key','timestamp');
		
	}

	
}