<div style="margin-top: 50px;">
	<h1>Result form</h1>
	<?=CHtml::beginForm('', 'post') ?>

	<?php if (count($resultModel->errors) > 0) : ?>
		<div class="f-message f-message-error">
			<?=CHtml::error($resultModel, 'matchId') ?>
			<?=CHtml::error($resultModel, 'team1Result') ?>
			<?=CHtml::error($resultModel, 'team2Result') ?>
			<?=CHtml::error($resultModel, 'team1Id') ?>
			<?=CHtml::error($resultModel, 'team2Id') ?>
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
			<td style="text-align: center;"><img src="<?=$team1->getIcoUrl() ?>" height="20" /></td>
			<td><?=$team1->name ?></td>
			<td>
				<?=CHtml::activeTextField($resultModel, 'team1Result', array('class' => 'g-1')) ?>
			</td>
		</tr>
		<tr>
			<td style="text-align: center;"><img src="<?=$team2->getIcoUrl() ?>" height="20" /></td>
			<td><?=$team2->name ?></td>
			<td>
				<?=CHtml::activeTextField($resultModel, 'team2Result', array('class' => 'g-1')) ?>
			</td>
		</tr>
		</tbody>
	</table>

	<div>
		<?=CHtml::submitButton('Submit result', array('class' => 'f-bu f-bu-success')) ?>
	</div>
	<?=CHtml::endForm() ?>
</div>