<?php
$this->breadcrumbs=array(
 'Matches' => $this->createUrl('/matches'),
); ?>

<h1>Matches</h1>
<?php if (isset($matches) && count($matches) > 0) : ?>
	<table>
		<tbody>
		<?php $i = 1 ?>
		<?php

			$dt = null;
			foreach ($matches as $m) :
				$team1 = $m->getTeam1();
				$team2 = $m->getTeam2();
				if ($team1 == null || $team2 == null) continue;

				$matchWinnerId = $m->getWinnerId();

				$date = date('d.m.Y', $m->begintime);
			?>

			<?php if ($date != $dt) : ?>
				<?php $dt = $date; ?>

				<tr>
					<td colspan="10"><h2 style="margin: 12px 0;"><?=$dt ?></h2></td>
				</tr>

				<tr>
					<td style="width: 20px; text-align: center; vertical-align: middle">#</td>
					<th style="width: 50px;text-align: center;">factor</th>
					<th style="text-align: right;" colspan="2">Team 1</th>
					<td></td>
					<td style="width: 20px;"></td>
					<td></td>
					<th style="text-align: left;" colspan="2">Team 2</th>
					<th style="width: 50px;text-align: center;">factor</th>
					<td style="width: 150px">Begin time (cet)</td>
					<td style="width: 150px">Champ</td>
					<td style="width: 150px"></td>
				</tr>

			<?php endif; ?>

			<tr>
				<td style="text-align: center;"><?=$i ?></td>
				<td style="text-align: center;"><?=$m->factor1 ?></td>
				<td style="text-align: right; width: 150px;">
					<?php if ($team1->id == $matchWinnerId) : ?>
						<b style="color: red;"><?=$team1->shortname ?></b>
					<?php else : ?>
						<?=$team1->shortname ?>
					<?php endif; ?>
				</td>
				<td style="text-align: center; width: 25px;">
					<img src="<?=$team1->getIcoUrl() ?>" style="width: 20px;" />
				</td>
				<td style="width: 20px; text-align: center;">
					<?php if ($m->isFinish()) echo $m->result1 ?>
				</td>
				<td style="text-align: center;">vs</td>
				<td style="width: 20px; text-align: center;">
					<?php if ($m->isFinish()) echo $m->result2 ?>
				</td>
				<td style="text-align: center; width: 25px;">
					<img src="<?=$team2->getIcoUrl() ?>" style="width: 20px;" />
				</td>
				<td style="text-align: left; width: 150px;">
					<?php if ($team2->id == $matchWinnerId) : ?>
						<b style="color: red;"><?=$team2->shortname ?></b>
					<?php else : ?>
						<?=$team2->shortname ?>
					<?php endif; ?>
				</td>
				<td style="text-align: center;"><?=$m->factor2 ?></td>
				<td>
					<time><?=$m->getBeginTime() ?></time>
				</td>
				<td>
					<?php $champ = $m->getChamp() ?>
					<?php if ($champ != null) : ?>
						<?=$champ->name ?>
					<?php endif; ?>
				</td>
				<td>
					<a href="<?=$this->createUrl('/match/'.$m->id) ?>">math info</a>
				</td>
			</tr>
			<?php $i++; ?>
		<?php endforeach; ?>
		</tbody>
	</table>
<?php else : ?>
	<h2>Матчей не запланировано</h2>
<?php endif; ?>
