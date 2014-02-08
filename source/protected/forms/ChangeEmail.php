<?php

class ChangeEmail extends CFormModel
{
    public $email;

    public function rules()
    {
        return array(
            array('email', 'required', 'message' => yii::t('user_personal', 'Email not set')),
            array('email', 'length', 'min'=>0, 'tooShort'=>yii::t('user_personal', 'Email not set')),
            array('email', 'email', 'message' => yii::t('user_registration', 'Email set wrong')),
            array('email', 'validateEmailExist'),
        );
    }

    public function validateEmailExist ($params = array(), $attrs = array())
    {
        if (yii::app()->user->isGuest) {
            $this->addError('email', yii::t('user_personal', 'Auth required for password change'));
            return;
        }

        $user = yii::app()->user->getDbUser();
        if ($user == null){
            $this->addError('email', yii::t('user_personal', 'Auth required for password change'));
            return;
        }

        $user4Email = User::findByEmail($this->email);
        if ($user4Email != null) {
            if ($user4Email->id != $user->id){
                $this->addError('email', yii::t('user_personal', 'The entered email all ready exists'));
            }
        }
    }
} 