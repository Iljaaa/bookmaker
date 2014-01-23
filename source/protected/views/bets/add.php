<h1>Bet</h1>

<?php
$flash = Yii::app()->user->getFlash('bet');
if ($flash != '') :
?>
<div class="f-message f-message-success"><?=$flash ?></div>
<?php endif; ?>

<h2><span style="font-weight: normal;">Math started:</span> <?=$match->getBeginTime() ?></h2>

<table style="width: auto;">
	<tbody>
		<td style="width: 300px; text-align: center;">
			<img src="<?=$team1->getImageUrl() ?>" height="200" />
			<h2 style="text-align: center;"><?=$team1->name ?></h2>
		</td>
		<td style="vertical-align: middle">VS</td>
		<td style="width: 300px; text-align: center;">
			<img src="<?=$team2->getImageUrl() ?>" height="200" />
			<h2 style="text-align: center;"><?=$team2->name ?></h2>
		</td>
	</tbody>
</table>


<?=CHtml::beginForm('', 'post') ?>
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
				<?=CHtml::activeTextField($model, 'team1Bet', array('class' => 'g-1')) ?>
				<?=CHtml::error($model, 'team1Bet') ?>
			</td>
		</tr>
		<tr>
			<td style="text-align: center;"><img src="<?=$team2->getIcoUrl() ?>" height="20" /></td>
			<td><?=$team2->name ?></td>
			<td style="text-align: center;"><?=$match->factor2 ?></td>
			<td>
				<?=CHtml::activeTextField($model, 'team2Bet', array('class' => 'g-1')) ?>
				<?=CHtml::error($model, 'team2Bet') ?>
			</td>
		</tr>
	</tbody>
</table>

<div>
	<?=CHtml::submitButton('Make bet', array('class' => 'f-bu f-bu-success')) ?>
</div>

<?=CHtml::endForm() ?>

