<?php

/**
 * Core_Controller class.
 * 
 * @extends Zend_Controller_Action
 */
class Core_Controller extends Zend_Controller_Action{

	/**
	 * init function.
	 * 
	 * @access public
	 * @return void
	 */
	public function init($checkLogin = true){
		
		$this->users = new Model_DbTable_Users;
		
		if($checkLogin)
			if(!Zend_Auth::getInstance()->hasIdentity()){
				$this->_redirect('/login');
			}
		
		$this->session = new Zend_Session_Namespace;
	}
	
	/**
	 * setLayout function.
	 * 
	 * @access public
	 * @param mixed $layout
	 * @return void
	 */
	public function layout($layout){
		$this->_helper->layout()->setLayout($layout);

	}
	
	/**
	 * makePagination function.
	 * 
	 * @access public
	 * @param mixed $results
	 * @return $paginator
	 */
	public function makePagination($results){
	
        Zend_Paginator::setDefaultScrollingStyle('Sliding');
        Zend_View_Helper_PaginationControl::setDefaultViewPartial(
            'search/pagination.phtml' 
            //Take note of this, we will be creating this file
        );

		$paginator = Zend_Paginator::factory($results);
        $paginator->setItemCountPerPage(50);
        $paginator->setCurrentPageNumber($this->_getParam('page'));
       
        /**
         * We will be using $this->view->paginator to loop thru in our view ;-)
         */
        return $paginator;


	}
}
