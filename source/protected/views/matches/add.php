<?php
$hours = array(
	0       => 0,
	3600    => 1,
	7200    => 2,
	10800   => 3,
	14400   => 4,
	18000   => 5,
	21600   => 6,
	25200   => 7,
	28800   => 8,
	32400   => 9,
	36000   => 10,
	39600   => 11,
	43200   => 12,
	46800   => 13,
	50400   => 14,
	54000   => 15,
	57600   => 16,
	61200   => 17,
	64800   => 18,
	68400   => 19,
	72000   => 20,
	75600   => 21,
	79200   => 22,
	82800   => 23
);

$minutes = array(
	0       => 0,
	900     => 15,
	1800    => 30,
	2700    => 45
);

?>

<script type="text/javascript">
	$(document).ready(function() {
		$( "#MatchForm_begindate" ).datepicker({
			dateFormat: "dd.mm.yy"
		});
	});
</script>

<h1>Создание матча</h1>


<?=CHtml::beginForm(); ?>

<div class="f-row">
<label style="width: 135px;">Teams</label>
<div class="f-input" style="margin-left: 145px">
	<table>
		<thead>
			<tr>
				<th style="width: 50px;"></th>
				<th style="width: 310px;"></th>
				<th>Factor</th>
			</tr>
		</thead>
		<tbody>
			<tr>
				<td><?=CHtml::activeLabel($model,'team1'); ?></td>
				<td>
					<?=CHtml::activeDropDownList($model, 'team1', $model->getTeamsList(), array('class' => 'g-4')) ?>
					<?=CHtml::error($model, 'team1') ?>
				</td>
				<td>
					<?=CHtml::activeTextField($model, 'factor1', array('class' => 'g-1')) ?>
					<?=CHtml::error($model, 'factor1') ?>
				</td>
			</tr>
			<tr>
				<td><?=CHtml::activeLabel($model,'team2'); ?></td>
				<td>
					<?=CHtml::activeDropDownList($model, 'team2', $model->getTeamsList(), array('class' => 'g-4')) ?>
					<?=CHtml::error($model, 'team2') ?>
				</td>
				<td>
					<?=CHtml::activeTextField($model, 'factor2', array('class' => 'g-1')) ?>
					<?=CHtml::error($model, 'factor2') ?>
				</td>
			</tr>
		</tbody>
	</table>
</div>
</div>

	<div class="f-row">
		<label><?=CHtml::activeLabel($model,'begindate'); ?></label>
		<div class="f-input">
			<?=CHtml::activeTextField($model, 'begindate') ?>


			<?=CHtml::activeDropDownList($model, 'begintimehours', $hours) ?>
			<?=CHtml::activeDropDownList($model, 'begintimeminets', $minutes) ?>


			<?=CHtml::error($model, 'begindate') ?>
			<?=CHtml::error($model, 'begintimehours') ?>
			<?=CHtml::error($model, 'begintimeminets') ?>
		</div>
	</div>

	<div class="f-row">
		<label><?=CHtml::activeLabel($model,'champ'); ?></label>
		<div class="f-input">
			<?=CHtml::activeDropDownList($model, 'champ', $model->getChampsList(), array('class' => 'g-4')) ?>
			<?=CHtml::error($model, 'champ') ?>
		</div>
	</div>

	<div class="f-actions">
		<button type="submit" class="f-bu f-bu-success">Save</button>
	</div>


<?=CHtml::endForm(); ?>