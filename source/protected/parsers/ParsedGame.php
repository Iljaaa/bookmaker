<?php
/**
 * Класс для распарсеной игры
 *
 * Created by PhpStorm.
 * User: ilja
 * Date: 19.01.14
 * Time: 8:15
 */

class ParsedGame
{
	protected $time = 0;

	protected $team1 = '';
	protected $team2 = '';

	protected $champ = '';

	protected $result1 = null;
	protected $result2 = null;

	protected $koef1 = null;
	protected $koef2 = null;


	/**
	 * Проверяем есть ли команда в участниках
	 *
	 */
	public function hasTeam ($teamName) {
		return ($this->getTeam1() == $teamName || $this->getTeam2() == $teamName);
	}




	/**
	 * @param int $time
	 */
	public function setTime($time)
	{
		$this->time = $time;
	}

	/**
	 * @return int
	 */
	public function getTime()
	{
		return $this->time;
	}

	/**
	 * @param string $team1
	 */
	public function setTeam1($team1)
	{
		$this->team1 = $team1;
	}

	/**
	 * @return string
	 */
	public function getTeam1()
	{
		return $this->team1;
	}

	/**
	 * @param string $team2
	 */
	public function setTeam2($team2)
	{
		$this->team2 = $team2;
	}

	/**
	 * @return string
	 */
	public function getTeam2()
	{
		return $this->team2;
	}

	/**
	 * @param string $champ
	 */
	public function setChamp($champ)
	{
		$this->champ = $champ;
	}

	/**
	 * @return string
	 */
	public function getChamp()
	{
		return $this->champ;
	}

	/**
	 * @param null $result1
	 */
	public function setResult1($result1)
	{
		$this->result1 = $result1;
	}

	/**
	 * @return null
	 */
	public function getResult1()
	{
		return $this->result1;
	}

	/**
	 * @param null $result2
	 */
	public function setResult2($result2)
	{
		$this->result2 = $result2;
	}

	/**
	 * @return null
	 */
	public function getResult2()
	{
		return $this->result2;
	}

	/**
	 * @param null $koef1
	 */
	public function setKoef1($koef1)
	{
		$this->koef1 = $koef1;
	}

	/**
	 * @return null
	 */
	public function getKoef1()
	{
		return $this->koef1;
	}

	/**
	 * @param null $koef2
	 */
	public function setKoef2($koef2)
	{
		$this->koef2 = $koef2;
	}

	/**
	 * @return null
	 */
	public function getKoef2()
	{
		return $this->koef2;
	}


} 