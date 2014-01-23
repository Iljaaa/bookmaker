<?php
/**
 * Created by PhpStorm.
 * User: ilja
 * Date: 18.12.13
 * Time: 9:39
 */

class ProdotaParser extends Parser {


	public function parse()
	{
		$this->load('http://prodota.ru/');

		$item = $this->_html->find("div.games_items_wrapper", 0);
		$items = $item->find("span.game_item");
		$this->log('matches count to parse '.count($items));

		$games = array ();
		foreach ($items as $it) {
			$g = $this->_parseGame($it);
			if ($g === false) continue;

			$games[] = $g;
		}

		$this->log('parsed games count '.count($games));
		return $games;
	}

	protected function _parseGame ($gameData)
	{
		if ($gameData->innertext == '') return false;

		$timeString = $gameData->find('div.game_time', 0)->innertext;

		$timeString = trim($timeString);
		$timeString = str_replace(' ', '', $timeString);
		if ($timeString == '') {
			$this->log('time string is invalid; game skipped');
			$this->log($gameData->innertext);

			return false;
		}

		$this->log('time string : '.$timeString);

		// формируем метку времени
		$timeArr = explode('<br/>', $timeString);
		if (count($timeArr) == 2)
		{
			$date = explode('.', $timeArr[0]);
			$timeArr[0] = $date;

			$tm = explode(':', $timeArr[1]);
			$timeArr[1] = $tm;

			$year = date('Y', time());
			if ($date[1] == 1) $year = 2014;
			if ($date[1] == 12) $year = 2013;
			$mktime = mktime ($tm[0], $tm[1], 0, $date[1], $date[0], $year);

			$this->log('parsed time stamp : '.$mktime);
			$this->log ('to valid : '.date("d.m.Y H:i:s", $mktime));

			$timeStampCet = $mktime - (3600 * 3);
			//$timeStampCet = $mktime;
			$this->log ('time cet : '.date("d.m.Y H:i:s", $timeStampCet));

			$time = $timeStampCet;
		}


		$teams = array(
			0 => $gameData->find('div.team1_name', 0)->innertext,
			1 => $gameData->find('div.team2_name', 0)->innertext
		);

		$teams[0] = trim($teams[0]);
		$teams[0] = $this->getComparedTeamName($teams[0]);

		$teams[1] = trim($teams[1]);
		$teams[1] = $this->getComparedTeamName($teams[1]);

		$result = $gameData->find('div.vs_logo span', 0)->innertext;
		$result = explode(':', $result);

		$champ = $gameData->find('div.game_detail b', 0)->innertext;
		$champ = trim($champ);
		$champ = $this->getComparedChampName($champ);

		$game = array(
			'timestring'    => $timeString,
			'time'          => $time,
			'teams'         => $teams,
			'result'        => $result,
			'champ'         => $champ
		);

		$this->log('Data ready : '.date('d.m.Y H:i.s',$game['time'])."\t".$game['result'][0].':'.$game['result'][1]."\t team 1 : ".$game['teams'][0].' team2 : '.$game['teams'][1]);

		return $game;
	}

} 