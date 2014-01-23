<?php $calculationData = $match->getCalckData (); ?>

<h1>Stat</h1>


<table>
	<thead>
	<tr>
		<th></th>
		<th style="width: 150px; text-align: center;"><?=$team1->shortname ?> (<?=$team1->id ?>)</th>
		<th style="width: 150px; text-align: center;"><?=$team2->shortname ?> (<?=$team2->id ?>)</th>
		<th style="width: 150px; text-align: center;">Итого</th>
	</tr>
	</thead>
	<tbody>
	<tr>
		<td rowspan="2" style="vertical-align: middle">Статистика по прямым матчам</td>
		<td style="text-align: center;">
			<?=(($calculationData['matches'][$team1->id])) ?>
		</td>
		<td style="text-align: center;">
			<?=($calculationData['matches'][$team2->id]) ?>
		</td>
		<td style="text-align: center;">
			<?=($calculationData['matches']['count']) ?>
		</td>
	</tr>
	<tr>
		<td style="text-align: center;">
			<?=(($calculationData['matchesResult'][$team1->id]) * 100) ?>%
		</td>
		<td style="text-align: center;">
			<?=(($calculationData['matchesResult'][$team2->id]) * 100) ?>%
		</td>
		<td style="text-align: center;">
			<?=(($calculationData['matchesResult'][$team2->id] + $calculationData['matchesResult'][$team1->id]) * 100) ?>%
		</td>
	</tr>
	<tr>
		<td>Средний процент по стыковым матчам</td>
		<td style="text-align: center;">
			<?=(($calculationData['comparedMatchesSummaryResult'][$team1->id]) * 100) ?>%
		</td>
		<td style="text-align: center;">
			<?=(($calculationData['comparedMatchesSummaryResult'][$team2->id]) * 100) ?>%
		</td>
		<td style="text-align: center;">
			<?=(($calculationData['comparedMatchesSummaryResult'][$team2->id] + $calculationData['comparedMatchesSummaryResult'][$team1->id]) * 100) ?>%
		</td>
	</tr>
	<tr>
		<td>Средние проценты</td>
		<td style="text-align: center;">
			<?=(($calculationData['koef']['average'][$team1->id]) * 100) ?>%
		</td>
		<td style="text-align: center;">
			<?=(($calculationData['koef']['average'][$team2->id]) * 100) ?>%
		</td>
		<td style="text-align: center;">
			<?=(($calculationData['koef']['average'][$team2->id] + $calculationData['koef']['average'][$team1->id]) * 100) ?>%
		</td>
	</tr>
	<tr>
		<td>Чистые коэфициенты</td>
		<td style="text-align: center;">
			100 / <?=(($calculationData['koef']['average'][$team1->id]) * 100) ?><br />
			<?=(($calculationData['koef']['clear'][$team1->id])) ?>
		</td>
		<td style="text-align: center;">
			100 / <?=(($calculationData['koef']['average'][$team2->id]) * 100) ?><br />
			<?=(($calculationData['koef']['clear'][$team2->id])) ?>
		</td>
		<td style="text-align: center;">
			<?=(($calculationData['koef']['clear'][$team2->id] + $calculationData['koef']['clear'][$team1->id])) ?>
		</td>
	</tr>
	<tr>
		<td>Коэффициенты на игру</td>
		<td style="text-align: center;">
			0.8 * <?=(($calculationData['koef']['clear'][$team1->id])) ?> =
			<b><?=(($calculationData['koef']['walrus'][$team1->id])) ?></b>
		</td>
		<td style="text-align: center;">
			0.8 * <?=(($calculationData['koef']['clear'][$team2->id])) ?> =
			<b><?=(($calculationData['koef']['walrus'][$team2->id])) ?></b>
		</td>
		<td style="text-align: center;">
			<b><?=(($calculationData['koef']['walrus'][$team2->id] + $calculationData['koef']['walrus'][$team1->id])) ?></b>
		</td>
	</tr>
	<tr style="background-color: #F3F3F3;">
		<td>Коэффициенты на игру округленные</td>
		<td style="text-align: center;">
			<b><?=sprintf("%0.3f", (($calculationData['koef']['walrusfinish'][$team1->id]))) ?></b>
		</td>
		<td style="text-align: center;">
			<b><?=sprintf("%0.3f", (($calculationData['koef']['walrusfinish'][$team2->id]))); ?></b>
		</td>
		<td style="text-align: center;">
			<b><?=sprintf("%0.3f", (($calculationData['koef']['walrusfinish'][$team2->id] + $calculationData['koef']['walrusfinish'][$team1->id]))); ?></b>
		</td>
	</tr>
	<tr>
		<td>Проценты на игру</td>
		<td style="text-align: center;">
			<?=(($calculationData['koef']['walruspercent'][$team1->id]) * 100) ?>%
		</td>
		<td style="text-align: center;">
			<?=(($calculationData['koef']['walruspercent'][$team2->id]) * 100) ?>%
		</td>
		<td style="text-align: center;">
			<?=(($calculationData['koef']['walruspercent'][$team2->id] + $calculationData['koef']['walruspercent'][$team1->id]) * 100) ?>%
		</td>
	</tr>
	<tr>
		<td>Разница процентов</td>
		<td style="text-align: center;">

		</td>
		<td style="text-align: center;">

		</td>
		<td style="text-align: center;">
			<b><?=((($calculationData['koef']['walruspercent'][$team2->id] + $calculationData['koef']['walruspercent'][$team1->id]) * 100) - 100) ?></b>%
		</td>
	</tr>
	</tbody>
</table>

<h3>Прямые матчи</h3>
<?php $personalMatches = Match::findMatchesBetweenTeams($team1->id, $team2->id, $match->begintime) ?>
<?php if (count($personalMatches) == 0) : ?>
	<p>No matches between teams</p>
<?php else : ?>
	<table style="width: auto;">
		<thead>
		</thead>
		<tbody>
			<?php foreach ($personalMatches as $m) :
				$team1 = $m->getTeam1();
				$team2 = $m->getTeam2();
				$winnerId = $m->getWinnerId();
				?>
				<tr>
					<td style="width: 100px; text-align: right">
						<?php if ($m->team1 == $winnerId) : ?>
							<b><?=$team1->shortname ?></b>
						<?php else : ?>
							<?=$team1->shortname ?>
						<?php endif; ?>
					</td>
					<td style="width: 20px; text-align: center"><?=$m->result1 ?></td>
					<td style="width: 20px; text-align: center">vs</td>
					<td style="width: 20px; text-align: center"><?=$m->result2 ?></td>
					<td style="width: 100px;">
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
<?php endif; ?>

<h3>Стыковочные матчи</h3>


<table style="width: auto;">
	<thead>
		<tr>
			<th style="width: 150px; text-align: right;">team 1</th>
			<th style="width: 70px; text-align: center;">w</th>
			<th style="width: 70px; text-align: center;">count</th>
			<th style="width: 70px; text-align: center;">w cm1</th>
			<th style="width: 150px; text-align: center;">compare team</th>
			<th style="width: 70px; text-align: center;">w cm2</th>
			<th style="width: 70px; text-align: center;">count</th>
			<th style="width: 70px; text-align: center;">w</th>
			<th style="width: 150px;">team 2</th>
		</tr>
	</thead>
	<tbody>

		<?php
		// суммарный процент по команде 2 по стыковочнфм матчам
		$summaryPercents = array (
			0       => 0,
			2       => 0,
			'count' => 0
		);

		?>





		<?php yii::app()->firephp->log ($calculationData, '$calculationData'); ?>

		<?php // $compareMatches = $match->getCompareMathes (); ?>

		<?php foreach ($calculationData['comparedMatches'] as $cmId => $cm) : ?>
			<tr>
				<td style="text-align: right;">
					<?php if ($team1->id == $cm[0]) :  ?>
						<?=$team1->shortname ?> (<?=$cm[0] ?>)
					<?php endif; ?>
					<?php if ($team2->id == $cm[0]) :  ?>
						<?=$team2->shortname ?> (<?=$cm[0] ?>)
					<?php endif; ?>

				</td>

				<?php

				$matchCode = $cm[0].'-'.$cm[1];
				$matchData = $calculationData['comparedMatchesData'][$matchCode];

				?>

				<td style="text-align: center;"><?=$matchData[$cm[0]]; ?></td>
				<td style="text-align: center;"><?=$matchData['count']; ?></td>
				<td style="text-align: center;"><?=$matchData[$cm[1]]; ?></td>


				<td style="text-align: center;">
					<?php $cTeam = Team::model()->findByPk($cm[1]); ?>
					<?=$cTeam->shortname ?> (<?=$cm[1] ?>)
				</td>


				<?php

				$matchCode = $cm[1].'-'.$cm[2];
				$matchData = $calculationData['comparedMatchesData'][$matchCode];

				?>


				<td style="text-align: center;"><?=$matchData[$cm[1]]; ?></td>
				<td style="text-align: center;"><?=$matchData['count']; ?></td>
				<td style="text-align: center;"><?=$matchData[$cm[2]]; ?></td>


				<td>
					<?php if ($team1->id == $cm[2]) :  ?>
						<?=$team1->shortname ?> (<?=$cm[2] ?>)
					<?php endif; ?>
					<?php if ($team2->id == $cm[2]) :  ?>
						<?=$team2->shortname ?> (<?=$cm[2] ?>)
					<?php endif; ?>
				</td>
			</tr>

			<?php

			$matchCode = $cm[0].'-'.$cm[1];
			$matchData = $calculationData['comparedMatchesData'][$matchCode];

			$winRate0 = (($matchData[$cm[0]] / $matchData['count']) * 100);
			$winRate1 = (($matchData[$cm[1]] / $matchData['count']) * 100);


			$matchCode = $cm[1].'-'.$cm[2];
			$matchData = $calculationData['comparedMatchesData'][$matchCode];

			$winRate11 = (($matchData[$cm[1]] / $matchData['count']) * 100);
			$winRate2 = (($matchData[$cm[2]] / $matchData['count']) * 100);

			?>


			<tr>
				<td></td>

				<?php ?>
				<td style="text-align: center;"><?=$winRate0 ?>%</td>
				<td></td>
				<td style="text-align: center;"><?=$winRate1 ?>%</td>

				<td style="text-align: center;">|</td>


				<td style="text-align: center;"><?=$winRate11 ?>%</td>
				<td></td>
				<td style="text-align: center;"><?=$winRate2 ?>%</td>
				<td></td>
			</tr>


			<?php $result = $calculationData['comparedMatchesResult'][$cmId]; ?>
			<tr>
				<td></td>
				<td style="text-align: center; color: green;"><b><?=($result[$cm[0]] * 100) ?>%</b></td>
				<td></td>
				<td></td>
				<td style="text-align: center;">|</td>
				<td></td>
				<td></td>
				<td style="text-align: center; color: green;"><b><?=($result[$cm[2]] * 100) ?>%</b></td>
				<td></td>
			</tr>

		<?php endforeach; ?>
	</tbody>
</table>


<?php /* $team1WR = $team1->getTeamWinRate($match->begintime); ?>
<?php $team2WR = $team2->getTeamWinRate($match->begintime); ?>
<?=$team1->shortname ?> win rate : <?=$team1WR; ?> (<?=$team1WR *100 ?>%)<br />
<?=$team2->shortname ?> win rate : <?=$team2WR; ?> (<?=$team2WR *100 ?>%)<br /><br />

<?php
$pureKoef1 = 0;
if ($team1WR > 0) $pureKoef1 = 1 / $team1WR;

$pureKoef2 = 0;
if ($team2WR > 0) $pureKoef2 = 1 / $team2WR;
?>
<?=$team1->shortname ?> pure koef : <?=$pureKoef1; ?><br />
<?=$team2->shortname ?> pure koef : <?=$pureKoef2; ?><br /><br />


<?php
$bookmakerKoef1 = $pureKoef1 * 0.7;
$bookmakerKoef2 = $pureKoef2 * 0.7;
?>
<?=$team1->shortname ?> bookmaker koef : <?=$bookmakerKoef1; ?> (<?=$pureKoef1 ?> * 0.7)<br />
<?=$team2->shortname ?> bookmaker koef : <?=$bookmakerKoef2; ?> (<?=$pureKoef2 ?> * 0.7)<br /><br />


<?php
$finalKoef1 = $bookmakerKoef1;
if ($finalKoef1 < 1) $finalKoef1 = 1;

$finalKoef2 = $bookmakerKoef2;
if ($finalKoef2 < 1) $finalKoef2 = 1;
?>

<?=$team1->shortname ?> final koef : <?=$finalKoef1; ?><br />
<?=$team2->shortname ?> final koef : <?=$finalKoef2; ?><br /><br />


<?=$team1->shortname ?> final winrate : <?=$finalKoef1 * 100; ?>%<br />
<?=$team2->shortname ?> final winrate : <?=$finalKoef2 * 100; ?>%<br /><br />

delta <?=(($pureKoef1 + $pureKoef2)) - (($finalKoef1 + $finalKoef2)) */ ?>


<?php $this->renderPartial('/matches/view/teamstat', array (
	'team'          => $team1,
	'match'         => $match
)); ?>

<?php $this->renderPartial('/matches/view/teamstat', array (
	'team'          => $team2,
	'match'         => $match
)); ?>

