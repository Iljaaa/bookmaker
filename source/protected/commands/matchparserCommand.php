<?php
/**
 * Created by PhpStorm.
 * User: ilja
 * Date: 18.12.13
 * Time: 9:31
 */

class matchparserCommand extends CConsoleCommand
{
	/**
	 * Массив парсеров
	 *
	 * @var array
	 */
	protected $_parsers = array (
		'prodota', 'starladder'
	);


	/**
	 * Массив соотвествия названия команд
	 *
	 * @var array
	 */
	protected $teamsCompare = array (
		'_A_' => 'Alliance',
		'RoX.KIS-' => 'RoX.KIS',
		'Relax.' => 'Relax',
		'.SSD.' => 'SSD',
		'Sigma.' => 'Sigma.Int',
		'-Fnatic-' => 'FnaticEU',
		'aSpera.' => 'aSpera',
		'TheRetry' => 'The Retry',
		'Na`Vi' => 'NaVi',
		'-OsG-' => 'OSG',
		'Mouz.' => 'Mouz'
	);

	/**
	 * Массив соотвествия названия чемпионата
	 *
	 * @var array
	 */
	protected $champCompare = array (
		'Starladder # 8'    => 'Starladder #8',
		'Starladder#8'   	=> 'Starladder #8',
		'starladder'        => 'Starladder #8',
	);



	/**
	 * Парсинг матчей на продоте
	 *
	 * @param array $args
	 * @return int|void
	 */
	public function run($args)
	{
		Yii::import('application.parsers.*');
		Yii::import('application.extensions.simple_html_dom');

		$path = YiiBase::getPathOfAlias('application.extensions.simple_html_dom').'.php';
		Yii::import('application.extensions.simple_html_dom');
		require_once ($path);

		if (count($args) > 0) {
			foreach ($args as $a) $this->runParser($a);
		}
		else {
			foreach ($this->_parsers as $parserName) $this->runParser($parserName);
		}
	}

	protected function runParser ($parserName)
	{
		$a = Parser::factory($parserName);
		$games = $a->parse();

		// обновляем данные из парсера
		$hasErrors = false;
		$messages = $this->updateMatchesInfo($games, $hasErrors, get_class($a));

		// логируем сообщения
		$className = get_class($this);
		foreach ($messages as $m) {
			echo "\r\n".$m;
			Yii::log($m, "matchupdate", $className);
			if ($hasErrors) Yii::log($m, "matchupdateemail", $className);
		}
	}

	/**
	 * Обновляем игры на сервере
	 *
	 * @param array $games
	 * @param bool $hasErrors // были ли ошибки при парсенге
	 * @param string $parserName
	 * @return array $messages
	 */
	protected function updateMatchesInfo (array $games, &$hasErrors = false, $parserName = "")
	{
		$messages = array();
		$messages[] = "---Games to updating: ".count($games);

		foreach ($games as $g) :

			$messages[] = '=== Match '.$g['teams'][0].' vs '.$g['teams'][1].' ['.$g['champ'].']';

			// находим команду 1
			$teamParsedName = $teamName = $g['teams'][0];
			if (isset($this->teamsCompare[$teamName])) {
				$teamParsedName = $this->teamsCompare[$teamName];
			}

			$team1 = Team::findTeamByName($teamParsedName);
			if ($team1 == null) {
				$m = 'Team "'.$teamName.'" not found';
				$messages[] = 'Team "'.$teamName.'" not found';
				$hasErrors = true;

				continue;
			}

			// находим команду 2
			$teamParsedName = $teamName = $g['teams'][1];
			if (isset($this->teamsCompare[$teamName])) {
				$teamParsedName = $this->teamsCompare[$teamName];
			}

			$team2 = Team::findTeamByName($teamParsedName);
			if ($team2 == null) {
				$messages[] = 'Team "'.$teamName.'" not found';
				$hasErrors = true;
				continue;
			}

			// ищим чемпионат
			$champ = null;
			if (isset($g['champ']) && $g['champ'] != '') {
				$champName = $g['champ'];
				if (isset($this->champCompare[$champName])) $champName = $this->champCompare[$champName];

				$champ = Champ::findChampByName($champName);
				if ($champ == null) {
					$messages[] = 'Champ '.$g['champ'].' not found';
					$hasErrors = true;
					continue;
				}
			}


			// ищим матч
			$match = Match::findMathByTeamsAndTime($team1->id, $team2->id, $g['time']);
			if ($match == null) {
				$messages[] = 'Match t1:'.$team1->id.' t2:'.$team2->id.' on:'.date('d.m.Y H:i:s', $g['time']).' ('.$g['time'].') not found';
			}
			else {
				// делаем пометку в матче что он найден
				$match->writeLogMessage('Match found ('.$match->id.')', null, $parserName);
			}

			// создаем новый матч
			if ($match == null) {
				$match = new Match();
				$match->factor1 = 1;
				$match->factor2 = 1;
				$match->team1 = $team1->id;
				$match->team2 = $team2->id;
				$match->begintime = $g['time'];

				if ($champ != null){
					$match->champid = $champ->id;
				}

				$match->save ();

				// применяем коэффициенты
				$calck = $match->getCalckData();

				$team1Koef = 1;
				if (isset($calck['koef']['walrusfinish'][$team1->id])) {
					$team1Koef = $calck['koef']['walrusfinish'][$team1->id];
				}

				$team2Koef = 1;
				if (isset($calck['koef']['walrusfinish'][$team2->id])) {
					$team2Koef = $calck['koef']['walrusfinish'][$team2->id];
				}

				$match->factor1 = $team1Koef;
				$match->factor2 = $team2Koef;
				$match->save ();

				$message = 'Match t1:'.$team1->id.' t2:'.$team2->id.' on:'.date('d.m.Y H:i:s', $g['time']).' ('.$g['time'].') created id : '.$match->id;
				$messages[] = $message;
				$match->writeLogMessage($message, null, $parserName);
			}

			// обновляем редультат
			$val = (
				isset($g['result'][0])
				&& isset($g['result'][1])
				&& ($g['result'][1] > 0 || $g['result'][0] > 0)
				&& $match != null
				&& ($match->result1 == 0 && $match->result2 == 0)
			);
			if ($val)
			{
				$result1 = 0;
				$result2 = 0;
				if ($match->team1 == $team1->id){
					$result1 = $g['result'][0];
				}
				if ($match->team1 == $team2->id){
					$result1 = $g['result'][1];
				}

				if ($match->team2 == $team1->id){
					$result2 = $g['result'][0];
				}
				if ($match->team2 == $team2->id){
					$result2 = $g['result'][1];
				}

				$match->setResult($result1, $result2);

				$message = 'Match id:'.$match->id.' on '.date('d.m.Y H:i:s', $g['time']).' ('.$g['time'].') result updated';
				$messages[] = $message;
				$match->writeLogMessage($message, null, $parserName);
			}

			$messages[] = 'ok';
		endforeach; // foreach ($games as $key => $g) :


		return $messages;
	}


	protected function log ($message)
	{
		echo "\r\n".$message;
		Yii::log($message, "matchupdate", get_class($this));
	}
} 