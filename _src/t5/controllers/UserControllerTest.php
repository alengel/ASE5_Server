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
}