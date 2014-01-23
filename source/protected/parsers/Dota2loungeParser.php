<?php
/**
 * Created by PhpStorm.
 * User: ilja
 * Date: 05.01.14
 * Time: 13:28
 */

class dota2loungeParser extends Parser  {

	public function parse ()
	{

		$this->load('http://dota2lounge.com/');
		$item = $this->_html->find("article#bets", 0);
		$items = $item->find("div.matchmain");

		$games = array();
		foreach ($items as $item){
			$game = $this->parseGame($item);
			if ($game){
				$games[] = $game;
			}
		}

		return $games;
	}


	protected function parseGame ($item)
	{
		$teamBlocks = $item->find('div.teamtext');
		if (count($teamBlocks) != 2) return false;

		$teamBlock = $teamBlocks[0];
		$team1Name = $teamBlock->find('b',0)->plaintext;
		$team1Name = trim($team1Name);
		$team1Name = $this->getComparedTeamName($team1Name);
		$team1Result = $teamBlock->find('i',0)->plaintext;

		$teamBlock = $teamBlocks[1];
		$team2Name = $teamBlock->find('b',0)->plaintext;
		$team2Name = trim($team2Name);
		$team2Name = $this->getComparedTeamName($team2Name);
		$team2Result = $teamBlock->find('i',0)->plaintext;

		$game = array(
			'timestring'    => '', // date("d.m.Y H:i.s",$finishDate),
			'time'          => 0, // $finishDate,
			'teams'         => array(0 => $team1Name, 1 => $team2Name),
			'result'        => array(0 => $team1Result, 1 => $team2Result),
			'champ'         => ''
		);

		$log = 'Data ready : '.date('d.m.Y H:i.s',$game['time']);
		$log .= "\t".$game['result'][0].':'.$game['result'][1];
		$log .= "\t".$game['teams'][0].' : '.$game['teams'][1];

		$this->log($log);

		return $game;
	}

} 