<?php
/**
 * Created by PhpStorm.
 * User: ilja
 * Date: 01.01.14
 * Time: 10:53
 */

class ChampForm extends CFormModel
{
	public $id;
	public $name;
	public $description;
	public $parent;

	public function rules()
	{
		return array(
			array ('id', 'required', 'message' => 'id not setted', 'on' => 'edit'),
			array ('name', 'required', 'message' => 'name not set'),
			array ('description', 'length', 'max' => 2000, 'tooLong' => 'description to long'),
			// array ('description', 'required', 'message' => 'description not send'),
			array ('parent', 'required', 'message' => 'parent not set'),
		);
	}

	/**
	 * Получаем чемпионаты чемпионаты для выбора
	 *
	 */
	public function getChampsForParentSelect ()
	{
		$select = array (0 => 'top level champ');

		$criteria = new CDbCriteria();
		$criteria->order = 'name ASC';
		$criteria->addCondition('parent IS NULL OR parent != 0');
		$criteria->params = array();

		if ($this->id > 0 && $this->getScenario() == "edit"){
			$criteria->addCondition('id <> :id');
			$criteria->params[':id'] = $this->id;
		}

		$champs = Champ::model()->findAll($criteria);
		foreach ($champs as $c){
			$select[$c->id] = $c->name.' ('.$c->id.')';
		}

		return $select;
	}

}