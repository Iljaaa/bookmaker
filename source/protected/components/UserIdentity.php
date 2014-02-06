<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
		$users=array(
			// username => password
			'demo'=>'demo',
			'admin'=>'admin',
		);

		if ($this->username == '' || $this->password == '') {
			$this->errorCode=self::ERROR_USERNAME_INVALID;
			return false;
		}

		$user = User::model()->findByLogin($this->username);
		if ($user == null){
			$this->errorCode=self::ERROR_USERNAME_INVALID;
			return false;
		}

		$passwordHash = static::getPassowrdHash($this->password);
		if ($passwordHash != $user->password) {
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
			return false;
		}

		$this->errorCode = self::ERROR_NONE;
		return true;
	}


	public static function getPassowrdHash ($password){
		return md5($password);
	}

	/**
	 * Генерация пароля
	 *
	 * @param int $password_length
	 * @return string
	 */
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

}