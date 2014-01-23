<?php
/**
 * Created by PhpStorm.
 * User: ilja
 * Date: 18.12.13
 * Time: 9:36
 */

class Parser
{
	/**
	 * Флаг
	 * Выводить ли лог в консоль
	 *
	 * @var bool
	 */
	protected $echo = true;

	/**
	 * Флаг
	 * Выводить ли лог в консоль
	 *
	 * @var bool
	 */
	protected $log = true;

	/**
	 * Массив соотвествия названия команд
	 *
	 * @var array
	 */
	protected $teamsCompare = array (

	);

	/**
	 * Массив соотвествия названия чемпионата
	 *
	 * @var array
	 */
	protected $champCompare = array (

	);

	/**
	 * Разнеца во времени для парсинга
	 *
	 * @var int
	 */
	protected $timedelta = 0;

	/**
	 *
	 *
	 * @var string
	 */
	protected $_html = "";



	public static function factory ($className)
	{
		// echo "\n''".ucfirst($className)."'";
		$className = ucfirst($className)."Parser";
		return new $className;
	}

	/**
	 *
	 *
	 */
	public function __construct()
	{
		$this->log(get_class($this));

		// подключаем файлы переименования
		$path = YiiBase::getPathOfAlias('application.parsers.compare');
		$className = strtolower(get_class($this));

		$teamsPath = $path.'/'.$className.'.teams.php';
		$champsPath = $path.'/'.$className.'.champs.php';

		if (file_exists($teamsPath)) {
			// $this->log ('Teams compare file found : '.$teamsPath);
			$this->teamsCompare = require($teamsPath);
		}
		else {
			// $this->log ('Teams compare file NOT found : '.$teamsPath);
		}

		if (file_exists($champsPath)) {
			// $this->log ('Champs compare file found : '.$champsPath);
			$this->champCompare = require($champsPath);
		}
		else {
			// $this->log ('Champs compare file NOT found : '.$champsPath);
		}
	}

	/**
	 * Загрузка контента
	 *
	 * @param $url
	 */
	public function load ($url) {
		static::log('Parser->load : '.$url);

		$this->_html = file_get_html($url);
	}

	/**
	 * Получаем приведенное имя
	 *
	 */
	public function getComparedTeamName ($name) {
		if (isset($this->teamsCompare[$name])) return $this->teamsCompare[$name];
		return $name;
	}

	/**
	 * Получаем приведенное имя
	 *
	 */
	public function getComparedChampName ($name) {
		if (isset($this->champCompare[$name])) return $this->champCompare[$name];
		return $name;
	}

	/**
	 * Установка флага $echo
	 *
	 * @param bool $val
	 */
	public function setEcho ($val) {
		$this->echo = $val;
	}

	/**
	 *
	 *
	 * @param boolean $log
	 */
	public function setLog($log)
	{
		$this->log = $log;
	}




	/**
	 *
	 *
	 * @param $str строка логирования
	 */
	public function log ($str) {
		if ($this->echo) echo "\r\n".$str;
		if ($this->log) Yii::log($str, "parser", get_class($this));
	}

	public function logGame (ParsedGame $game)
	{
		$this->log('game champ : '.$game->getChamp());
		$this->log("game time : ".date("d.m.Y H:i:s", $game->getTime()).' ('.$game->getTime().')');
		$this->log("game team 1 : ".$game->getTeam1());
		$this->log("game team 2 : ".$game->getTeam2());

		if ($game->getResult1() != null && $game->getResult2() != null){
			$this->log("game result : ".$game->getResult1().'x'.$game->getResult2());
		}

		if ($game->getKoef1() != null && $game->getKoef2() != null){
			$this->log("game koefs : ".$game->getKoef1().'x'.$game->getKoef2());
		}
	}
}