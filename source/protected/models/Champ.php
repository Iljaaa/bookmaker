<?php
/**
 * Created by PhpStorm.
 * User: ilja
 * Date: 18.12.13
 * Time: 13:12
 */

class Champ extends CActiveRecord
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
		return 'champs';
	}

	/**
	 * Находим по имени
	 *
	 * @param $name
	 * @return array|CActiveRecord|CActiveRecord[]|mixed|null
	 */
	public static function findChampByName ($name)
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition ('name = :n');
		$criteria->params = array (':n' => $name);

		return static::model()->find($criteria);
	}

	/**
	 *
	 * @return CDbDataReader|mixed|string
	 */
	public function getMatches ()
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition('champid = :id');
		$criteria->params = array(':id' => $this->id);

		return Match::model()->findAll($criteria);
	}

	/**
	 * Количество матчей в чемпионате
	 *
	 */
	public function getMatchesCount()
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition('champid = :id');
		$criteria->params = array(':id' => $this->id);

		return Match::model()->count($criteria);
	}

} 