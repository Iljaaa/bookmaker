<?php


class User extends CActiveRecord
{
	/**
	 *
	 *
	 * @var string
	 */
	private $primaryKey = 'id';

	/**
	 *
	 * @param string $className
	 * @return CActiveRecord
	 */
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
     * @return User
	 */
	public static function addUserByRegistrationModel (RegistrationForm $model)
	{
		$u = new User();

		$u->login			= $model->name;
		$u->email			= $model->email;
		$u->password 		= UserIdentity::hashPassword($model->password);

		$u->status 			= 'new';
		$u->last_activity 	= time();
		$u->regisrtime      = time();
		$u->balance = 0;

		$u->save();

        return $u;
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

    /**
     * Количество ставок
     * @return CDbDataReader|mixed|string
     */
    public function getBetsCount () {
		$criteria = new CDbCriteria();
		$criteria->addCondition('uid = :uid');
		$criteria->params = array (':uid' => $this->id);
		return Bet::model()->count($criteria);
	}

    /**
     * ПЕрвая ставка игрока
     * @return array|CActiveRecord|mixed|null
     */
    public function getFirstBet () {
        return Bet::findUserFirstBet($this->id);
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
     * Url for compare registration
     *
     */
    public function getRegistrationCompareUrl () {
	    $date = date('Y-m-d H:i:s', $this->regisrtime);
	    $date = urlencode($date);
        return 'http://'.$_SERVER['HTTP_HOST'].'/users/confirmregistration/?date='.$date.'&id='.$this->id.'&code='.$this->getRegistrationCode();
    }

    /**
     * registratin compare code
     *
     */
    protected function getRegistrationCode (){
        $str = date('dmY', $this->regisrtime)."-".$this->login.'-'.$this->id;
        return md5($str);
    }

    /**
     *
     *
     * @param $code4compare
     * @return bool
     */
    public function compareRegistrationCode ($code4compare) {
        $code = $this->getRegistrationCode();
        return ($code == $code4compare);
    }

    /**
     * Подтверждение регистрации пользователя
     *
     */
    public function registrationConfirm (){
        $this->status = 'worked';
        $this->save();
    }

	/**
	 * Устанавливаем паролья для пользователя
	 *
	 * @param $newPassword
	 */
	public function setPassword ($newPassword) {
		$this->password = UserIdentity::getPassowrdHash($newPassword);
		$this->save ();
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
	 * Обновляем счетчик последней активности
	 *
	 *
	public function updateLastActivity () {
		$this->last_activity = time();
		$this->save ();
	}*/
}
