<?php
/**
 * Created by PhpStorm.
 * User: ilja
 * Date: 2/8/14
 * Time: 3:33 PM
 */

class ChangePassword extends CFormModel {

    public $old_password;
    public $password;
    public $password_confirm;

    /**
     * Declares the validation rules.
     */
    public function rules()
    {
        return array(
            array('old_password', 'required', 'message' => yii::t('user_personal', 'Old password not set')),
            array('old_password', 'length', 'min'=>0, 'tooShort'=>yii::t('user_personal', 'Old password not set')),
            array('old_password', 'validateEmailExist'),

            array('password', 'required', 'message' => yii::t('user_personal', 'New password not set')),
            array('password', 'length',
                'min'=>4, 'max'=>128 ,
                'tooShort'=>yii::t('user_personal', 'Password must be 4 symbols min'), 'tooLong'=>yii::t('user_personal', 'Password to long')),
            array('password', 'compare', 'compareAttribute'=>'password_confirm', 'message' => yii::t('user_personal', 'Passwords do not match')),

            array('password_confirm', 'required', 'message' => yii::t('user_personal', 'Password confirm not set')),
            array('password_confirm', 'length', 'min'=>0, 'tooShort'=>yii::t('user_personal', 'Password confirm not set')),
        );
    }

    public function validateEmailExist ($params = array(), $attrs = array())
    {
        if (yii::app()->user->isGuest) {
            $this->addError('old_password', yii::t('user_personal', 'Auth required for password change'));
            return;
        }

        $user = yii::app()->user->getDbUser();
        if ($user == null){
            $this->addError('old_password', yii::t('user_personal', 'Auth required for password change'));
            return;
        }

        $oldPasswordHash = UserIdentity::getPassowrdHash($this->old_password);
        if ($user->password != $oldPasswordHash) {
            $this->addError('old_password', yii::t('user_personal', 'The entered password does not match the current'));
        }
    }
}