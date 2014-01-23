<?php
/**
 * Created by PhpStorm.
 * User: ilja
 * Date: 13.12.13
 * Time: 18:20
 */

class BetsController extends Controller
{

	/**
	 * Расчет ставок по результатам матча
	 *
	 *
	public function actionCalkmatch ($id)
	{
		if ($id == 0) throw new HttpUrlException ('Match id not setted');

		$match = Match::model()->findByPk($id);
		if ($match == null) throw new Exception ('Match not found');

		// победиель
		$winerId = $match->team1;
		$winFactor = $match->factor1;
		if ($match->result2 > $match->result1) {
			$winerId = $match->team2;
			$winFactor = $match->factor2;
		}

		$log = array();

		// выигравшие ставки
		$bets = Bet::findByMatchAntTeam($match->id, $winerId);
		foreach ($bets as $b) {
			$win = $b->cost * $winFactor;

			//
			$balance = Balance::getByBet($b->id);
			if ($balance != null){
				$log[] = 'Balance('.$balance->id.') for bet('.$b->id.') all ready exists. skipped.';
				continue;
			}

			$balance = new Balance();
			$balance->uid   = $b->uid;
			$balance->betid = $b->id;
			$balance->cost  = $win;
			$balance->time  = time();
			$balance->type = 'prize';
			$balance->save ();

			$log[] = 'New balance('.$balance->id.') for bet('.$b->id.') saved!!!';

			// обновляем баланс пользователя
			$user = $balance->getUser ();
			if ($user == null){
				$log[] = 'User ('.$balance->uid.') not found. skipped.';
				continue;
			}

			$user->updateBalance();
			$user->save ();
		}

		$this->render ('calkmatch', array('log' => $log, 'matchId' => $match->id));
	}


	public function actionAdd ($match)
	{
		if ($match == 0) throw new HttpUrlException ('Match id not setted');
		if (Yii::app()->user->isGuest) throw new Exception ('Not auth user');

		$match = Match::model()->findByPk($match);
		if ($match == null) throw new Exception ('Match not found');

		$team1 = $match->getTeam1 ();
		if ($team1 == null) throw new Exception ('Team 1 setted wrong');

		$team2 = $match->getTeam2 ();
		if ($team2 == null) throw new Exception ('Team 2 setted wrong');

		$username = Yii::app()->user->name;
		$user = User::model()->findByLogin($username);
		if ($user == null)  throw new Exception ('User not found');

		$model = new BetForm();
		if(isset($_POST['BetForm']))
		{
			$model->attributes=$_POST['BetForm'];
			if($model->validate()) {

				$hasBets = false;
				if ($model->team1Bet > 0){
					$b = new Bet ();
					$b->uid = $user->id;
					$b->match = $match->id;
					$b->team = $team1->id;
					$b->cost = $model->team1Bet;
					$b->time = time();
					$b->type = 'bet';
					$b->save ();

					Yii::app()->user->setFlash('bet', 'Bet accepted');
					$hasBets = true;
				}

				if ($model->team2Bet > 0){
					$b = new Bet ();
					$b->uid = $user->id;
					$b->match = $match->id;
					$b->team = $team2->id;
					$b->cost = $model->team2Bet;
					$b->time = time();
					$b->type = 'bet';
					$b->save ();

					Yii::app()->user->setFlash('bet', 'Bet accepted');
					$hasBets = true;
				}

				if ($hasBets) $this->refresh();

			}
		}

		$data = array(
			'match' => $match,
			'team1' => $team1,
			'team2' => $team2,
			'model' => $model
		);

		$this->render('add', $data);
	}*/


	public function actionUser ()
	{
		if (Yii::app()->user->isGuest) {
			$url = $this->createUrl('/site/login');
			$this->redirect($url);
		}

		$username = Yii::app()->user->name;
		$user = User::model()->findByLogin($username);
		if ($user == null)  throw new Exception ('User not found');

		$data = array (
			'bets'  => Bet::findByUser($user->id),
		);

		$this->render("user", $data);
	}

	public function actionAdminList ()
	{
		$this->layout = "nocolums";

		if (!yii::app()->user->hasRole('admin')) {
			throw new CHttpException('Has no rights');
		}

		//
		$this->adminCommands();

		//
		$criteria = $this->createCriteria4AdminList();

		$data = array (
			'bets'      => Bet::model()->findAll($criteria),
			'betsCount' => Bet::model()->count($criteria),
			'criteria'  => $criteria, // только для вывода фильтров
		);

		$this->render ('adminlist', $data);
	}

	protected function createCriteria4AdminList ()
	{
		$criteria = new CDbCriteria();
		$criteria->order = "time DESC";
		$criteria->params = array();

		$uid = yii::app()->getRequest()->getParam('uid', 0);
		if (intval($uid) > 0) {
			$criteria->addCondition('uid = :uid');
			$criteria->params[':uid'] = $uid;
		}

		$mid = yii::app()->getRequest()->getParam('mid', 0);
		if (intval($mid) > 0) {
			$criteria->addCondition('matchid = :mid');
			$criteria->params[':mid'] = $mid;
		}

		return $criteria;
	}

	public function adminCommands ()
	{
		$command = yii::app()->getRequest()->getParam('command', '');

		if ($command == 'cancel-balance')
		{
			$id = intVal(yii::app()->getRequest()->getParam('balanceid', 0));
			if ($id == 0) {
				yii::app()->user->setFlash ('adminlist-bad', 'Balance id sended wrong');
				$this->refresh();
			}

			$balance = Balance::model()->findByPk($id);
			if ($balance == null) {
				yii::app()->user->setFlash ('adminlist-bad', 'Balance witch Id '.$id.' not found');
				$this->refresh();
			}

			//
			$balance->cancel(true);

			yii::app()->user->setFlash ('adminlist', 'Balance '.$id.' canceled');
			$this->refresh();

		}

		if ($command == 'cancel-bet')
		{
			$id = intVal(yii::app()->getRequest()->getParam('betid', 0));
			if ($id == 0) {
				yii::app()->user->setFlash ('adminlist-bad', 'Bet id sended wrong');
				$this->refresh();
			}

			$bet = Bet::model()->findByPk($id);
			if ($bet == null) {
				yii::app()->user->setFlash ('adminlist-bad', 'Bet witch Id '.$id.' not found');
				$this->refresh();
			}

			//
			$bet->cancel();

			yii::app()->user->setFlash ('adminlist', 'Bet '.$bet->id.' canceled');
			$this->refresh();
		}
	}
} 