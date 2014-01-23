<?php
/**
 * Created by PhpStorm.
 * User: ilja
 * Date: 12.12.13
 * Time: 23:14
 */

class TeamsController extends Controller {

	public function filters()
	{
		return array(
			'accessControl',
		);
	}

	public function accessRules()
	{
		return array(
			array('deny',
				'actions'=>array('edit', 'add'),
				// 'roles'=>array('admin'),
				'users'=>array('?'),
			),
			/*
			array('allow',
				'actions'=>array('create', 'edit','delete'),
				'users'=>array('@'),
			),*/
		);
	}

	public function actionIndex()
	{
		$page = yii::app()->getRequest()->getParam('page', 1);
		if ($page < 1) $page = 1;

		$criteria = new CDbCriteria();
		$criteria->order = 'shortname ASC';
		$criteria->offset = ($page - 1) * 40;
		$criteria->limit = 40;

		$data = array (
			'teams' => Team::model()->findAll($criteria),
			'teamsCount' => Team::model()->count(),
			'page'  => $page
		);

		$this->render('index', $data);
	}


	public function actionAdd ()
	{
		$model = new TeamForm();
		if(isset($_POST['TeamForm']))
		{
			$model->attributes = $_POST['TeamForm'];
			if($model->validate()) {
				$t = new Team ();
				$t->name = $model->name;
				$t->shortname = $model->shortname;
				$t->save();

				if ($t->id > 0) {
					yii::app()->user->setFlash ('team-edit', 'Team added');
					$url = $this->createUrl('/team/'.$t->id.'/edit');
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

		$team = Team::model()->findByPk($id);
		if ($team == null)  throw new CHttpException('Team not found');

		$model = new TeamForm();
		$model->attributes = $team->attributes;
		if(isset($_POST['TeamForm']))
		{
			$model->attributes = $_POST['TeamForm'];
			if($model->validate()) {
				yii::app()->user->setFlash ('team-edit', 'Team updated');
				$team->updateByPk($team->id, $model->attributes);
				$this->refresh();
			}
		}

		$data = array (
			'model' => $model,
			'team'  => $team
		);

		$this->render('edit', $data);
	}

} 