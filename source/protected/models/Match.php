<?php


class Match extends CActiveRecord
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
		return 'matches';
	}

	/**
	 * Извлекаем матчи для главной страницы
	 *
	 * @return array|CActiveRecord|CActiveRecord[]|mixed|null
	 */
	public static function findForIndexPage ()
	{
		$criteria = new CDbCriteria();
		$criteria->order = 'begintime DESC';
		return static::model()->findAll($criteria);
	}

	/**
	 * Находим матч по командам и времени
	 *
	 * @param $team1
	 * @param $team2
	 * @param $timeStamp
	 * @return array|CActiveRecord|mixed|null
	 */
	public static function findMathByTeamsAndTime ($team1, $team2, $timeStamp)
	{
		$criteria = new CDbCriteria();

		$criteria->addCondition('team1 = :t1');
		$criteria->addCondition('team2 = :t2');
		$criteria->addCondition('begintime BETWEEN :dt1 AND :dt2');
		$criteria->params = array (
			':t1'   => $team1,
			':t2'   => $team2,
			':dt1'   => $timeStamp - (1800),
			':dt2'   => $timeStamp + (1800)
		);

		$math = Match::model()->find($criteria);
		if ($math != null) return $math;

		$criteria->params = array (
			':t1'   => $team2,
			':t2'   => $team1,
			':dt1'   => $timeStamp - (1800),
			':dt2'   => $timeStamp + (1800)
		);

		return Match::model()->find($criteria);
	}

	/**
	 * Находим матчи между командами
	 * до определенного времени
	 *
	 * @param $team1
	 * @param $team2
	 * @param null $beforeDate
	 * @return array|CActiveRecord|CActiveRecord[]|mixed|null
	 */
	public static function findMatchesBetweenTeams ($team1, $team2, $beforeDate = null)
	{
		$criteria = new CDbCriteria();

		$criteria->addCondition('(team1 = :t1 AND team2 = :t2) OR (team1 = :t2 AND team2 = :t1)');
		$criteria->addCondition('canceltime IS NULL');
		$criteria->params = array (
			':t1'   => $team1,
			':t2'   => $team2,
		);

		if ($beforeDate > 0){
			$criteria->addCondition('begintime < :bg');
			$criteria->params[':bg'] = $beforeDate;
		}

		return Match::model()->findAll($criteria);
	}

	/**
	 * Матчи открытые для ставок
	 *
	 */
	public static function matchesForBet ()
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition('begintime > :now');
		$criteria->addCondition('factor1 > 1 AND factor2 > 1');
		$criteria->addCondition('factor1 <> factor2');
		$criteria->addCondition('result1 IS NULL AND result2 IS NULL');
		$criteria->addCondition('resulttime IS NULL');
		$criteria->addCondition('canceltime IS NULL');

		$criteria->params = array (
			':now'  => time()
		);
		$criteria->order = 'begintime ASC';

		return Match::model()->findAll($criteria);
	}

	/**
	 * Адресс станицы матча
	 *
	 */
	public function getUrl() {
		return '/match/'.$this->id;
	}

	/**
	 *
	 *
	 */
	public function getTeam1 () {
		return Team::model()->findByPk($this->team1);
	}

	/**
	 *
	 *
	 */
	public function getTeam2 () {
		return Team::model()->findByPk($this->team2);
	}

	/**
	 * Форматированое время начала
	 *
	 * @return bool|string
	 */
	public function getBeginTime (){
		return date('d.m.Y H:i', $this->begintime);
		// return date('Y-m-d H:s', $this->begintime);
	}

	/**
	 * Проходит ли match сейчас
	 *
	 */
	public function isGo ()
	{
		if (!$this->isFinish())
		{
			if (time() > $this->begintime){
				return true;
			}
		}

		return false;
	}

	/**
	 * Завершон ли матч
	 *
	 */
	public function isFinish ()
	{
		if (intVal($this->result1) > 0 || intVal($this->result2) > 0){
			return true;
		}

		return false;
	}

	public function cancel ()
	{
		if (!$this->canCanceled()) return false;

		$this->canceltime = time();
		$this->save();

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
	 * Можно ли отменить матч
	 *
	 */
	public function canCanceled (&$reason = '')
	{
		if ($this->getBetsCount() > 0) {
			$reason = 'Match have bets';
			return false;
		}

		return true;
	}

	public function getWinner ()
	{
		if (!$this->isFinish()) return null;

		$winnerId = $this->getWinnerId();
		if ($winnerId == 0) return null;

		return Team::model()->findByPk($winnerId);
	}

	/**
	 * @return bool
	 */
	public function delete ()
	{
		if (!$this->canDelete()) return false;

		return parent::delete();
	}

	/**
	 * Можно ли удалить матч
	 *
	 * @return bool
	 */
	public function canDelete ()
	{
		if ($this->getBetsCount() > 0) {
			return false;
		}

		return true;
	}

	/**
	 * Идентификатор команды победятеля
	 *
	 */
	public function getWinnerId () {
		if (!$this->isFinish()) return 0;

		if ($this->result1 > $this->result2) return $this->team1;
		else return $this->team2;
	}

	/**
	 * Количество ставок сделаных на матч
	 *
	 * @return int
	 */
	public function getBetsCount ()
	{
		$criteria = new CDbCriteria();
		$criteria->addCondition('matchid = :matchid');
		$criteria->params = array (':matchid' => $this->id);

		return Bet::model()->count($criteria);
	}

	/**
	 *
	 * @return null
	 */
	public function getChamp () {
		if ($this->champid == 0) return null;
		return Champ::model()->findByPk($this->champid);
	}

	/**
	 * Оставшееся время
	 *
	 */
	public function getRemainingTime ()
	{
		if ($this->begintime < time()) return 0;
		return $this->begintime - time();
	}

	/**
	 * Строка сколько времени осталось до наала матча
	 *
	 * @return string
	 */
	public function getRemainingTimeString ()
	{
		$remaining = $this->getRemainingTime();

		$days = $remaining / (3600 * 24);
		if ($days > 1){
			return number_format($days, 2).' days';
		}

		$hours = $remaining / 3600;
		if ($hours > 1) {
			return number_format($hours, 2).' hour';
		}

		$min = $remaining / 60;
		if ($min > 1) {
			return number_format($min, 0).' minutes';
		}

		return 'all most start';
	}

	/**
	 * Устанавливаем результат матча
	 *
	 */
	public function setResult ($resultTeam1, $resultTeam2)
	{
		$this->result1 = $resultTeam1;
		$this->result2 = $resultTeam2;
		$this->resulttime = time();
		$this->save();

		Yii::log("Match ".$this->id." updating", "bets", get_class($this));

		// победиель
		$winerId = $this->getWinnerId();
		$winFactor = $this->factor1;
		if ($this->result2 > $this->result1) $winFactor = $this->factor2;

		$log = array();

		// выигравшие ставки
		$bets = Bet::findByMatchAntTeam($this->id, $winerId);
		foreach ($bets as $b)
		{
			Yii::log("Win bet ".$b->id, "bets, cost : ".$b->cost, get_class($this));

			$win = $b->cost * $winFactor;

			//
			$balance = Balance::getByBet($b->id);
			if ($balance != null){
				Yii::log('Balance '.$balance->id.' for bet '.$b->id.' all ready exists. skipped.', "bets", get_class($this));
				continue;
			}

			$balance = new Balance();
			$balance->uid   = $b->uid;
			$balance->betid = $b->id;
			$balance->cost  = $win;
			$balance->time  = time();
			$balance->type = 'prize';
			$balance->save ();

			Yii::log('New balance '.$balance->id.' cretated for bet '.$b->id.', prize :'.$win, "bets", get_class($this));

			// обновляем баланс пользователя
			$user = $balance->getUser ();
			if ($user == null){
				Yii::log('User '.$user->id.' not found. skipped.', "bets", get_class($this));
				continue;
			}

			Yii::log('User '.$user->id.' balance updated.', "bets", get_class($this));
			$user->updateBalance();
			$user->save ();
		}

	}


	/**
	 * Обновляем данные на основании формы
	 *
	 * @param MatchForm $model
	 * @return void
	 */
	public function updateByModel (MatchForm $model)
	{
		$this->team1        = $model->team1;
		$this->team2        = $model->team2;
		$this->factor1      = $model->factor1;
		$this->factor2      = $model->factor2;
		$this->champid      = $model->champ;
		// $this->result1
		// $this->result2
		$this->begintime    = $model->getFinaleTime();

	}


	/**
	 * Готовим предварительную информацию для расчета матчя
	 *
	 */
	public function getCalckData ()
	{
		$data = array (
			'matches' => array (
				$this->team2    => 0,
				$this->team1    => 0,
				'count'         => 0,
			),
			'matchesResult' => array (
				$this->team2    => 0,
				$this->team1    => 0,
			),
		);

		// прямые матчи
		$matches = Match::findMatchesBetweenTeams($this->team1, $this->team2, $this->begintime);
		if (count($matches) > 0)
		{
			foreach ($matches as $m){
				if (!$m->isFinish()) continue;

				$winer = $m->getWinnerId();

				if (!isset($data['matches'][$winer])) $data['matches'][$winer] = 0;
				$data['matches'][$winer]++;
				$data['matches']['count']++;
			}
		}

		if ($data['matches']['count'] > 0) {
			$data['matchesResult'][$this->team1] = ($data['matches'][$this->team1] / $data['matches']['count']);
			$data['matchesResult'][$this->team2] = ($data['matches'][$this->team2] / $data['matches']['count']);
		}
		else {
			$data['matchesResult'][$this->team1] = $data['matchesResult'][$this->team2] = 0.5;
		}

		// стыковочные матчи
		$comparedMatches = $this->getCompareMathes();
		$data['comparedMatches'] = $comparedMatches;
		$data['comparedMatchesData'] = array ();

		// добавляем данные по победам для стыковочных матчей
		foreach ($data['comparedMatches'] as $cmKey => $cm)
		{
			// расширяем информацию о матче
			$info = $this->getTeamsMatchesInfo($cm[0], $cm[1]);

			$matchKey = $cm[0].'-'.$cm[1];
			$matchData = array (
				'count' => $info['matchesCount'],
				$cm[0] => $info[$cm[0]],
				$cm[1] => $info[$cm[1]],
			);

			$data['comparedMatchesData'][$matchKey] = $matchData;

			//
			$info = $this->getTeamsMatchesInfo($cm[1], $cm[2]);

			$matchKey = $cm[1].'-'.$cm[2];
			$matchData = array (
				'count' => $info['matchesCount'],
				$cm[1] => $info[$cm[1]],
				$cm[2] => $info[$cm[2]],
			);

			$data['comparedMatchesData'][$matchKey] = $matchData;;
		}

		// расчитываем соотношения между командами на основании стыковых матчей
		$data['comparedMatchesResult'] = $this->calckComparedMatchedInfo($data);

		// сумарный процент для стыковочных матчей
		$data['comparedMatchesSummaryResult'] = $this->calckComparedMatchesSummeryInfo($data);

		// коэффициент
		$data['koef'] = $this->calckKoef($data);

		return $data;
	}

	/**
	 * Формируем информацию о матчах между командами
	 *
	 */
	protected function getTeamsMatchesInfo ($team1Id, $team2Id)
	{
		$data = array (
			'matchesCount'  => 0,
			$team1Id        => 0,
			$team2Id        => 0
		);

		$matches = Match::findMatchesBetweenTeams($team1Id, $team2Id, $this->begintime);
		//$data['matchesCount'] = count($matches);

		foreach ($matches as $m) {
			if (!$m->isFinish()) continue;
			$winnerId = $m->getWinnerId();
			$data[$winnerId]++;
			$data['matchesCount']++;
		}

		return $data;
	}

	/**
	 * Получаем стыковые матчи для команд матча
	 *
	 */
	public function getCompareMathes ()
	{
		$team1 = $this->getTeam1();
		//yii::app()->firephp->info ($team1->id, 'team 1 id');
		$team2 = $this->getTeam2();
		//yii::app()->firephp->info ($team2->id, 'team 2 id');

		// готовим массив с id апанентами первой команды
		$team1Matches = $team1->getLastFinishedMatchesBeforeDate ($this->begintime);
		$team1Oponents = array ();
		foreach ($team1Matches as $m) {
			if (!$m->isFinish()) continue;

			//yii::app()->firephp->log ($m->team1.':'.$m->team2, 't1 match');

			//
			$oponent = 0;
			if ($m->team1 != $team1->id) $oponent = $m->team1;
			if ($m->team2 != $team1->id) $oponent = $m->team2;

			// опускаем если это вторая команда
			if ($oponent == $team2->id) continue;

			//
			$team1Oponents[] = $oponent;
		}

		//yii::app()->firephp->log ($team1Oponents, '$team1Oponents');


		//
		$team2Matches = $team2->getLastFinishedMatchesBeforeDate ($this->begintime);

		$compareMatches = array ();
		$comparedTeams = array();
		foreach ($team2Matches as $m)
		{
			if (!$m->isFinish()) continue;

			//yii::app()->firephp->log ($m->team1.':'.$m->team2, 't2 match');

			// опонент
			$oponent = 0;
			if ($m->team1 != $team2->id) $oponent = $m->team1;
			if ($m->team2 != $team2->id) $oponent = $m->team2;

			//
			if ($oponent == $team1->id) continue;

			//yii::app()->firephp->log ($oponent, 't2 oponent');


			// если нет в апанента первой команды
			if (!in_array($oponent, $team1Oponents)) continue;

			// если опонент уже добавлен
			if (in_array($oponent, $comparedTeams)) continue;


			$comparedTeams[] = $oponent;

			$compareMatches[] = array (
				$team1->id,
				$oponent,
				$team2->id
			);
		}

		//yii::app()->firephp->log ($compareMatches, '$compareMatches');





		/*
		$compareMatches = array();
		$comparedTeams = array();
		//
		foreach ($team1Matches as $m)
		{
			if (!$m->isFinish()) continue;
			$teamsIs = array($m->team1, $m->team2);

			// ищим подходящие матчи
			foreach ($team2Matches as $m2)
			{
				if (!$m2->isFinish()) continue;

				// опонент команды по которой ищим матчи
				if ($m2->team1 == $team2->id) $m2Oponent = $m2->team2;
				else $m2Oponent = $m2->team1;

				if (in_array($m2Oponent, $teamsIs))
				{
					if (in_array($m2Oponent, $comparedTeams)) continue;

					// пропускаем если уже добавлен
					$comparedTeams[] = $m2Oponent;


					$compareMatches[] = array (
						$team1->id,
						$m2Oponent,
						$team2->id
					);
				}
			}
		}*/

		return $compareMatches;
	}

	/**
	 * Расчитываем процентноое соотношение между
	 * крайними командами для стыковых матчей
	 *
	 */
	public function calckComparedMatchedInfo ($data)
	{
		$matches = array ();
		foreach ($data['comparedMatches'] as $cmId => $cm)
		{
			$match1Key = $cm[0].'-'.$cm[1];
			$match2Key = $cm[1].'-'.$cm[2];

			if (!isset($data['comparedMatchesData'][$match1Key])) continue;
			if (!isset($data['comparedMatchesData'][$match2Key])) continue;

			$m1Data = $data['comparedMatchesData'][$match1Key];
			$m2Data = $data['comparedMatchesData'][$match2Key];


			$m1PercentData = array(
				$cm[0] => ($m1Data[$cm[0]] / $m1Data['count']),
				$cm[1] => ($m1Data[$cm[1]] / $m1Data['count']),
			);

			$m2PercentData = array(
				$cm[1] => ($m2Data[$cm[1]] / $m2Data['count']),
				$cm[2] => ($m2Data[$cm[2]] / $m2Data['count']),
			);

			$win0 = ($m1PercentData[$cm[0]] + $m2PercentData[$cm[1]]) * 0.5;
			$win2 = ($m2PercentData[$cm[2]] + $m1PercentData[$cm[1]]) * 0.5;

			$matches[$cmId] = array (
				$cm[0] => $win0,
				$cm[2] => $win2
			);
		}


		return $matches;
	}

	/**
	 * Рассчитываем средний прцет по стыковочным матчам
	 *
	 * @param $data
	 * @return array
	 */
	public function calckComparedMatchesSummeryInfo ($data)
	{
		$sum = array(
			$this->team1 => 0,
			$this->team2 => 0
		);

		$count = 0;
		foreach ($data['comparedMatchesResult'] as $r) {
			$sum[$this->team1] += $r[$this->team1];
			$sum[$this->team2] += $r[$this->team2];
			$count++;
		}
		if ($count > 0) {
			$sum[$this->team1] = $sum[$this->team1] / $count;
			$sum[$this->team2] = $sum[$this->team2] / $count;
		}
		else {
			$sum[$this->team1] = 0.5;
			$sum[$this->team2] = 0.5;
		}

		return $sum;
	}

	public function calckKoef ($data)
	{

		$cData = array (
			'average'   => array (
				$this->team1 => 0,
				$this->team2 => 0
			),
			'clear'     => array (
				$this->team1 => 0,
				$this->team2 => 0
			),
			'walrus'     => array (
				$this->team1 => 0,
				$this->team2 => 0
			),
			'walrusfinish'     => array (
				$this->team1 => 1.1,
				$this->team2 => 1.1
			),
			'walruspercent'     => array (
				$this->team1 => 1,
				$this->team2 => 1
			)
		);

		// средний процент
		$cData['average'][$this->team1] = ($data['matchesResult'][$this->team1] + $data['comparedMatchesSummaryResult'][$this->team1]) / 2;
		$cData['average'][$this->team2] = ($data['matchesResult'][$this->team2] + $data['comparedMatchesSummaryResult'][$this->team2]) / 2;

		if ($cData['average'][$this->team1] > 0)
		{
			// чмсиый коэффициет
			$cData['clear'][$this->team1] = (1 / $cData['average'][$this->team1]);

			// коэффициет с маржой
			$cData['walrusfinish'][$this->team1] = $cData['walrus'][$this->team1] = (0.8 * $cData['clear'][$this->team1]);

			// округленный коэффициет с маржой
			if ($cData['walrusfinish'][$this->team1] < 1.1) $cData['walrusfinish'][$this->team1] = 1.1;

			// игровые коэффициенты
			$cData['walruspercent'][$this->team1] = (1 / $cData['walrusfinish'][$this->team1]);
		}

		if ($cData['average'][$this->team2] > 0)
		{
			// чмсиый коэффициет
			$cData['clear'][$this->team2] = (1 / $cData['average'][$this->team2]);

			// коэффициет с маржой
			$cData['walrusfinish'][$this->team2] = $cData['walrus'][$this->team2] = (0.8 * $cData['clear'][$this->team2]);

			// округленный коэффициет с маржой
			// $cData['walrus'][$this->team2];
			if ($cData['walrusfinish'][$this->team2] < 1.1) $cData['walrusfinish'][$this->team2] = 1.1;

			// игровые коэффициенты
			$cData['walruspercent'][$this->team2] = (1 / $cData['walrusfinish'][$this->team2]);
		}

		return $cData;
	}

	/**
	 * Пишим техническое сообщение для матча
	 *
	 * @param string $message
	 * @param ??? $user пользователь от имени которого сделано действие
	 * @param $source источник сообщения
	 * @param $
	 */
	public function writeLogMessage ($message, $user = null, $source = '') {
		$data = array (
			'time' => time(),
			'message' => $message
		);

		if ($user != null) {
			$data['user'] = $user;
		}

		if ($source != null) {
			$data['source'] = $source;
		}

		MatchLogHandler::pushMessage($this->id, $data);
	}

	public function getLogMessages () {
		return MatchLogHandler::getMessages($this->id);
	}
}
