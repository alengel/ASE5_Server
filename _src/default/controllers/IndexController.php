<?php

/**
 * IndexController class.
 * 
 * @extends Core_Controller
 */
class IndexController extends Core_Controller{

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
	 * indexAction function.
	 * 
	 * @access public
	 * @return void
	 */
	public function indexAction(){
	
		$key = $this->_getParam('key');
		
		$data = array('flag'=>'NOT POSTED');
		
		// only if posted
		if($this->getRequest()->isPost()){
			if($key == 'bf55e75fa263cbbc2529db49da43cb7f1d370b88'){
				$data = array('flag'=>'success','key'=>$key);
			}
			else{
				$data = array('flag'=>'failed','key'=>$key);
			}
		}
		
		$response = $this->getResponse();
		$response->setHeader('Content-type', 'application/json', true);
		$this->_helper->json->sendJson($data);
		
	}

	
}