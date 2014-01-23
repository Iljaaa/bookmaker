<?php


class Team extends CActiveRecord
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
        return 'teams';
    }

	/**
	 * Находи команды по имени
	 *
	 * @param $name
	 * @return array|CActiveRecord|CActiveRecord[]|mixed|null
	 */
	public static function findTeamByName ($name)
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition ('name = :n OR shortname = :n');
		$criteria->params = array (':n' => $name);

		return static::model()->find($criteria);
	}


	/**
	 * Уменьшеное изображение с лого
	 *
	 */
	public function getIcoUrl() {
		$path =  YiiBase::getPathOfAlias('application').'/../images/teams/'.$this->id.'.jpg';
		if (file_exists($path)) {
			return '/images/teams/'.$this->id.'.jpg';
		}
		return '/images/teams/00.jpg';
	}

	/**
	 * Урл к лого
	 *
	 * @return string
	 */
	public function getImageUrl() {
		return $this->getIcoUrl();
	}

	/**
	 * Определяем винрейт команды
	 * на основании съиграных ей матчей ко всем
	 *
	 * @param $beforeDate начиная с даты
	 * @param int $offset
	 * @param int $limit
	 * @return float
	 */
	public function getTeamWinRate ($beforeDate, $offset = 0, $limit = 5)
	{
		$matches = $this->getLastFinishedMatchesBeforeDate ($beforeDate, $offset, $limit);

		$allCount = count($matches);
		if ($allCount == 0) return 0;

		$winMatches = 0;
		foreach ($matches as $m){
			$winTeamId = $m->getWinnerId();
			if ($this->id == $winTeamId) $winMatches++;
		}

		return $winMatches/$allCount;
	}

	/**
	 * Последние сыграные матчи команды
	 *
	 * @param int $offset
	 * @param int $limit
	 * @return array|CActiveRecord|CActiveRecord[]|mixed|null
	 */
	public function getLastFinishedMatches ($offset = 0, $limit = 5)
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition('team1 = :teamId OR team2 = :teamId');
		$criteria->addCondition('result1 IS NOT NULL AND result2 IS NOT NULL');
		$criteria->params = array (
			':teamId' => $this->id
		);
		$criteria->offset = $offset;
		$criteria->limit = $limit;

		return Match::model()->findAll($criteria);
	}

	/**
	 * Последние сыграные матчи команды
	 *
	 * @param int $offset
	 * @param int $limit
	 * @return array|CActiveRecord|CActiveRecord[]|mixed|null
	 */
	public function getLastFinishedMatchesBeforeDate ($beforeDate, $offset = 0, $limit = 5)
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition('team1 = :teamId OR team2 = :teamId');
		$criteria->addCondition('begintime < :bg');
		$criteria->addCondition('result1 > 0 OR result2 > 0');
		$criteria->params = array (
			':teamId'       => $this->id,
			':bg'    => $beforeDate
		);
		$criteria->offset = $offset;
		$criteria->limit = $limit;
		$criteria->order = "begintime DESC";

		return Match::model()->findAll($criteria);
	}

	/**
	 * Количество всех матчей
	 *
	 * @param null $beforeDate
	 * @return CDbDataReader|mixed|string
	 */
	public function getAllGamesCount($beforeDate = null)
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition('team1 = :teamId OR team2 = :teamId');
		$criteria->addCondition('result1 IS NOT NULL AND result2 IS NOT NULL');
		$criteria->params =  array (
			':teamId'       => $this->id
		);

		if ($beforeDate > 0){
			$criteria->addCondition('begintime < :begindate');
			$criteria->params[':begindate'] = $beforeDate;
		}

		return Match::model()->count($criteria);
	}

	/**
	 * Количество выиграных матчей
	 *
	 * @param null $beforeDate
	 * @return CDbDataReader|mixed|string
	 */
	public function getAllWinGamesCount($beforeDate = null)
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition('(team1 = :teamId AND result1 > result2) OR (team2 = :teamId AND result2 > result1)');
		$criteria->addCondition('result1 IS NOT NULL AND result2 IS NOT NULL');
		$criteria->params =  array (
			':teamId'       => $this->id
		);

		if ($beforeDate > 0){
			$criteria->addCondition('begintime < :begindate');
			$criteria->params[':begindate'] = $beforeDate;
		}

		/*
		$criteria->addCondition('result1 IS NOT NULL AND result2 IS NOT NULL');
		$criteria->params = array (
			':teamId'       => $this->id,
			':begindate'    => $beforeDate
		);
		$criteria->offset = $offset;
		$criteria->limit = $limit;*/

		return Match::model()->count($criteria);
	}
}
