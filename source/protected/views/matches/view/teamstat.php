<h3>Team <?=$team->shortname ?></h3>

<?php
$winsCount = $team->getAllWinGamesCount($match->begintime);
$allGamesCount = $team->getAllGamesCount($match->begintime);
?>

<table>
	<thead>
		<tr>
			<th style="width: 50px;"></th>
			<th style="width: 50px;">Matches</th>
			<th style="width: 150px; text-align: center;">Win rate</th>
			<th></th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>Wins: </td>
			<td style="text-align: center;"><?=$winsCount ?></td>
			<td rowspan="2" style="vertical-align: middle; text-align: center;">
				<?php if ($allGamesCount > 0) : ?>
					<b style="font-size: 200%; color: green"><?=number_format ((($winsCount/$allGamesCount) * 100), 2) ?>%</b>
				<?php else : ?>
					<b style="color: red;">error</b>
				<?php endif; ?>
			</td>
		</tr>
		<tr>
			<td>All:</td>
			<td style="text-align: center;"><?=$allGamesCount ?></td>
		</tr>
	</tbody>
</table>

<h4>Last games</h4>

<table>
<tbody>
<?php $matches = $team->getLastFinishedMatchesBeforeDate ($match->begintime); ?>
<?php
foreach ($matches as $m) :
	$team1 = $m->getTeam1();
	if ($team1 == null) continue;

	$team2 = $m->getTeam2();
	if ($team2 == null) continue;

	$winnerId = $m->getWinnerId();
	?>

	<tr>
		<td style="width: 100px; text-align: right; <?php if ($m->team1 != $team->id) : ?> color: silver;<?php endif; ?>">
			<?php if ($m->team1 == $winnerId) : ?>
				<b><?=$team1->shortname ?></b>
			<?php else : ?>
				<?=$team1->shortname ?>
			<?php endif; ?>
		</td>
		<td style="width: 20px; text-align: center">
			<?php if ($m->team1 == $winnerId) : ?>
				<b><?=$m->result1 ?></b>
			<?php else : ?>
				<?=$m->result1 ?>
			<?php endif; ?>
		</td>
		<td style="width: 20px; text-align: center">vs</td>
		<td style="width: 20px; text-align: center">
			<?php if ($m->team2 == $winnerId) : ?>
				<b><?=$m->result2 ?></b>
			<?php else : ?>
				<?=$m->result2 ?>
			<?php endif; ?>
		</td>
		<td style="width: 100px; <?php if ($m->team2 != $team->id) : ?> color: silver;<?php endif; ?>">
			<?php if ($m->team2 == $winnerId) : ?>
				<b><?=$team2->shortname ?></b>
			<?php else : ?>
				<?=$team2->shortname ?>
			<?php endif; ?>
		</td>
		<td><?=$m->getBeginTime() ?></td>
		<td>
			<a href="<?=$m->getUrl() ?>">match info</a>
		</td>
	</tr>

<?php endforeach; ?>
</tbody>
</table>