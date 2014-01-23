<?php
/**
 * Created by PhpStorm.
 * User: ilja
 * Date: 14.12.13
 * Time: 11:19
 */

class ResultForm  extends CFormModel
{
	public $matchId;

	public $team1Id;
	public $team2Id;

	public $team1Result;
	public $team2Result;

	public function rules()
	{
		return array(
			array ('matchId', 'required', 'message' => 'Match id not set'),
			array ('matchId', 'numerical', 'min' => 1, 'tooSmall' => 'Match id not sended'),
			array ('matchId', 'verifyMatchId'),

			array('team1Id', 'verifyTeam1Id'),
			array('team2Id', 'verifyTeam2Id'),

			array('team1Result', 'verifyTeam1Result'),
			array('team2Result', 'verifyTeam2Result'),

			array ('matchId', 'validateAllReadyResultPost'),
		);
	}

	public function getTeam1 () {
		return Team::model()->findByPk($this->team1Id);
	}

	public function getTeam2 () {
		return Team::model()->findByPk($this->team2Id);
	}

	public function verifyMatchId ($attribute,$params)
	{
		if (intVal($this->matchId) == 0){
			$this->addError('matchId', 'Match id not set');
			return false;
		}

		return true;
	}

	public function verifyTeam1Id ($attribute,$params)
	{
		if (intVal($this->team1Id) == 0) {
			$this->addError('team1Id', 'Team 1 id not set');
		}
	}

	public function verifyTeam2Id ($attribute,$params)
	{
		if (intVal($this->team2Id) == 0) {
			$this->addError('team2Id', 'Team 2 id not set');
		}
	}

	public function verifyTeam1Result ($attribute,$params)
	{
		$this->team1Result = trim($this->team1Result);

		if ($this->team1Result == '') {
			$this->addError('team1Result', 'Team 1 result not set');
			return ;
		}

		$team1ResultInt = intVal($this->team1Result);
		if ($this->team1Result != strval($team1ResultInt)){
			$this->addError('team1Result','Team 1 result set wrong');
		}
	}

	public function verifyTeam2Result ($attribute,$params)
	{
		$this->team2Result = trim($this->team2Result);

		if ($this->team2Result == '') {
			$this->addError('team2Result', 'Team 2 result not set');
			return ;
		}

		$team1ResultInt = intVal($this->team2Result);
		if ($this->team2Result != strval($team1ResultInt)){
			$this->addError('team2Result','Team 2 result set wrong');
		}
	}

	public function validateAllReadyResultPost ($attribute,$params)
	{
		if (count($this->getErrors('matchId')) > 0) return;
		if (count($this->getErrors('team1Id')) > 0) return;
		if (count($this->getErrors('team2Id')) > 0) return;
		if (count($this->getErrors('team1Result')) > 0) return;
		if (count($this->getErrors('team2Result')) > 0) return;

		$match = Match::model()->findByPk($this->matchId);
		if ($match == null) {
			$this->addError('matchId', 'Team 2 result not set');
			return;
		}

		if ($match->result1 != '' || $match->result2 != '') {
			$this->addError('matchId', 'Result all ready posted');
		}
	}

} 