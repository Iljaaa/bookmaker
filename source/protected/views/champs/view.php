<?php
$this->pageTitle = "Champ: ".$champ->name;
?>

<h1><?=$this->pageTitle ?></h1>

<?php
$flash = Yii::app()->user->getFlash('bet');
if (is_array($flash) && count($flash) > 0) : ?>
	<div class="f-message f-message-success">
		<?php foreach ($flash as $f) : ?><?=$f ?><br /><?php endforeach; ?>
	</div>
<?php endif; ?>


<?php if ($champ->description != "") : ?>
<div>
	<?=yii::app()->wiky->parse($champ->description); ?>
</div>
<?php endif; ?>

<h3>Teams by champ</h3>
<table>
	<thead>
	<tr>
		<th></th>
		<th></th>
		<th></th>
		<th>g</th>
		<th>w</th>
		<th>l</th>
		<th>d</th>
		<th>p</th>
	</tr>
	</thead>
	<tbody>
	<?php $i = 0; ?>
	<?php foreach ($order as $pointsCount) : ?>
		<?php $teamssss = $teamsByPoints[$pointsCount]; ?>
		<?php if (count($teamssss) > 0) : ?>
			<?php foreach ($teamssss as $teamId) : ?>
			<?php $i++; ?>
			<tr>
				<td style="width: 25px;">#<?=$i ?></td>
				<td style="width: 25px;"><?=$teamId ?></td>
				<td><?=$teams[$teamId] ?></td>
				<td><?=$stat['games'][$teamId] ?></td>
				<td>
					<?php $teamWinsCount = 0; ?>
					<?php if (isset($stat['wins'][$teamId]) > 0) $teamWinsCount = $stat['wins'][$teamId]; ?>
					<?=$teamWinsCount ?>
				</td>
				<td>
					<?=$stat['games'][$teamId] - $teamWinsCount; ?>
				</td>
				<td>
					<?=$teamWinsCount - ($stat['games'][$teamId] - $teamWinsCount); ?>
				</td>
				<td>
					<?php if (isset($stat['points'][$teamId]) > 0) : ?>
						<?=$stat['points'][$teamId] ?>
					<?php else : ?>
						0
					<?php endif; ?>
				</td>
			</tr>
			<?php endforeach; ?>
		<?php endif; ?>
	<?php endforeach; ?>

	<?php if (isset($teamssss) && count($teamssss) > 0) : ?>
		<?php $teamssss = $teamsByPoints[0]; ?>
		<?php foreach ($teamssss as $teamId) : ?>
			<?php $i++; ?>
			<tr>
				<td style="width: 25px;">#<?=$i ?></td>
				<td style="width: 25px;"><?=$teamId ?></td>
				<td><?=$teams[$teamId] ?></td>
				<td><?=$stat['games'][$teamId] ?></td>
				<td>
					<?php if (isset($stat['wins'][$teamId]) > 0) : ?>
						<?=$stat['wins'][$teamId] ?>
					<?php else : ?>
						0
					<?php endif; ?>
				</td>
				<td>-<?=$stat['games'][$teamId] ?></td>
				<td>-<?=$stat['games'][$teamId] ?></td>
				<td>0</td>
			</tr>
		<?php endforeach; ?>
	<?php endif; ?>

	</tbody>
</table>




<h3>Teams by champ</h3>
<table>
	<thead>
		<tr>
			<th></th>
			<th></th>
			<th></th>
			<th>g</th>
			<th>w</th>
			<th>p</th>
		</tr>
	</thead>
	<tbody>
	<?php $i = 0; ?>
	<?php foreach ($teams as $teamId => $teamName) :
			$i++;
		?>
		<tr>
			<td style="width: 25px;">#<?=$i ?></td>
			<td style="width: 25px;"><?=$teamId ?></td>
			<td><?=$teamName ?></td>
			<td><?=$stat['games'][$teamId] ?></td>
			<td>
				<?php if (isset($stat['wins'][$teamId]) > 0) : ?>
					<?=$stat['wins'][$teamId] ?>
				<?php else : ?>
					0
				<?php endif; ?>
			</td>
			<td>
				<?php if (isset($stat['points'][$teamId]) > 0) : ?>
					<?=$stat['points'][$teamId] ?>
				<?php else : ?>
					0
				<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>




<h3>Games by champ</h3>
<div>count : <?=$matchesCount ?> (witch result : <?=$matchesWitchResult ?>)</div>
<table style="margin-top: 0;">
	<tbody>
	<?php $day = 0; ?>
	<?php foreach ($matches as $m) :
		$team1 = $m->getTeam1();
		$team2 = $m->getTeam2();
		if ($team1 == null || $team2 == null) continue;

		$matchDay = date('d.m.Y', $m->begintime);
		?>

		<?php if ($day == null || $day != $matchDay) : ?>
			<?php $day = $matchDay; ?>
			<tr>
				<td colspan="10">
					<b style="color: red;"><?=$day ?></b>
				</td>
			</tr>
		<?php endif; ?>

		<tr>
			<td><?=$m->id ?></td>
			<td style="text-align: right; width: 100px;"><?=$team1->shortname ?></td>
			<td style="text-align: center; width: 25px;">
				<img src="<?=$team1->getIcoUrl() ?>" style="width: 20px;" />
			</td>
			<td style="text-align: center; width: 25px;"><?=$m->result1 ?></td>
			<td style="text-align: center;width: 20px">vs</td>
			<td style="text-align: center; width: 25px;"><?=$m->result2 ?></td>
			<td style="text-align: center; width: 25px;">
				<img src="<?=$team2->getIcoUrl() ?>" style="width: 20px;" />
			</td>
			<td style="text-align: left; width: 100px;"><?=$team2->shortname ?></td>
			<td>
				<?php if (date('d.m.Y', $m->begintime) == date('d.m.Y')) :  ?>
					Today <?=date('H:i', $m->begintime) ?>
				<?php else : ?>
					<time><?=$m->getBeginTime() ?></time>
				<?php endif; ?>
			</td>
			<td>
				<a href="<?=$this->createUrl('/match/'.$m->id) ?>">goto match</a>
			</td>

		</tr>

	<?php endforeach; ?>
	</tbody>
</table>




