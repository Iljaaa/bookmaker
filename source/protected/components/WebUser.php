<?php

class WebUser extends CWebUser
{
	private $dbUser = null;

	public function hasRole ($role)
	{
		if ($this->isGuest) return false;

		$dbUser = $this->getDbUser();
		if ($dbUser == null) return false;

		if ($role == $dbUser->role) return true;

		return false;
	}

	/**
	 * Получаем хеш пароля
	 *
	 * @param string $password
	 * @return string
	 */
	public static function hashPassword ($password) {
		return md5($password);
	}

	/**
	 * Возвращает пользователя загруженого из БД
	 *
	 * @return User
	 */
	public function getDbUser ()
	{
		if ($this->dbUser == null) {
			$this->loadUser();
		}

		return $this->dbUser;
	}

	/**
	 * Загружаем пользователя
	 *
	 */
	private function loadUser ()
	{
		$user = User::findByLogin($this->name);
		$this->dbUser = $user;
	}

	/*
	public function checkAccess($operation, $params = Array (), $allowCaching = true)
	{
		$controllerRoles = yii::app()->controller->accessRules();
		$action = yii::app()->controller->getAction()->getId();

		if ($this->dbUser == null) {
			$this->loadUser();
		}

		$userRole = $this->dbUser->type;

		foreach ($controllerRoles as $role)
		{
			// ищим совпадение по роли пользователя
			$foundRole = false;
			if (isset($role['roles']) && is_array($role['roles'])) {
				foreach ($role['roles'] as $r){
					if ($r == $userRole) {
						$foundRole = true;
						break;
					}
				}
			}
				
			// ищим совпадение по акшн методу
			$foundAction = false;
			if ($foundRole)
			{
				if (isset($role['actions']) && is_array($role['actions'])) {
					foreach ($role['actions'] as $a) {
						$foundAction = true;
						break;
					}
				}
			}
				
			if ($foundRole && $foundAction && $role[0] == 'deny') {
				return true;
			}
				
		}

		return false;
	}*/

	/**
	 * Генерируем url для доступа пользователя к регистрации
	 *
	 * @param unknown_type $email
	 * @param unknown_type $owner
	 * @param unknown_type $time
	 *
	public static function generateRegistratonUrl ($email, $owner, $time)
	{
		$url = '/users/registration/';
	
		$data = array (
			'email' => $email,
			'owner' => $owner,
			'code'	=> self::generateRegistrationKey($email, $owner, $time),
			'time' 	=> $time
		);
		
		return 'http://'.$_SERVER['HTTP_HOST'].yii::app()->controller->createUrl ($url, $data);
	}

	/**
	 * Генерируем ключ регистрации
	 *
	 * @return string
	 *
	public static function generateRegistrationKey ($email, $owner, $time)
	{
		$keyString = $email.' ( . ) ( . ) '.$owner.' ( . ) ( . ) '.$time;
		return md5 ($keyString);
	}

	/**
	 * проверяем вилидность ключа регистрации
	 *
	 * @return bool
	 *
	public static function validRegistrationKey ($code, $email, $owner, $time)
	{
		$genCode = self::generateRegistrationKey($email, $owner, $time);
		
		
		if ($genCode != $code){
			return false;
		}
		
		$delta = time() - $time;
		$freeDays = (3600 * 24) * 3;
		
		if ($delta > $freeDays) {
			return false;
		}
		
		
		return true;
	}

	/**
	 * Генерируем пароль
	 *
	 * @param int $password_lenght
	 * @return string
	 *
	public static function generatePassword ($password_lenght = 10)
    {
	    $lethers = array(
	    "a","b","c","d","e","f","g","h","j","k","l","m","n","o","p","r","q","s","t","y","v","w","x","y","z",
	    "A","B","C","D","E","F","G","H","J","K","L","M","N","O","P","R","Q","S","T","Y","V","W","X","Y","Z",
	    "1","2","3","4","5","6","7","8","9","0"
	    );
	   
	    $new_pass = "";
	    for($i = 0; $i < $password_lenght; $i++)
		{
		    $char = $lethers[array_rand($lethers, 1)];
		    $new_pass .= $char;
	    }
   
    return $new_pass;
    }
	
	
	/**
	 * Перегрузка метода
	 *
	 * (non-PHPdoc)
	 * @see CWebUser::getId()
	 *
	public function getId()
	{
		if ($this->dbUser == null) {
			$this->loadUser();
		}

		return $this->dbUser->id;
	}*/




}