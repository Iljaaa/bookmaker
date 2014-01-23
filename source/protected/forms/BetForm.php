<?php
/**
 * Created by PhpStorm.
 * User: ilja
 * Date: 13.12.13
 * Time: 19:00
 */

class BetForm extends CFormModel
{
	public $uid;
	public $matchId;

	public $team1Id;
	public $team2Id;

	public $team1Bet;
	public $team2Bet;

	public function rules()
	{
		return array(
			array ('uid', 'required', 'message' => 'User id not set'),
			array ('uid', 'numerical', 'min' => 1, 'tooSmall' => 'User id not set'),

			array ('matchId', 'required', 'message' => 'Match id not set'),
			array ('matchId', 'numerical', 'min' => 1, 'tooSmall' => 'Match id not sended'),
			array ('matchId', 'verifyMatchId'),

			array('team1Bet', 'verifyTeam1Bet'),
			array('team2Bet', 'verifyTeam2Bet'),

			array('team1Id', 'verifyTeam1Id'),
			array('team2Id', 'verifyTeam2Id'),

			array('team1Id', 'verifyTeam1BetExists'),
			array('team2Id', 'verifyTeam2BetExists'),

			array('matchId', 'verifyBalance'),
		);
	}

	public function verifyMatchId ($attribute,$params)
	{
		if (intVal($this->matchId) == 0){
			$this->addError('matchId', 'Match id not set');
		}

		return false;
	}

	public function verifyTeam1Bet ($attribute,$params)
	{
		$this->team1Bet = trim($this->team1Bet);
		$this->team1Bet = str_replace(',', '.', $this->team1Bet);
		if ($this->team1Bet != '' && floatVal($this->team1Bet) == 0){
			$this->addError('team1Bet','Bet setted wrong');
			return;
		}
	}

	public function verifyTeam2Bet ($attribute,$params)
	{
		$this->team2Bet = trim($this->team2Bet);
		$this->team2Bet = str_replace(',', '.', $this->team2Bet);
		if ($this->team2Bet != '' && floatVal($this->team2Bet) == 0){
			$this->addError('team2Bet','Bet setted wrong');
			return;
		}
	}

	public function verifyTeam1Id ($attribute,$params)
	{
		if (count($this->getErrors('team1Bet')) > 0) return;
		if ($this->team1Bet == '' || floatVal($this->team1Bet) == 0) return;

		if (intVal($this->team1Id) == 0) {
			$this->addError('team1Id', 'Team 1 id not set');
		}
	}

	public function verifyTeam2Id ($attribute,$params)
	{
		if (count($this->getErrors('team2Bet')) > 0) return;
		if ($this->team2Bet == '' || floatVal($this->team2Bet) == 0) return;

		if (intVal($this->team2Id) == 0) {
			$this->addError('team2Id', 'Team 2 id not set');
		}
	}

	public function verifyTeam1BetExists ($attribute,$params)
	{
		if (count($this->getErrors('team1Bet')) > 0) return;
		if ($this->team1Bet == '' || floatVal($this->team1Bet) == 0) return;

		if (count($this->getErrors('team1Id')) > 0) return;

		$bet = Bet::getByUserMatchAndTeam($this->uid, $this->team1Id, $this->matchId);
		if ($bet != null) {
			$this->addError('team1Id', 'Bet on team 1 all ready exists');
		}
	}

	public function verifyTeam2BetExists ($attribute,$params)
	{
		if (count($this->getErrors('team2Bet')) > 0) return;
		if ($this->team2Bet == '' || floatVal($this->team2Bet) == 0) return;

		if (count($this->getErrors('team2Id')) > 0) return;

		$bet = Bet::getByUserMatchAndTeam($this->uid, $this->team2Id, $this->matchId);
		if ($bet != null) {
			$this->addError('team2Id', 'Bet on team 2 all ready exists');
		}
	}


	/**
	 * Проверяем баланс пользователя
	 *
	 * @param $attribute
	 * @param $params
	 */
	public function verifyBalance ($attribute,$params)
	{
		if (count($this->getErrors('team1Bet')) > 0) return;
		if (count($this->getErrors('team2Bet')) > 0) return;

		$sumBet = $this->team1Bet + $this->team2Bet;

		$user = User::getAuthedUser();
		if ($user->balance < $sumBet) {
			$this->addError('team2Id', 'Your balance is insufficient that would make this bet');
		}
	}


	/**
	 * do bet team 1
	 *
	 * @return bool
	 */
	public function createBetTeam1 ()
	{
		if (floatVal($this->team1Bet) == 0) return false;

		$b = new Bet ();
		$b->uid     = $this->uid;
		$b->matchid = $this->matchId;
		$b->teamid  = $this->team1Id;
		$b->cost    = $this->team1Bet;
		$b->time    = time();
		$b->type    = 'bet';
		$b->save ();

		return true;
	}

	/**
	 * do bet team 2
	 *
	 * @return bool
	 */
	public function createBetTeam2 ()
	{
		if (floatVal($this->team2Bet) == 0) return false;

		$b = new Bet ();
		$b->uid     = $this->uid;
		$b->matchid = $this->matchId;
		$b->teamid  = $this->team2Id;
		$b->cost    = $this->team2Bet;
		$b->time    = time();
		$b->type    = 'bet';
		$b->save ();

		return true;
	}

} 