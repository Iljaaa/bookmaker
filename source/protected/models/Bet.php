<?php
/**
 * Created by PhpStorm.
 * User: ilja
 * Date: 13.12.13
 * Time: 19:38
 */

class Bet extends CActiveRecord
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
		return 'bets';
	}

    /**
     * Ставки пользователя
     * @param $userId
     * @param null $startTimeStamp
     * @param null $endTimeStamp
     * @return array|CActiveRecord|CActiveRecord[]|mixed|null
     */
    public static function findByUser ($userId, $startTimeStamp = 0, $endTimeStamp = 0)
	{
		$criteria = new CDbCriteria();
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

	public static function findByMatch ($matchId)
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition('matchid = :matchid');
		$criteria->params = array (
			':matchid'  => $matchId
		);

		return static::model()->findAll($criteria);
	}

	public static function findByMatchAntTeam ($matchId, $teamId)
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition('matchid = :matchid');
		$criteria->addCondition('teamid = :teamid');
		$criteria->params = array (
			':matchid'  => $matchId,
			':teamid'   => $teamId
		);

		return static::model()->findAll($criteria);
	}

    /**
     * ПЕрвая ставка польователя
     *
     * @param $userId
     * @return array|CActiveRecord|mixed|null
     */
    public static function findUserFirstBet ($userId){
        $criteria = new CDbCriteria();
        $criteria->order = 'id ASC';
        $criteria->addCondition('canceltime IS NULL OR canceltime = 0');
        $criteria->addCondition('uid = :uid');
        $criteria->params = array (
            ':uid'  => $userId,
        );
        return static::model()->find ($criteria);
    }

	/**
	 * Находим ставку по пользователю матчу и команде
	 *
	 * @param $userId
	 * @param $teamId
	 * @param $matchId
	 */
	public static function getByUserMatchAndTeam ($userId, $teamId, $matchId)
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition("uid = :userid");
		$criteria->addCondition('matchid = :matchid');
		$criteria->addCondition('teamid = :teamid');
		$criteria->params = array (
			':userid'      => $userId,
			':matchid'    => $matchId,
			':teamid'     => $teamId,
		);

		return static::model()->find($criteria);
	}


	/**
	 * Отменен ли матч
	 *
	 * @return bool
	 */
	public function isCanceled ()
	{
		if ($this->canceltime > 0) return true;
		return false;
	}

	/**
	 * Отменяем запись ставки
	 *
	 * @param bool $cancelBalance флаг надо ли отменить баланс
	 * @param bool $updateUser флаг надо ли обновлять баланс пользвателя
	 */
	public function cancel ($cancelBalance = true, $updateUser = true)
	{
		$this->canceltime = time ();
		$this->save();

		//
		if ($cancelBalance){
			$balance = $this->getBalance();
			if ($balance != null) $balance->cancel(false);
		}

		//
		if ($updateUser) {
			$user = $this->getUser();
			if ($user != null) {
				$user->updateBalance();
				$user->save();
			}
		}
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

	/**
	 * Матч
	 *
	 * @return array|CActiveRecord|mixed|null
	 */
	public function getMatch (){
		return Match::model()->findByPk($this->matchid);
	}

	/**
	 *
	 * @return array|CActiveRecord|mixed|null
	 */
	public function getTeam (){
		return Team::model()->findByPk($this->teamid);
	}

	/**
	 * Баланс для ставки
	 *
	 * @return array|CActiveRecord|mixed|null
	 */
	public function getBalance (){
		return Balance::getByBet ($this->id);
	}

	/**
	 * Владелец ставки
	 *
	 * @return array|CActiveRecord|mixed|null
	 */
	public function getUser (){
		return User::model()->findByPk($this->uid);
	}
} 