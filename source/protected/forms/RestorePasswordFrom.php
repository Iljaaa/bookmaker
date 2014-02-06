<?php

class RestorePasswordFrom extends CFormModel
{
	public $email;

	/**
	 * Declares the validation rules.
	 */
	public function rules()
	{
		return array(
			array('email', 'required', 'message' => yii::t('user_registration', 'Email not set')),
			array('email', 'length', 'min'=>1, 'max'=>128 , 'tooShort'=>yii::t('user_registration', 'Email to short'), 'tooLong'=>yii::t('user_registration', 'Email to long')),
			array('email', 'email', 'message' => yii::t('user_registration', 'Email set wrong')),
			array('email', 'validateEmailExist'),
		);
	}

	public function validateEmailExist ($params = array(), $attrs = array())
	{


		$user = User::findByEmail ($this->email);
		if ($user == null) {
			$this->addError('email', yii::t('user_registration', 'User not found'));
		}
	}
} 