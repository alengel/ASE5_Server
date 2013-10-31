<?php

/**
 * Application bootstrap
 *  
 */
class Bootstrap extends Zend_Application_Bootstrap_Bootstrap{

    /**
     * Bootstrap autoloader for application resources
     * 
     * @return Zend_Application_Module_Autoloader
     */
    protected function _initAutoload(){
	
        $autoloader = new Zend_Application_Module_Autoloader(array(
            'namespace' => '',
            'basePath'  => APPLICATION_PATH,
        	'resourceTypes' => array(
			
				'model' => array( 'path'=> 'models/', 'namespace' => 'Model_'),
				'core' => array( 'path'=> 'core/', 'namespace' => 'Core_')
			
			)
        ));
        
      return $autoloader;
    }

    /**
     * Bootstrap the view doctype
     * 
     * @return void
     */
    protected function _initDoctype(){
	
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
    }
}
