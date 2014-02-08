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

}