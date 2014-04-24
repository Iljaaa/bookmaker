<?php
/**
 * Created by PhpStorm.
 * User: ilja
 * Date: 13.12.13
 * Time: 19:38
 */

class Balance extends CActiveRecord
{
	/**
	 * типы балансов
	 */
	// public static BALANCE_IN		= "in";
	// public static BALANCETYPE_PRIZE = "prize";

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
		return 'balances';
	}


	public static function findByUser ($userId, $startTimeStamp = 0, $endTimeStamp = 0)
	{
		$criteria = new CDbCriteria();
        $criteria->addCondition('type = \'in\'');
		$criteria->addCondition('uid = :uid');
		$criteria->params = array (
			':uid'  => $userId
		);

        if ($startTimeStamp > 0) {
            $criteria->addCondition('time > :starttime');
            $criteria->params[':starttime'] = $startTimeStamp;
        }

        if ($endTimeStamp > 0) {
            $criteria->addCondition('time < :endtime');
            $criteria->params[':endtime'] = $endTimeStamp;
        }

		return static::model()->findAll($criteria);
	}

	/**
	 * Баланс для ставки
	 *
	 * @param $betid
	 * @return array|CActiveRecord|mixed|null
	 */
	public static function getByBet ($betid)
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition('betid = :betid');
		$criteria->params = array (
			':betid'  => $betid
		);

		return static::model()->find($criteria);
	}

	/**
	 * Отменен ли
	 *
	 * @return bool
	 */
	public function isCanceled ()
	{
		if ($this->canceltime > 0) return true;
		return false;
	}

	/**
	 * Отменяем запись баланса
	 *
	 * @param bool $updateUser флаг надо ли обновлять баланс пользователя
	 */
	public function cancel ($updateUser = true)
	{
		$this->canceltime = time();
		$this->save ();

		if ($updateUser) {
			$user = $this->getUser();
			if ($user != null) {
				$user->updateBalance();
				$user->save();
			}
		}

	}

	/**
	 *
	 *
	 * @return array|CActiveRecord|mixed|null
	 */
	public function getUser (){
		return User::model()->findByPk($this->uid);
	}

	/**
	 * Форматированое время начала
	 *
	 * @return bool|string
	 */
	public function getTime (){
		return date('d.m.Y H:s', $this->time);
		// return date('Y-m-d H:s', $this->begintime);
	}

} 