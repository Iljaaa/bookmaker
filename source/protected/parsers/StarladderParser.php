<?php
/**
 * Created by PhpStorm.
 * User: ilja
 * Date: 01.01.14
 * Time: 11:28
 */

class starladderParser extends Parser
{

	/**
	 * Массив соотвествия названия команд
	 *
	 * @var array
	 */
	protected $teamsCompare = array (
		'The Retry' => 'TR',
		'TheRetry' => 'TR',
		'QPAD.' => 'QPAD'
	);


	public function parse ()
	{
		$this->log('start parse '.date('d.m.Y H:i:s'));
		$url = 'http://dota2.starladder.tv/home/broadcast/next';

		$parsedGames = array ();
		for ($i = -2; $i < 2; $i++)
		{
			$time = time() + ($i * 24 * 3600);

			$this->load ($url, array(
				'date' => date('Y-m-d', $time)
			));

			// дата матчей
			$da = strtotime($this->_html['date']);
			$this->log('parsed date : '.date('d.m.Y', $da));

			//
			$this->_html = str_get_html($this->_html['matches']);

			$games = $this->_html->find("div.translations_item");
			$this->log("\r\ngames count = ".count($games));

			foreach ($games as $g) {
				$parsedGames[] = $this->parseGame($g, $da);
			}

		}

		return $parsedGames;
	}


	public function load ($url, $post = array())
	{
		$this->log(get_class($this));
		$this->log($url);

		$postData = '';
		foreach ($post as $pKey => $pVal){
			$postData = $pKey.'='.$pVal;
		}

		if ($postData != ''){
			$this->log ('POST: '.$postData);
		}

		$context = stream_context_create(array(
			'http' => array(
				'method' => 'POST',
				'header' => 'Content-Type: application/x-www-form-urlencoded'.PHP_EOL,
				'content' => $postData,
			),
		));

		$content = file_get_contents(
			$file = $url,
			$use_include_path = false,
			$context);

		$this->_html = json_decode($content, true);


	}

	/**
	 * @param $data данные матча
	 * @param $date день матчи для которого парсятся
	 * @return array|bool
	 */
	protected function parseGame ($data, $date)
	{
		$rows = $data->find('table.star tbody tr');
		if (count($rows) != 2) return false;

		$r = $rows[0];
		$team1 = $r->find('span.tournament_member', 0)->plaintext;
		$team1 = trim($team1);
		$team1 = $this->getComparedTeamName($team1);

		$result1 = $r->find('td', 2)->plaintext;

		$time = $r->find('td', 3)->plaintext;
		$time = str_replace('CET', '', $time);
		$time = trim($time);
		$timeArr = explode(':', $time);
		if (count($timeArr) != 2) {
			$this->log('Wrong time format : '.$time);
			return false;
		}

		$finishDate = mktime ($timeArr[0], $timeArr[1], 0,date("n", $date), date("j", $date), date("Y", $date));

		// +3 часа
		// $finishDate = $finishDate + (3 * 3600);

		$r = $rows[1];
		$team2 = $r->find('span.tournament_member', 0)->plaintext;
		$team2 = trim($team2);
		$team2 = $this->getComparedTeamName($team2);

		$result2 = $r->find('td', 2)->plaintext;

		$data = array(
			'timestring'    => date("d.m.Y H:i.s",$finishDate),
			'time'          => $finishDate,
			'teams'         => array(0 => $team1, 1 => $team2),
			'result'        => array(0 => $result1, 1 => $result2),
			'champ'         => 'starladder'
		);

		$this->log('Data ready : '.$data['timestring']."\t".$data['result'][0].':'.$data['result'][1]."\t team 1 : ".$data['teams'][0].' team2 : '.$data['teams'][1]);

		return $data;
	}

} 