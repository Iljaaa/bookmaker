<?php
$this->pageTitle = "Match: ".$team1->shortname.' vs '.$team2->shortname;
?>

<h1>Match</h1>

<?=CHtml::hiddenField('matchid', $match->id) ?>

<?php
$flash = Yii::app()->user->getFlash('bet');
if (is_array($flash) && count($flash) > 0) : ?>
	<div class="f-message f-message-success">
		<?php foreach ($flash as $f) : ?><?=$f ?><br /><?php endforeach; ?>
	</div>
<?php endif; ?>



<?php
$class = 'f-message';
$cap = '<span style="font-weight: normal;">Math starting: </span>'.$match->getBeginTime();
if ($match->isCanceled()) {
	$class .= ' f-message-error';
	$cap = 'Match canceled';
}
else {
	if ($match->isFinish()) {
		$class .= ' f-message-success';
		$cap = 'Match Finished';
	}
	else {
		if ($match->isGo()) {
			// $class .= ' f-message-error';
			$cap = 'Match already go<br /><span style="font-weight: normal;">start at : '.$match->getBeginTime().'</span>';
		}
	}
}


?>

<h2 class="<?=$class ?>"><?=$cap ?></h2>

<table style="width: auto;">
	<tbody>
	<?php if ($match->isFinish()) : ?>
	<tr>
		<td>
			<h1 style="color: red; text-align: center; margin: 0">
				<?php if ($match->result1 > $match->result2) : ?>
					-&nbsp;=&nbsp;<?=$match->result1 ?>&nbsp;=&nbsp;-
				<?php else : ?>
					<?=$match->result1 ?>
				<?php endif; ?>
			</h1>
		</td>
		<td style="text-align: center; vertical-align: middle">:</td>
		<td>
			<h1 style="color: red; text-align: center; margin: 0">
				<?php if ($match->result2 > $match->result1) : ?>
					-&nbsp;=&nbsp;<?=$match->result2 ?>&nbsp;=&nbsp;-
				<?php else : ?>
					<?=$match->result2 ?>
				<?php endif; ?>
			</h1>
		</td>
	</tr>
	<?php endif; ?>
	<tr>
		<td style="width: 300px; text-align: center;">
			<img src="<?=$team1->getImageUrl() ?>" height="200" />
			<h2 style="text-align: center;"><?=$team1->name ?></h2>
		</td>
		<td style="vertical-align: middle">VS</td>
		<td style="width: 300px; text-align: center;">
			<img src="<?=$team2->getImageUrl() ?>" height="200" />
			<h2 style="text-align: center;"><?=$team2->name ?></h2>
		</td>
	</tr>
	<tr>
		<td style="text-align: center;" colspan="5">
			<?php $champ = $match->getChamp(); ?>
			<?php if ($champ != null) : ?>
				<b><?=$champ->name ?></b>
			<?php endif; ?>
		</td>
	</tr>
	</tbody>
</table>


<table style="width: auto;">
	<tbody>
		<tr>
			<th rowspan="2" style="padding: 0 20px; vertical-align: middle;">Begin time: </th>
			<td>CET</td>
			<td><?=date('d.m.Y H.i', $match->begintime); ?></td>
			<td rowspan="2" style="vertical-align: middle; padding-left: 30px;"><?=$match->getRemainingTimeString() ?> left</td>
		</tr>
		<tr>
			<td>MOS</td>
			<td><?=date('d.m.Y H.i', ($match->begintime+(3*3600))); ?></td>
		</tr>
	</tbody>
</table>


<?php $this->renderPartial('/matches/view/bet', array (
	'team1'         => $team1,
	'team2'         => $team2,
	'match'         => $match,
	'model'         => $model
)); ?>

<div style="height: 20px;"></div>

<?php $this->renderPartial('/matches/view/forecast', array (
	'match'         => $match
)); ?>


<?php /* if (!yii::app()->user->isGuest && !$match->isFinish()) : ?>
	<?php $this->renderPartial('/matches/view/result', array (
		'team1'         => $team1,
		'team2'         => $team2,
		'resultModel'   => $resultModel
	)); ?>
<?php endif; */ ?>

<?php $this->renderPartial('/matches/view/stat', array (
	'team1'         => $team1,
	'team2'         => $team2,
	'match'         => $match
)); ?>


<?php $this->renderPartial('/matches/view/techdata', array (
	'match'         => $match
)); ?>





