<?php

class RegistrationForm extends CFormModel
{
	public $name;
	public $email;
	
	public $password;
	public $password_confirm;

	public $verifyCode;
	
	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('name', 'required', 'message' => 'Name not setted'),
			array('name', 'length', 'min'=>1, 'max'=>128 , 'tooShort'=>'Name not setted', 'tooLong'=>'Name to long'),
				
			array('email', 'required', 'message' => 'Email not setted'),
			array('email', 'length', 'min'=>1, 'max'=>128 , 'tooShort'=>'Email not setted', 'tooLong'=>'Email to long',),
			array('email', 'email', 'message' => 'Email setted wrong'),
			array('email', 'validateEmailExist'),
				
			array('password', 'required', 'message' => 'Password not setted'),
			array('password', 'length', 'min'=>4, 'max'=>128 , 'tooShort'=>'Password must be 4 symbols min', 'tooLong'=>'Password to long'),
			array('password', 'compare', 'compareAttribute'=>'password_confirm', 'message' => 'Passwords do not match'),
				
			array('password_confirm', 'required', 'message' => 'Password confirm not setted'),

			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}
	
	
	public function validateEmailExist ($params = array(), $attrs = array()) 
	{
		$users = User::findByEmail($this->email);
		if (count($users) > 0){
			$this->addError('email', 'Email exist, try other');
		}
	}

	
	public function validateCode ($params = array(), $attrs = array()) 
	{
		$delta = time() - $this->time;
				
		$freeDays = (3600 * 24) * 3;
		
		if ($delta > $freeDays) {
			$this->addError('code', 'Урл устарел');
			return;
		}
		
		$valid = WebUser::validRegistrationKey($this->code, $this->email, $this->owner_id, $this->time);
		if (!$valid) {
			$this->addError('code', 'Урл не верный');
		} 
	}
	
	public function validateOwnerExist ($params = array(), $attrs = array()) 
	{
		$user = User::model()->findByPk ($this->owner_id);
		if ($user == null) {
			$this->addError('owner_id', 'User who send request not found');
		}
	}
	
}