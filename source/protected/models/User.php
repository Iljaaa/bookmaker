<?php

class User extends CActiveRecord
{
	/**
	 *
	 *
	 * @var string
	 */
	private $primaryKey = 'id';


	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}
 
    public function tableName()
    {
        return 'users';
    }

	public static function getAuthedUser (){
		if (yii::app()->user->isGuest) return null;
		return static::findByLogin(yii::app()->user->name);
	}

	/**
	 * Создаем учетную запись пользователя
	 * по данным модели
	 *
	 * @param RegistrationForm $model
	 */
	public static function addUserByRegistrationModel (RegistrationForm $model)
	{
		$u = new User();

		$u->login			= $model->name;
		$u->email			= $model->email;
		$u->password 		= WebUser::hashPassword($model->password);

		$u->status 			= 'worked';
		$u->last_activity 	= time();
		$u->balance = 0;

		$u->save();
	}

    /*
     *
     *
     * @param unknown_type $email
     * @return User
     */
    public static function findByLogin ($login)
    {
    	$crit = new CDbCriteria();
    
    	$crit->addCondition("login = :login");
    	$crit->params = array (':login' => $login);
    
    	return User::model()->find($crit);
    }

	/**
	 *
	 *
	 * @param unknown_type $email
	 * @return User
	 */
	public static function findByEmail ($email)
	{
		$crit = new CDbCriteria();

		$crit->addCondition("email = :email");
		$crit->params = array (':email' => $email);

		return User::model()->find($crit);
	}

	public function getBetsCount () {
		$criteria = new CDbCriteria();
		$criteria->addCondition('uid = :uid');
		$criteria->params = array (':uid' => $this->id);
		return Bet::model()->count($criteria);
	}

	/**
	 * Обновляем баланс пользователя
	 *
	 */
	public function updateBalance ()
	{
		$balance = 0;

		$balances = Balance::findByUser($this->id);
		foreach ($balances as $b) {
			if ($b->isCanceled()) continue;
			$balance = $balance + $b->cost;
		}

		$bets = Bet::findByUser($this->id);
		foreach ($bets as $b){
			if ($b->isCanceled()) continue;
			$balance = $balance - $b->cost;
		}

		$this->balance = $balance;
	}




	/**
	 * Поиск по строке
	 *
	 * @param $searchString
	 * @return array|CActiveRecord|mixed|null
	 *
	public static function search ($searchString)
    {
    	$crit = new CDbCriteria();

    	$crit->addCondition("email LIKE (:search) OR name LIKE (:search) ");
    	$crit->params = array (':search' => '%'.$searchString.'%');

		$users = User::model()->findAll($crit);
    	return $users;
    }

	/**
	 * Создаем учетную запись пользователя
	 * по данным модели
	 *
	 * @param RegistrationForm $model
	 *
	public static function addUserByRegistrationModel (RegistrationForm $model)
    {
    	$u = new User();

    	$u->name			= $model->name;
    	$u->email			= $model->email;
    	$u->password 		= WebUser::hashPassword($model->password);
    	
    	$u->status 			= 'worked';
    	$u->last_activity 	= time();

    	$u->save();
    }


	/**
	 * Урл для аватары
	 *
	 * @return string
	 *
	public function getAvataraUrl ()
    {
    	$extensions = array ('.jpg', '.jpeg', '.gif', ',png');

    	$path = yii::getPathOfAlias('webroot.images.avatars');
		foreach ($extensions as $ext){
			$filename = $this->id.$ext;
			$filePath = $path.'/'.$filename;
			if (file_exists($filePath)){
				return '/images/avatars/'.$filename;
			}
		}
    	
    	$url = '/images/avatara.jpg';
    	return $url;
    }

	/**
	 * Урл иконки пользователя
	 *
	 *
	 * @return string
	 *
	public function getIcoUrl()
    {
    	$extensions = array ('.jpg', '.jpeg', '.gif', ',png');
    	
    	$filename = $this->id.'_ico';
    	$path = yii::getPathOfAlias('webroot.images.avatars');
    	
    	$preFilePath = $path.'/'.$filename;
    	foreach ($extensions as $ext) {
    		$fillPath =  $preFilePath.$ext;
    		if (file_exists($fillPath)){
    			return '/images/avatars/'.$filename.$ext;
    		}
    	}
    	 
    	return '/images/icons/user.png';
    	
    }

	/**
	 * Баги пользователя
	 * которые он опубликовали
	 * или на него завязаные
	 *
	 * @return array|CActiveRecord|mixed|null
	 *
	public function getIssues ()
    {
    	$criteria = new CDbCriteria();
    	$criteria->addCondition("owner_id = :userid OR assigned_to = :userid");
    	$criteria->params = array (':userid' => $this->id);
    	
    	return Issue::model()->findAll($criteria);
    }

	/**
	 * Отрытые баги польвателя
	 *
	 * @param CDbCriteria $criteria
	 * @return array|CActiveRecord|mixed|null
	 *
	public function getOpenIssues (CDbCriteria $criteria = null)
	{
		if ($criteria == null) $criteria = new CDbCriteria();

		$criteria->addCondition("(owner_id = :userid OR assigned_to = :userid) AND steps_id != :active");
		$criteria->params = array (':userid' => $this->id, ':active' => 6);

		if ($criteria->order == '') {
			$criteria->order = 'last_activity DESC';
		}

		return Issue::model()->findAll($criteria);
	}

    /**
     * Проекты в которых учствует пользователь
     * 
     *
    public function getProjects () 
    {

		$criteria = new CDbCriteria();
		$criteria->addCondition("user_id = :id");
		$criteria->params = array (':id' => $this->id);
		
		$projetsIdsData = UserHasProjects::model()->FindAll($criteria);
		$projetsIds = array ();
		foreach ($projetsIdsData as $pid) {
			$projetsIds[] = $pid->project_id;
		}

	    $criteria = new CDbCriteria();
	    // $criteria->addInCondition('id', $projetsIds);
	    $criteria->addCondition('id IN (:projects_ids) OR owner_id = :owner');
	    $criteria->params = array (
		    ':projects_ids' => implode(', ', $projetsIds),
		    ':owner'        => $this->id
	    );


	    $projects = Project::model()->findAll($criteria);
		return $projects;
    }
   
    /**
     * Получаем провекты в которых пользователь являе тся владельцем
     * 
     *
    public function getProjectByOwner ()
    {
    	$projects = array ();
		
		$criteria = new CDbCriteria();
		$criteria->addCondition("owner_id = :id");
		$criteria->params = array (':id' => $this->id);
		
		return Project::model()->findAll($criteria);
    	
    }
    
    public function getOwnedUsers () 
    {
    	$projects = array ();
    	
    	$criteria = new CDbCriteria();
    	$criteria->addCondition("owner_id = :id");
    	$criteria->params = array (':id' => $this->id);
    	
    	return User::model()->findAll($criteria);
    }
    
    
    
    public function isInProject ($projectId)
    {
    	$criteria = new CDbCriteria();
		$criteria->addCondition("user_id = :user_id");
		$criteria->addCondition('project_id = :pid');
		
		$criteria->params = array (':user_id' => $this->id, ':pid' => $projectId);
		
		$cnt = UserHasProjects::model()->count($criteria);
		if ($cnt > 0) return true;
		
		$project = Project::model()->findByPk ($projectId);
		if ($project != null) {
			if ($project->owner_id != $this->id) return true;
		}
    	
    	return false;
    }
    
    /**
     * Обновляем на основании модели
     * 
     * @param unknown_type $model
     *
    public function updateByModel ($model)
    {
    	$data = array (
    			'owner_id'		=> $model->owner_id,
    			'name'			=> $model->name,
    			'code'			=> $model->code,
    			'description'	=> $model->description
    	);
    
    	Yii::app()->db->createCommand()->update($this->tableName(), $data, 'id=:id', array(':id' => $model->id));
    
    }
    
    
    
    public function setNewPassword ($password)
    {
    	$passwordHash = WebUser::hashPassword($password);
    	$data = array (
    			'password'		=> $passwordHash
    	);
    
    	Yii::app()->db->createCommand()->update($this->tableName(), $data, 'id=:id', array(':id' => $this->id));
    
    }

	/**
	 * Обновляем счетчик последней активности
	 *
	 *
	public function updateLastActivity () {
		$this->last_activity = time();
		$this->save ();
	}*/
}
