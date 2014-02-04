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
			array('name', 'required', 'message' => yii::t('user_registration', 'Name not set')),
			array('name', 'length', 'min'=>1, 'max'=>128 , 'tooShort'=>yii::t('user_registration', 'Name to short'), 'tooLong'=>yii::t('user_registration', 'Name to long')),
				
			array('email', 'required', 'message' => yii::t('user_registration', 'Email not set')),
			array('email', 'length', 'min'=>1, 'max'=>128 , 'tooShort'=>yii::t('user_registration', 'Email to short'), 'tooLong'=>yii::t('user_registration', 'Email to long')),
			array('email', 'email', 'message' => yii::t('user_registration', 'Email set wrong')),
			array('email', 'validateEmailExist'),
				
			array('password', 'required', 'message' => yii::t('user_registration', 'Password not set')),
			array('password', 'length', 'min'=>4, 'max'=>128 , 'tooShort'=>yii::t('user_registration', 'Password must be 4 symbols min'), 'tooLong'=>yii::t('user_registration', 'Password to long')),
			array('password', 'compare', 'compareAttribute'=>'password_confirm', 'message' => yii::t('user_registration', 'Passwords do not match')),
				
			array('password_confirm', 'required', 'message' => yii::t('user_registration', 'Password confirm not set')),

			array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}

    public function attributeLabels () {
        return array (
            'name' => yii::t('user_registration', 'Login'),
            'email' => yii::t('user_registration', 'Email'),
            'password' => yii::t('user_registration', 'Password'),
            'password_confirm' => yii::t('user_registration', ' Password confirm'),
            'verifyCode' => yii::t('user_registration', 'Verify code'),
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
			$this->addError('code', yii::t('user_registration', 'Url to old'));
			return;
		}
		
		$valid = WebUser::validRegistrationKey($this->code, $this->email, $this->owner_id, $this->time);
		if (!$valid) {
			$this->addError('code', yii::t('user_registration', 'Wrong url'));
		} 
	}
	
	public function validateOwnerExist ($params = array(), $attrs = array()) 
	{
		$user = User::model()->findByPk ($this->owner_id);
		if ($user == null) {
			$this->addError('owner_id', yii::t('user_registration', 'User who send request not found'));
		}
	}
	
}