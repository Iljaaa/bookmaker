<h1>Set match result (<?=$match->id ?>)</h1>

<?php $flash = yii::app()->user->getFlash('match-result', ''); ?>
<?php if ($flash != '') : ?>
	<div class="f-message f-message-error"><?=$flash ?></div>
<?php endif; ?>

<?=CHtml::beginForm('', 'post') ?>

<?php if (count($model->errors) > 0) : ?>
	<div class="f-message f-message-error">
		<?=CHtml::error($model, 'matchId') ?>
		<?=CHtml::error($model, 'team1Result') ?>
		<?=CHtml::error($model, 'team2Result') ?>
	</div>
<?php endif; ?>

<table>
	<thead>
	<tr>
		<th style="width: 25px;"></th>
		<th style="width: 250px;">Team</th>
		<th>Bet</th>
	</tr>

	</thead>
	<tbody>
	<tr>
		<?php $team1 = $model->getTeam1() ?>
		<td style="text-align: center;"><img src="<?=$team1->getIcoUrl() ?>" height="20" /></td>
		<td>

			<?=$team1->name ?>
		</td>
		<td>
			<?=CHtml::activeTextField($model, 'team1Result', array('class' => 'g-1')) ?>
		</td>
	</tr>
	<tr>
		<?php $team2 = $model->getTeam2() ?>
		<td style="text-align: center;"><img src="<?=$team2->getIcoUrl() ?>" height="20" /></td>
		<td><?=$team2->name ?></td>
		<td>
			<?=CHtml::activeTextField($model, 'team2Result', array('class' => 'g-1')) ?>
		</td>
	</tr>
	</tbody>
</table>

<div>
	<?=CHtml::submitButton('Submit result', array('class' => 'f-bu f-bu-success')) ?>
</div>


<?=CHtml::hiddenField('command', 'set-result') ?>
<?=CHtml::endForm(); ?>
