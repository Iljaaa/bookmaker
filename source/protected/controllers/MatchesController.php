<?php
/**
 * Created by PhpStorm.
 * User: ilja
 * Date: 13.12.13
 * Time: 9:27
 */

class MatchesController extends Controller
{

	public function actionIndex()
	{
		$data = array (
			'matches'    => Match::findForIndexPage()
		);
		$this->render ('index', $data);
	}

	public function actionAdd ()
	{
		$model = new MatchForm();
		$model->setScenario ('create');

		if(isset($_POST['MatchForm']))
		{
			$model->attributes=$_POST['MatchForm'];
			if($model->validate()) {

				$match = new Match();
				$match->updateByModel($model);
				$match->save ();

				if ($match->id > 0){
					$url = $this->createUrl($match->getUrl());
					$this->redirect($url);
				}
			}
		}

		$data = array (
			'model'  => $model
		);

		$this->render ('add', $data);
	}

	/**
	 *
	 *
	 */
	public function actionEdit ()
	{

	}

	public function actionView ($id)
	{
		if ($id == 0) throw new HttpUrlException ('Match id not setted');

		$match = Match::model()->findByPk($id);
		if ($match == null) throw new Exception ('Match not found');

		$team1 = $match->getTeam1 ();
		if ($team1 == null) throw new Exception ('Team 1 setted wrong');

		$team2 = $match->getTeam2 ();
		if ($team2 == null) throw new Exception ('Team 2 setted wrong');

		$user = null;
		if (!Yii::app()->user->isGuest) {
			$username = Yii::app()->user->name;
			$user = User::model()->findByLogin($username);
			if ($user == null)  throw new Exception ('User not found');
		}


		$model = new BetForm();
		$resultModel = new ResultForm();

		if ($user != null) {
			$model->uid = $user->id;
		}

		$resultModel->team1Id = $model->team1Id = $team1->id;
		$resultModel->team2Id = $model->team2Id = $team2->id;
		$resultModel->matchId = $model->matchId = $id;

		// сохраняем ставку
		if(isset($_POST['BetForm']))
		{
			$model->attributes=$_POST['BetForm'];
			if($model->validate())
			{
				$messages = array ();
				if ($model->createBetTeam1()){
					$messages[] = 'Bet accepted on '.$team1->shortname;
				}

				if ($model->createBetTeam2()) {
					$messages[] = 'Bet accepted on '.$team2->shortname;
				}

				if (count($messages) > 0) {
					$user = User::getAuthedUser();
					$user->updateBalance();
					$user->save();

					yii::app()->user->setFlash ('bet', $messages);
					$this->refresh();
				}

			}
		}

		// сохраняем результат
		if(isset($_POST['ResultForm']))
		{
			$resultModel->attributes = $_POST['ResultForm'];
			if($resultModel->validate()) {
				yii::app()->user->setFlash ('bet', array('Match result accepted'));
				$match->setResult($resultModel->team1Result, $resultModel->team2Result);
				$this->refresh();
			}
		}

		$data = array (
			'match'     => $match,
			'team1'     => $team1,
			'team2'     => $team2,
			'model'     => $model,
			'resultModel' => $resultModel,
		);

		$this->render ('view', $data);
	}


	/**
	 * Список матчей инструмент администратора
	 *
	 */
	public function actionAdminList ()
	{
		if (!yii::app()->user->hasRole('admin')) {
			throw new CHttpException('Has no rights');
		}

		yii::app()->firephp->log ($_GET);

		//
		$this->adminCommands();

		$filterForm = new TeamsAdminFilterForm();
		if (isset($_GET['TeamsAdminFilterForm'])) {
			$filterForm->attributes = $_GET['TeamsAdminFilterForm'];
		}

		$criteria = $this->createCriteria4AdminList($filterForm);

		$data = array (
			'matches' => Match::model()->findAll($criteria),
			'matchesCount' => Match::model()->count($criteria),
			'filterForm' => $filterForm
		);

		$this->render ('adminlist', $data);
	}

	protected function createCriteria4AdminList (TeamsAdminFilterForm $filterForm)
	{
		$criteria = new CDbCriteria();
		$criteria->order = "begintime DESC";
		$criteria->params = array();

		yii::app()->firephp->info ($filterForm->attributes);

		if ($filterForm->team > 0) {
			$criteria->addCondition('team1 = :team OR team2 = :team');
			$criteria->params [':team'] = $filterForm->team;
		}

		if ($filterForm->champ > 0) {
			$criteria->addCondition('champid = :champ');
			$criteria->params [':champ'] = $filterForm->champ;
		}

		return $criteria;
	}

	public function adminCommands ()
	{
		$command = yii::app()->getRequest()->getParam('command', '');

		if ($command == 'cancel') {
			$id = intVal(yii::app()->getRequest()->getParam('id', 0));
			if ($id == 0) {
				yii::app()->user->setFlash ('adminlist-bad', 'Id sended wrong');
				$this->refresh();
			}

			$match = Match::model()->findByPk($id);
			if ($match == null) {
				yii::app()->user->setFlash ('adminlist-bad', 'Id sended wrong');
				$this->refresh();
			}

			$meassage = '';
			if (!$match->canCanceled($meassage)){
				yii::app()->user->setFlash ('adminlist-bad', 'Match can not be canceled : '.$meassage);
				$this->refresh();
			}

			//
			$match->cancel();

			yii::app()->user->setFlash ('adminlist', 'Match canceled');
			$this->refresh();

		}

		if ($command == 'update-factor'){
			$id = intVal(yii::app()->getRequest()->getParam('id', 0));
			if ($id == 0) {
				yii::app()->user->setFlash ('adminlist-bad', 'Id sended wrong');
				$this->refresh();
			}

			$match = Match::model()->findByPk($id);
			if ($match == null) {
				yii::app()->user->setFlash ('adminlist-bad', 'Match not found');
				$this->refresh();
			}

			//
			$data = $match->getCalckData();
			yii::app()->firephp->log ($data);
			$match->factor1 = $data['koef']['walrusfinish'][$match->team1];
			$match->factor2 = $data['koef']['walrusfinish'][$match->team2];
			$match->save();

			yii::app()->user->setFlash ('adminlist', 'Match '.$match->id.' factors updates');
			$this->refresh();
		}
	}

	/**
	 *
	 *
	 */
	public function actionDelete ($match)
	{
		if ($match == 0) throw new CHttpException('Url wrong params');

		$match = Match::model()->findByPk($match);
		if ($match == null) throw new CHttpException('Match not found');

		if (!yii::app()->user->hasRole('admin')) {
			throw new CHttpException('Has no rights');
		}

		$command = yii::app()->getRequest()->getParam('command');
		if ($command == 'delete'){
			if ($match->delete()) {
				yii::app()->user->setFlash('adminlist', 'Match deleted');

				$url = $this->createUrl('/matches/adminlist');
				$this->redirect($url);
			}

			yii::app()->user->setFlash('match-delete', 'Match no deleted');
			$this->refresh();
		}


		$data = array(
			'match' => $match
		);

		$this->render ('delete', $data);
	}

	/**
	 * Установка результата матча
	 *
	 */
	public function actionResult ($match)
	{
		if ($match == 0) throw new CHttpException('Url wrong params');

		$match = Match::model()->findByPk($match);
		if ($match == null) throw new CHttpException('Match not found');

		if (!yii::app()->user->hasRole('admin')) {
			throw new CHttpException('Has no rights');
		}

		$model = new ResultForm();
		$model->matchId = $match->id;
		$model->team1Id = $match->team1;
		$model->team2Id = $match->team2;

		// сохраняем результат
		if(isset($_POST['ResultForm']))
		{
			$model->attributes = $_POST['ResultForm'];
			if($model->validate()) {
				yii::app()->user->setFlash ('adminlist', 'Match result accepted');

				$match->setResult($model->team1Result, $model->team2Result);

				$url = $this->createUrl('/matches/adminlist');
				$this->redirect($url);
			}
		}

		$data = array(
			'match' => $match,
			'model' => $model
		);

		$this->render ('result', $data);
	}
} 