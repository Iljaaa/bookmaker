<?php
/**
 * Created by PhpStorm.
 * User: ilja
 * Date: 19.01.14
 * Time: 8:06
 */

class BetsprodotaParser extends Parser {


	public function parse()
	{
		$this->load('http://bets.prodota.ru/');

		//
		$itemsSummary = 0;

		// блок "Скоро (Бекмейкерские)"
		$item = $this->_html->find("div.stavkiscoro", 0);
		$itemsForParse = $items = $item->find("div.row");
		$itemsSummary = count($items);

		// блок "закончелись" (блок с не букмейкерскими ставками)
		$item = $this->_html->find("div.stavkipast", 0);
		$items = $item->find("div.row");
		$itemsSummary += count($items);
		$itemsForParse = array_merge($itemsForParse, $items);

		$this->log('matches count to parse '.$itemsSummary.'('.count($itemsForParse).')');


		$games = array ();
		foreach ($itemsForParse as $it) {
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

		$game = new ParsedGame();

		//
		$matchBlock =  $gameData->find('div.match', 0);

		// парсим время
		$timeString = $matchBlock->find('div.date', 0)->innertext;

		$timeString = trim($timeString);
		if ($timeString == '') {
			$this->log('time string is invalid; game skipped');
			$this->log($gameData->innertext);

			return false;
		}

		$this->log('date time string : '.$timeString);

		// формируем метку времени
		$timeArr = explode('в', $timeString);
		if (count($timeArr) == 2)
		{
			// $this->log('date string : '.$timeArr[0]);

			$date = explode('.', $timeArr[0]);
			$date[0] = intVal($date[0]);
			$date[1] = intVal($date[1]);

			$tm = explode(':', $timeArr[1]);
			$tm[0] = trim($tm[0]);
			$tm[0] = intVal($tm[0]);

			$tm[1] = str_replace('CET', '', $tm[1]);
			$tm[1] = str_replace('&nbsp', '', $tm[1]);
			$tm[1] = trim($tm[1]);
			$tm[1] = intVal($tm[1]);

			$year = date('Y', time());

			$mktime = mktime ($tm[0], $tm[1], 0, $date[0], $date[1], $year);

			$this->log('parsed time stamp : '.$mktime);
			$this->log ('to valid : '.date("d.m.Y H:i:s", $mktime));

			// $timeStampCet = $mktime - (3600 * 3);
			$timeStampCet = $mktime;
			$this->log ('time cet : '.date("d.m.Y H:i:s", $timeStampCet));

			$time = $timeStampCet;

			$game->setTime($time);
		}

		// парсим чамп
		$champ = $matchBlock->find('div.nazvanie div', 0)->innertext;
		$champ = strip_tags ($champ);
		$tmp = explode('&nbsp', $champ);
		$champ = trim($tmp[0]);

		$this->log('parsed champ string : '.$champ);

		$champ = $this->getComparedChampName($champ);
		$game->setChamp($champ);


		//
		$team1 = $gameData->find('div.comanda1 span a', 0)->innertext;
		$team1 = $this->getComparedTeamName($team1);
		$this->log('parsed team 1 string : '.$team1);
		$game->setTeam1($team1);

		//
		$team2 = $gameData->find('div.komanda2 span a', 0)->innertext;
		$team2 = $this->getComparedTeamName($team2);
		$this->log('parsed team 2 string : '.$team2);
		$game->setTeam2($team2);

		// коэффициенты
		$koef1 = $gameData->find('div.koef1 span', 0)->innertext;
		$this->log('parsed koef 1 : '.$koef1);
		$koef1 = str_replace("x", '', $koef1);
		$game->setKoef1($koef1);

		$koef2 = $gameData->find('div.koef2 span', 0)->innertext;
		$this->log('parsed koef 2 : '.$koef2);
		$koef2 = str_replace("x", '', $koef2);
		$game->setKoef2($koef2);

		// результат матча
		$score = $gameData->find('div.score', 0);
		if ($score == null){
			$this->log('parsed : no result for this match');
		}
		else {
			$tmp = explode('-', $score->innertext);
			if (count($tmp) == 2){
				$game->setResult1($tmp[0]);
				$game->setResult2($tmp[1]);
			}
		}

		//
		$this->logGame($game);

		return $game;
	}

}