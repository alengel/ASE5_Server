<?php
/**
 * ErrorController class.
 * 
 * @extends Zend_Controller_Action
 */
class ErrorController extends Zend_Controller_Action{

   /**
     * Error Action
	 *
	 * Render the required Error page and expected information for debuging
     * 
	 * @param null
     * @return void
     */
    public function errorAction(){
        
		// show friendly page is debug is disabled, check index.php
		if(!DEBUG)
			$this->render('friendly404');
		
		//  get the error handler object
		$errors = $this->_getParam('error_handler');
        
		// check the error type
        switch ($errors->type) { 
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_CONTROLLER:
            case Zend_Controller_Plugin_ErrorHandler::EXCEPTION_NO_ACTION:
        
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $this->view->message = 'Page not found';
                break;
            default:
                // application error 
                $this->getResponse()->setHttpResponseCode(500);
                $this->view->message = 'Application error';
                break;
        }
        
        $this->view->exception = $errors->exception;
        $this->view->request   = $errors->request;
    }


}

