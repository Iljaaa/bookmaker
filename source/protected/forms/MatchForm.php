<?php

class MatchForm extends CFormModel
{
	public $team1;
	public $team2;
	public $champ;
	public $factor1 = 1;
	public $factor2 = 1;
	public $begindate;
	public $begintimehours;
	public $begintimeminets;

	public function rules()
	{
		return array(
			array ('team1', 'required', 'message' => 'Team 1 not setted'),
			array ('team1', 'numerical', 'min' => 1,  'tooSmall' => 'Team 1 not selected'),
			array ('team1', 'validateMathExist', 'on' => 'create'),

			array ('team2', 'required', 'message' => 'Team 2 not setted'),
			array ('team2', 'numerical', 'min' => 1, 'tooSmall' => 'Team 2 not selected'),
			array ('team2', 'verifyTeams'),

			array ('champ', 'required', 'message' => 'Champ not setted'),
			array ('champ', 'numerical', 'min' => 1, 'tooSmall' => 'Champ not selected'),

			array ('factor1', 'required', 'message' => 'Factor 1 not setted'),
			array ('factor1', 'validateFactor1'),
			array ('factor2', 'required', 'message' => 'Factor 2 not setted'),
			array ('factor2', 'validateFactor2'),

			array ('begindate', 'required', 'message' => 'Start date not setted'),
			array ('begindate', 'validateDate'),

			array ('begintimehours', 'required', 'message' => 'Start time not setted'),
			array ('begintimeminets', 'required', 'message' => 'Start time not setted'),
			// array ('begintimeminets', 'validateTime'),
			// array('verifyCode', 'captcha', 'allowEmpty'=>!CCaptcha::checkRequirements()),
		);
	}

	public function attributeLabels()
	{
		return array(
			'verifyCode'=>'Verification Code',
		);
	}

	/**
	 * Расчитываем финальное время
	 *
	 */
	public function getFinaleTime (){
		$this->begindate = trim($this->begindate);
		$dt = strtotime ($this->begindate);

		return $dt + intVAl($this->begintimehours) + intVAl($this->begintimeminets);
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
			if ($d->name != '') {
				$name .= ' ('.$d->name.')';
			}

			$select[$d->id] = $name.' ['.$d->id.']';
		}

		return $select;
	}

	/**
	 * Vfnxb для
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

	public function verifyTeams ($attribute,$params) {
		if ($this->team1 == $this->team2){
			$this->addError('team2','Can\'t play with him self');
		}
	}

	public function validateFactor1 ($attribute,$params) {
		$this->factor1 = trim($this->factor1);
		$this->factor1 = str_replace(',', '.', $this->factor1);
		if ($this->factor1 == ''){
			$this->addError('factor1','Factor 1 not setted');
			return;
		}

		$this->factor1 = floatVAl($this->factor1);
		if ($this->factor1 <= 0){
			$this->addError('factor1','Factor 1 setted wrong');
		}
	}

	public function validateFactor2 ($attribute,$params) {
		$this->factor2 = trim($this->factor2);
		$this->factor2 = str_replace(',', '.', $this->factor2);

		if ($this->factor2 == ''){
			$this->addError('factor2','Factor 2 not setted');
			return;
		}

		$this->factor2 = floatVAl($this->factor2);
		if ($this->factor2 <= 0){
			$this->addError('factor2','Factor 2 setted wrong');
		}
	}

	public function validateDate ($attribute,$params) {
		$this->begindate = trim($this->begindate);
		$dt = strtotime ($this->begindate);

		$dtStr = date('d.m.Y', $dt);
		if ($this->begindate != $dtStr) {
			$this->addError('begindate','Date setted wrong');
		}
	}

	public function validateTime ($attribute,$params)
	{
		if (isset($this->errors['begintime']) && count($this->errors['begintime']) > 0){
			return;
		}

		$finishTime = $this->getFinaleTime();
		if ($finishTime <= time()) {
			$this->addError('begindate','Begin date can\'t be in the past');
		}
	}

	/**
	 * Проверка на существование такого матча
	 *
	 * @param $attribute
	 * @param $params
	 */
	public function validateMathExist ($attribute,$params)
	{
		$match = Match::findMathByTeamsAndTime($this->team1, $this->team2, $this->getFinaleTime());
		if ($match != null){
			$this->addError('team1', 'Such a match exists');
		}
	}

}