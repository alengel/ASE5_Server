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
<<<<<<< HEAD
		$this->locations = new Model_DbTable_Locations;
		$this->users_reviews = new Model_DbTable_Users_Reviews;
=======
>>>>>>> d0ee01d684781a2ac139deb58989c19f2b43954a
		$this->mails = new Model_Custom_Mails;
		
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
