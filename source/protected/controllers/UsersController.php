<?php

class UsersController extends Controller
{
	public function actions()
	{
		return array(
			// captcha action renders the CAPTCHA image displayed on the contact page
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
			),
			// page action renders "static" pages stored under 'protected/views/site/pages'
			// They can be accessed via: index.php?r=site/page&view=FileName
			'page'=>array(
				'class'=>'CViewAction',
			),
		);
	}

	public function filters()
	{
		return array(
			'accessControl',
		);
	}

	public function accessRules()
	{
		return array(
			array('deny',
				'actions'=>array('adminlist', 'index'),
				// 'roles'=>array('admin'),
				'users'=>array('?'),
			),
			/*
			array('allow',
				'actions'=>array('create', 'edit','delete'),
				'users'=>array('@'),
			),*/
		);
	}

	/**
	 * Регистрация
	 *
	 */
	public function actionRegistration ()
	{
		$model = new RegistrationForm();

		// проверяем форму регистрации
		if (isset($_POST['RegistrationForm']) && count($_POST['RegistrationForm']))
		{
			$model->attributes = $_POST['RegistrationForm'];

			if ($model->validate()) {
				$user = User::addUserByRegistrationModel ($model);
                if ($user->id == 0) throw new Exception ("User creation error");

                $message = $this->renderPartial('/emails/'.yii::app()->language.'/registration_complite', array (
                    'user' => $user
                ), true);

                Email::sendSystemEMail($user->email, yii::t('user_registration', 'Registration complete'), $message);

				$url = $this->createUrl('/users/registrationcomplite');
				$this->redirect($url);
			}

		}

		$data = array (
			'model'		=> $model
		);

		$this->render('registration', $data);
	}


	/**
	 *
	 */
	public function actionRegistrationcomplite() {
		$this->render ('registratuioncomplite');
	}

    /**
     * FIXIT: make view
     *
     */
    public function actionConfirmregistration ()
    {
	    $data = array (
		    'errorMessage' => '',
		    'goodMessage' => '',
	    );

	    $date = yii::app()->getRequest()->getParam('date', '');
	    if ($date == '') $data['errorMessage'] = yii::t('user_registration', 'Wrong url');

	    $dt = date_parse($date);
	    $var = (
		    isset($dt['error_count'])
	        && $dt['error_count'] == 0
		    && isset($dt['warning_count'])
		    && $dt['warning_count'] == 0
	    );
	    if (!$var){
			$data['errorMessage'] = yii::t('user_registration', 'Wrong url').' [1]';
	    }

	    // проверяем время жизни ссылки
	    $time = mktime ($dt['hour'], $dt['minute'], $dt['second'], $dt['month'], $dt['day'], $dt['year']);
	    $delta = time() - $time;

	    if ($delta > (3600 * 24 * 3)) {
		    $data['errorMessage'] = yii::t('user_registration', 'Url to old');
	    }

        // checking code exists
        $code = yii::app()->getRequest()->getParam('code', '');
        if ($code == '') $data['errorMessage'] = yii::t('user_registration', 'Wrong url').' [2]';
        yii::app()->firephp->log ($code, '$code');

        // chking user
        $userId = yii::app()->getRequest()->getParam('id', 0);
        if ($userId == 0) $data['errorMessage'] = yii::t('user_registration', 'User not found');

        $user = User::model()->findByPk($userId);
        if ($user == null) $data['errorMessage'] = yii::t('user_registration', 'User not found').' [1]';

        // checking code
        if (!$user->compareRegistrationCode($code)){
            $data['errorMessage'] = yii::t('user_registration', 'Wrong url').' [3]';
        }

        // checking user
        if ($user->status != 'new') {
            $data['errorMessage'] = yii::t('user_registration', 'User status wrong status');
        }

        // all ok
        if ($data['errorMessage'] == '') {
            $user->registrationConfirm();
            $data['goodMessage'] = yii::t('user_registration', 'Registration confirmed');
        }

	    $this->render ('confirmregistration', $data);
    }

	/**
	 * Данные пользователя
	 * 
	 * 
	 */
	public function actionIndex()
	{
        if (yii::app()->user->isGuest) {
            throw new Exception('Only auth users');
        }

        $user = yii::app()->user->getDbUser();

        // password change
        $changePasswordModel = new ChangePassword();
        if (isset($_POST['ChangePassword'])){
            $changePasswordModel->attributes = $_POST['ChangePassword'];
            if ($changePasswordModel->validate()){
                $user->setPassword($changePasswordModel->password);
                yii::app()->user->setFlash('user', yii::t('user_personal', 'Password is changed'));
                $this->refresh();
            }
        }

        // email change
        yii::app()->firephp->log ($_POST);
        $changeEmailModel = new ChangeEmail();
        if (isset($_POST['ChangeEmail'])){
            $changeEmailModel->attributes = $_POST['ChangeEmail'];
            if ($changeEmailModel->validate()){
                $user->email = $changeEmailModel->email;
                $user->save ();
                yii::app()->user->setFlash('user', yii::t('user_personal', 'Email is changed'));
                $this->refresh();
            }
        }


        $data = array (
            'user'                  => $user,
            'changePasswordModel'   => $changePasswordModel,
            'changeEmailModel'      => $changeEmailModel
        );

		$this->render ('index', $data);
	}

	/**
	 * Восставновление пароля
	 *
	 */
	public function actionRestorepassword ()
	{
		$model = new RestorePasswordFrom();
		if (isset($_POST['RestorePasswordFrom']) && count($_POST['RestorePasswordFrom']))
		{
			$model->attributes = $_POST['RestorePasswordFrom'];

			if ($model->validate())
			{
				$user = User::findByEmail($model->email);
				if ($user->id == 0) throw new Exception ("User not found");

				$newPassword = UserIdentity::generatePassword();

				$user->setPassword ($newPassword);

				//
				$this->sendRestorePasswordEmail($user, $newPassword);

				yii::app()->user->setFlash('resotrepassword', yii::t('user_registration', 'New password send to email'));

				$url = $this->createUrl('/users/restorepassword');
				$this->redirect($url);
			}

		}


		$this->render ('restorepassword', array(
			'model' => $model
		));
	}

    /**
     * Restore passowrd email
     *
     * @param $user
     * @param $newPassword
     */
    protected function sendRestorePasswordEmail ($user, $newPassword)
	{
		$data = array (
			'user'			=> $user,
			'newPassword'	=> $newPassword
		);

		$message = $this->renderPartial('/emails/'.yii::app()->language.'/restore_password', $data, true);
		
        Email::sendSystemEMail($user->email, yii::t('user_registration', 'Registration password'), $message);
	}

	/**
	 * Для ajax ajax поиска пользователей
	 *
	 *
	 */
	public function actionAjaxUsers ()
	{
		$data = array (
			// 'project'		=> $project,
			'foundUsers'	=> array (),
			'searched'		=> false,
			'errors'		=> array ()
		);

		$search = yii::app()->request->getParam('search', '');
		$modelAddUser = new AddUserForm();

		if ($search != '') {
			$modelAddUser->name = $search;
			if ($modelAddUser->validate()) {
				$users = User::search ($modelAddUser->name);
				foreach ($users as $u) $data['foundUsers'][] = $u;
				$data['searched'] = true;
			}
		}
		else {
			$data['errors'][] = 'Searching string not setted';
		}



		// тип вывода информации
		$display = yii::app()->request->getParam('display', 'json');
		if ($display == 'json'){
			foreach ($data['foundUsers'] as $key => $val){
				$data['foundUsers'][$key] = $val->attributes;
			}

			echo  json_encode($data);
			yii::app()->end();
		}

		if ($display == 'html') {
			$this->renderPartial ('ajax-users', $data);
			yii::app()->end();
		}


		die ('MF die');


	}

	/**
	 * Список матчей инструмент администратора
	 *
	 */
	public function actionAdminList () {

		if (!yii::app()->user->hasRole('admin')) {
			throw new CHttpException('Has no rights');
		}

		$criteria = new CDbCriteria();
		$criteria->order = "id DESC";

		$data = array (
			'users' => User::model()->findAll($criteria)
		);

		$this->render ('adminlist', $data);
	}

}
