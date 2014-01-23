<?php

class testCommand extends CConsoleCommand
{

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

		$a = Parser::factory('betsprodota');
		// $a->$a->setEcho(false);
		$games = $a->parse();

		// print_r ($games);

		/* обновляем данные из парсера
		$hasErrors = false;
		$messages = $this->updateMatchesInfo($games, $hasErrors);

		// логируем сообщения
		$className = get_class($this);
		foreach ($messages as $m) {
			echo "\r\n".$m;
			Yii::log($m, "matchupdate", $className);
			if ($hasErrors) Yii::log($m, "matchupdateemail", $className);
		}*/

	}
}
