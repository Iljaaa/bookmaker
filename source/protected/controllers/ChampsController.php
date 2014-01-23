<?php
/**
 * Created by PhpStorm.
 * User: ilja
 * Date: 18.12.13
 * Time: 14:30
 */

class ChampsController extends Controller
{
	public function actionIndex (){
		$data = array (
			'champs'    => Champ::model()->findAll()
		);

		$this->render ('index', $data);
	}


	public function actionAdd ()
	{
		$model = new ChampForm();
		if(isset($_POST['ChampForm']))
		{
			$model->attributes = $_POST['ChampForm'];
			if($model->validate()) {
				$t = new Champ ();
				$t->name        = $model->name;
				$t->parent      = $model->parent;
				$t->description = $model->description;
				$t->save();

				if ($t->id > 0) {
					yii::app()->user->setFlash ('champ-edit', 'Team added');
					$url = $this->createUrl('/champ/'.$t->id.'/edit');
					$this->redirect($url);
				}
			}
		}

		$data = array (
			'model' => $model
		);
		$this->render('add', $data);
	}


	public function actionEdit ($id)
	{
		if ($id == 0) throw new CHttpException('Wrong url');

		$champ = Champ::model()->findByPk($id);
		if ($champ == null)  throw new CHttpException('Champ not found');

		$model = new ChampForm();
		$model->setScenario('edit');

		$model->attributes = $champ->attributes;
		if(isset($_POST['ChampForm']))
		{
			$model->attributes = $_POST['ChampForm'];
			if($model->validate()) {
				yii::app()->user->setFlash ('champ-edit', 'Champ updated');
				$champ->updateByPk($champ->id, $model->attributes);
				$this->refresh();
			}
		}

		$data = array (
			'model' => $model,
			'champ'  => $champ
		);

		$this->render('edit', $data);

	}


	public function actionView ($id)
	{
		if ($id == 0) throw new CHttpException('Wrong url');

		$champ = Champ::model()->findByPk($id);
		if ($champ == null)  throw new CHttpException('Champ not found');

		// статистика по матчам

		$criteria = new CDbCriteria();
		$criteria->addCondition('champid = :champ');
		$criteria->params = array (':champ' => $id);
		$criteria->order = "begintime DESC";

		$matches = Match::model()->findAll($criteria);

		$matchesWitchResult = 0;
		$teams = array();
		$stat = array (
			'games' => array (),
			'wins'  => array (),
			'points' => array (),
		);
		foreach ($matches as $m) {
			$team1 = $m->getTeam1();
			$team2 = $m->getTeam2();

			if ($team1 != null) {
				$teams[$team1->id] = $team1->shortname;

				if (!isset($stat['games'][$team1->id])) $stat['games'][$team1->id] = 0;
				$stat['games'][$team1->id]++;
			}
			if ($team2 != null) {
				$teams[$team2->id] = $team2->shortname;

				if (!isset($stat['games'][$team2->id])) $stat['games'][$team2->id] = 0;
				$stat['games'][$team2->id]++;
			}

			$winnerId = $m->getWinnerId();
			if ($winnerId > 0) {
				$matchesWitchResult++;

				if (!isset($stat['wins'][$winnerId])) $stat['wins'][$winnerId] = 0;
				$stat['wins'][$winnerId]++;

				if (!isset($stat['points'][$winnerId])) $stat['points'][$winnerId] = 0;
				$stat['points'][$winnerId] = $stat['points'][$winnerId] + 3;
			}
		}

		// сортируем
		$pointsSort = array();
		$teamsByPoints = array();
		foreach ($stat['points'] as $teamId => $p) {
			if (!isset($teamsByPoints[$p])) $teamsByPoints[$p] = array();
			$teamsByPoints[$p][] = $teamId;
			$pointsSort[] = $p;
		}

		$pointsSort = array_unique($pointsSort);
		rsort($pointsSort);

		// команды с нулем очков
		foreach ($teams as $teamId => $teamName) {
			if (!isset($stat['wins'][$teamId])){
				$teamsByPoints[0][] = $teamId;
			}

		}

		$data = array (
			'champ' => $champ,
			'matches' => $matches,
			'matchesCount' => Match::model()->count($criteria),
			'matchesWitchResult' => $matchesWitchResult,

			'teams' => $teams,
			'stat'  => $stat,
			'order' => $pointsSort,
			'teamsByPoints' => $teamsByPoints
		);

		//
		$view = 'view';
		$path = YiiBase::getPathOfAlias('application.views.champs.promo');
		$file = $path.'/'.$id.'.php';
		yii::app()->firephp->log ($file);
		if (file_exists($file)){
			$view = '/champs/promo/'.$id;
		}

		$this->render ($view, $data);
	}
} 