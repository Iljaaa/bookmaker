<h1>Bet form</h1>


<?php

$enabledForm = true;
if ($match->isFinish()) $enabledForm = false;
if ($match->begintime < time()) $enabledForm = false;
if ($match->isCanceled()) $enabledForm = false;
if ($match->factor1 == 1 || $match->factor2 == 2) $enabledForm = false;

?>


<?php if (yii::app()->user->isGuest) : ?>
<?php $enabledForm = false; ?>
<div class="f-message f-message-error">
	To make a bet you must be logged. Use <a href="<?=$this->createUrl('/site/login') ?>">login form</a>.
</div>
<?php endif; ?>


<?=CHtml::beginForm('', 'post') ?>
<?php if (count($model->errors) > 0) : ?>
	<div class="f-message f-message-error">
		<?=CHtml::error($model, 'uid') ?>
		<?=CHtml::error($model, 'matchId') ?>
		<?=CHtml::error($model, 'team1Bet') ?>
		<?=CHtml::error($model, 'team2Bet') ?>
		<?=CHtml::error($model, 'team1Id') ?>
		<?=CHtml::error($model, 'team2Id') ?>
	</div>
<?php endif; ?>

<table>
	<thead>
	<tr>
		<th style="width: 25px;"></th>
		<th style="width: 250px;">Team</th>
		<th style="width: 50px; text-align: center;">Factor</th>
		<th>Bet</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td style="text-align: center;"><img src="<?=$team1->getIcoUrl() ?>" height="20" /></td>
		<td><?=$team1->name ?></td>
		<td style="text-align: center;"><?=$match->factor1 ?></td>
		<td>
			<?php $bet = Bet::getByUserMatchAndTeam($model->uid, $model->team1Id, $model->matchId); ?>
			<?php if ($bet != null) : ?>
				you have bet <b><?=$bet->cost ?></b>$

				<?php $balance = $bet->getBalance() ?>
				<?php if ($balance != null) : ?>
					<b style="color: green">And you win <?=($balance->cost - $bet->cost) ?>$</b>
				<?php endif; ?>
			<?php else : ?>

				<?php if ($match->isFinish()) : ?>
					Match finish, you have no bets
				<?php else : ?>

					<?php $data = array('class' => 'g-1') ?>
					<?php if (!$enabledForm) {
						$data['disabled'] = 'disabled';
						$data['style'] = 'background-color: gray;';
					} ?>

					<?=CHtml::activeTextField($model, 'team1Bet', $data) ?>
					<?=CHtml::error($model, 'team1Bet') ?>

				<?php endif; ?>

			<?php endif; ?>
		</td>
	</tr>
	<tr>
		<td style="text-align: center;"><img src="<?=$team2->getIcoUrl() ?>" height="20" /></td>
		<td><?=$team2->name ?></td>
		<td style="text-align: center;"><?=$match->factor2 ?></td>
		<td>
			<?php $bet = Bet::getByUserMatchAndTeam($model->uid, $model->team2Id, $model->matchId); ?>
			<?php if ($bet != null) : ?>
				you have bet <b><?=$bet->cost ?></b>$

				<?php $balance = $bet->getBalance() ?>
				<?php if ($balance != null) : ?>
					<b style="color: green">And you win <?=($balance->cost - $bet->cost) ?>$</b>
				<?php endif; ?>

			<?php else : ?>

				<?php if ($match->isFinish()) : ?>
					Match finish, you have no bets
				<?php else : ?>

					<?php $data = array('class' => 'g-1') ?>
					<?php if (!$enabledForm) {
						$data['disabled'] = 'disabled';
						$data['style'] = 'background-color: gray;';
					} ?>
					<?=CHtml::activeTextField($model, 'team2Bet', $data) ?>
					<?=CHtml::error($model, 'team2Bet') ?>

				<?php endif; ?>
			<?php endif; ?>
		</td>

	</tr>
	</tbody>
</table>

<?php if ($enabledForm) : ?>
	<div>
		<?=CHtml::submitButton('Make bet', array('class' => 'f-bu f-bu-success')) ?>
	</div>
<?php endif; ?>


<?=CHtml::endForm() ?>