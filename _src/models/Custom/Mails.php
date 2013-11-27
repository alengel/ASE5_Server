<?php

/**
 * Model_Custom_Mails class.
 * 
 * @extends Model_DbTable_Base
 */
class Model_Custom_Mails extends Zend_Mail{	

	CONST T5_INFO 	= 'info@t5server.com';

	/**
	 * _clear function.
	 * 
	 * @access private
	 * @return void
	 */
	private function _clear(){
	
		$this->clearFrom();
		$this->clearSubject();

	}
		
	/**
	 * forgotPassword function.
	 * 
	 * @access public
	 * @param mixed $user
	 * @return void
	 */
	public function forgotPassword($user){
	
	    $this->_clear();

		// if user exists
		if($user){	
			$html = "Dear {$user->first_name},";
			$html .= "<br><br>";
			$html = "You have requested to change the password, please click below link to reset your password.";
			$html .= "<br><br>";
			$html .= "<a href='".HTTP.WWW_ROOT."/t5/user/set-password/x/".sha1($user->id)."'>Click to view Bubble</a>";
			$html .= "<br><br>";
			$html .= "Thanks, <br><br>T5 Support.";

			$this->setBodyHtml($html);
			$this->setFrom(T5_INFO,T5_INFO);
			$this->addTo($user->email, $check->first_name);
			$this->setSubject("T5 - Reset Password");
			$this->send();
		
		}
		
	}
	
	
}

