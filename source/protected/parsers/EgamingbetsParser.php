<?php
/**
 * Created by PhpStorm.
 * User: ilja
 * Date: 05.01.14
 * Time: 16:09
 */

class EgamingbetsParser extends Parser {

	/**
	 * Массив соотвествия названия чемпионата
	 *
	 * @var array
	 */
	protected $champCompare = array (
		'Starseries'    => 'Starladder #8',
	);

	public function parse ()
	{

		$postParams = array (
			'act' => 'UpdateTableBets',
			'ajax'  => 'update',
			'g2' => 1,
			'g4' => 1,
			'g6' => 1,
			'g7' => 1,
			'g8' => 1,
			'ind' => 'tables',
			'type' => 'modules',
		);

		$this->load('http://egamingbets.com/ajax.php?key=modules_tables_update_UpdateTableBets', $postParams);

		$this->log ('game to parse : '.count($this->_html['bets']));
		//echo "\r\n\t".count($this->_html['bets'])."\r\n";
		$games = array();
		foreach ($this->_html['bets'] as $item){
			$game = $this->parseGame($item);
			if ($game){
				// print_r ($game);
				$games[] = $game;
			}
		}

		$this->log ('parsed games count : '.count($games));
		return $games;
	}

	/**
	 * @param $item
	 * @return array|bool
	 */
	protected function parseGame ($item)
	{
		if ($item['game'] != 'Dota2') return false;

		$finishDate = $item['date_t'] - (3600 * 3);

		$team1Name = trim($item['gamer_1']['nick']);
		$team1Name = $this->getComparedTeamName($team1Name);
		$team2Name = trim($item['gamer_2']['nick']);
		$team2Name = $this->getComparedTeamName($team2Name);

		$team1Result = $item['gamer_1']['points'];
		$team2Result = $item['gamer_2']['points'];

		$team1Koef = $item['coef_1'];
		$team2Koef = $item['coef_2'];

		$champ = $item['tourn'];
		$champ = $this->getComparedChampName($champ);

		$data = array(
			'timestring'    => date("d.m.Y H:i.s",$finishDate),
			'time'          => $finishDate,
			'teams'         => array(0 => $team1Name, 1 => $team2Name),
			'result'        => array(0 => $team1Result, 1 => $team2Result),
			'koef'          => array(0 => $team1Koef, 1 => $team2Koef),
			'champ'         => $champ
		);

		$log = 'Data ready : '.date('d.m.Y H:i.s',$data['time']);
		$log .= "\t".$data['result'][0].':'.$data['result'][1];
		$log .= "\t".$data['teams'][0].' : '.$data['teams'][1];

		$this->log($log);

		return $data;
	}



	public function load ($url, $post = array())
	{
		$this->log('load : '.$url);

		$postData = '';
		foreach ($post as $pKey => $pVal){
			if ($postData != '') $postData .= '&';
			$postData .= $pKey.'='.$pVal;
		}

		if ($postData != ''){
			$this->log ('POST: '.$postData);
		}

		$header = "";
		//$header .= "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,/*;q=0.8".PHP_EOL;
		//$header .= "Accept-Encoding: gzip, deflate".PHP_EOL;
		//$header .= "Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3".PHP_EOL;
		//$header .= "Connection: keep-alive".PHP_EOL;
		//$header .= "Host: egamingbets.com".PHP_EOL;
		$header .= 'Content-Type: application/x-www-form-urlencoded'.PHP_EOL;
		$header .= "User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:25.0) Gecko/20100101 Firefox/25.0 FirePHP/0.7.4".PHP_EOL;

		$context = stream_context_create(array(
			'http' => array(
				'method' => 'POST',
				'header' => $header,
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
	 * Парсинг главной страницы
	 *
	 * @return array
	 *
	 *
	 *
	 *
	public function parse ()
	{

		$this->load('http://egamingbets.com/ru/tables#');
		$item = $this->_html->find("table.tablebets", 0);
		$items = $item->find("tr.tablebody");
		echo "\r\n\t".count($items)."\r\n";
		$games = array();
		foreach ($items as $item){
			$game = $this->parseGame($item);
			if ($game){
				$games[] = $game;
			}
		}



		return $games;
	}


	 *
	 * @param $url
	 * @param array $post
	 *
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

		$header = "";
		//$header .= "Accept: text/html,application/xhtml+xml,application/xml;q=0.9,/*;q=0.8".PHP_EOL;
		//$header .= "Accept-Encoding: gzip, deflate".PHP_EOL;
		//$header .= "Accept-Language: ru-RU,ru;q=0.8,en-US;q=0.5,en;q=0.3".PHP_EOL;
		//$header .= "Connection: keep-alive".PHP_EOL;
		//$header .= "Host: egamingbets.com".PHP_EOL;
		$header .= "User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:25.0) Gecko/20100101 Firefox/25.0 FirePHP/0.7.4".PHP_EOL;
		//$header .= "x-insight: activate".PHP_EOL;

		$context = stream_context_create(array(
			'http' => array(
				'method' => 'GET',
				//'header' => 'Content-Type: application/x-www-form-urlencoded'.PHP_EOL,
				// 'content' => $postData,
				'header' => $header

			),
		));

		$content = file_get_contents(
			$file = $url,
			$use_include_path = false,
			$context);

		$this->_html = str_get_html($content);

	}*/
} 