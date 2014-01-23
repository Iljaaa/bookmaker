<?php
/**
 * Created by PhpStorm.
 * User: ilja
 * Date: 05.01.14
 * Time: 14:03
 */

class MatchDataController extends Controller {


	/**
	 * Данные по процентам для матчей
	 * с сайтов со ставками
	 *
	 */
	public function actionCompare ()
	{
		Yii::import('application.parsers.*');

		$source = yii::app()->getRequest()->getParam('source', 0);
		if ($source == '')  throw new Exception('Source not sended');

		$matchId = yii::app()->getRequest()->getParam('match', 0);
		if ($matchId == 0) throw new Exception('Match id not sended');

		$match = Match::model()->findByPk($matchId);
		if ($match == null) throw new Exception('Match '.$matchId.' not found');

		$team1 = $match->getTeam1();
		if ($team1 == null) throw new Exception('Match team 1 not found');

		$team2 = $match->getTeam2();
		if ($team2 == null) throw new Exception('Match team 2 not found');

		$cache = true;
		$cacheFlag = yii::app()->getRequest()->getParam('cache', 1);
		if ($cacheFlag == 0) $cache = false;

		//
		$data = $this->getdata($source, $cache);

		$foundGame = null;
		foreach ($data as $game)
		{
			$val = false;

			// FIXIT : потом удрать проверку на массив когда все парсеры будут возвращать объект
			if (is_array($game)){
				$val = (
					in_array($team1->shortname, $game['teams'])
					&& in_array($team2->shortname, $game['teams'])
				);
			}
			else {
				if ($game->hasTeam($team1->shortname) && $game->hasTeam($team2->shortname)){
					$val = true;
				}
			}

			if ($val) {
				$foundGame = $game;
				break;
			}

		}

		ob_clean();
		ob_flush();
		echo $this->renderPartial('/matchdata/compare/'.$source, array('match' => $foundGame));

	}

	/**
	 *
	 *
	 * @param $parser
	 */
	protected function getdata ($parser, $cache = true)
	{
		$cacheId = $parser.'-matchdatacontroller';

		$games = null;
		if ($cache) $games = Yii::app()->cache->get($cacheId);

		if ($games == null) {
			$games = $this->loaddata($parser);
			Yii::app()->cache->set($cacheId, $games, 600);
			// yii::app()->firephp->info($cacheId.' have no cache');
		}
		else {
			// yii::app()->firephp->info($cacheId.' have cache');
		}

		return $games;
	}

	/**
	 * Грузим данные из парсера
	 *
	 */
	protected function loaddata ($parser)
	{
		Yii::import('application.extensions.simple_html_dom');

		$path = YiiBase::getPathOfAlias('application.extensions.simple_html_dom').'.php';
		Yii::import('application.extensions.simple_html_dom');
		require_once ($path);

		$a = Parser::factory($parser);
		$a->setEcho(false);
		return $a->parse();
	}

} 