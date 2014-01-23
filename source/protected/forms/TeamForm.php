<?php

class TeamForm extends CFormModel
{
	public $name;
	public $shortname;

	public function rules()
	{
		return array(
			array ('name', 'required', 'message' => 'Name not set'),
			array ('shortname', 'required', 'message' => 'short name not set'),
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

} 