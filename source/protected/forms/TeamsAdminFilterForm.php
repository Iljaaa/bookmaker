<?php
/**
 * Created by PhpStorm.
 * User: ilja
 * Date: 08.01.14
 * Time: 15:19
 */

class TeamsAdminFilterForm extends CFormModel
{

	public $team = 0;
	public $champ = 0;

	public function rules()
	{
		return array(
			array ('team', 'numerical', 'integerOnly' => true, 'message' => 'Name not set'),
			array ('champ', 'numerical', 'integerOnly' => true, 'message' => 'Name not set'),
		);
	}

	/**
	 * Declares attribute labels.
	 */
	public function attributeLabels()
	{
		return array(
			'shortname'=>'Short name',
		);
	}

	/**
	 * Команды для
	 *
	 * @return array
	 */
	public function getTeamsList ()
	{
		$criteria = new CDbCriteria();
		$criteria->order = 'shortname ASC';

		$data = Team::model()->findAll();
		$select = array(0 => '---');
		foreach ($data as $d){
			$name = $d->shortname;
			if ($d->name != '' && $d->name != $d->shortname) {
				$name .= ' ('.$d->name.')';
			}

			$select[$d->id] = $name.' ['.$d->id.']';
		}

		return $select;
	}

	/**
	 * Команды для
	 *
	 * @return array
	 */
	public function getChampsList ()
	{
		$criteria = new CDbCriteria();
		$criteria->order = 'name ASC';

		$data = Champ::model()->findAll();
		$select = array(0 => '---');
		foreach ($data as $d){
			$select[$d->id] = $d->name.' ['.$d->id.']';
		}

		return $select;
	}
} 