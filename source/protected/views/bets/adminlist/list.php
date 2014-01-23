
<table style="width: auto;">
	<thead>
	<tr>

		<th colspan="6" style="text-align: center;">bet</th>

		<th colspan="9" style="text-align: center; border-left: solid 2px black;">match</th>

		<th colspan="5" style="text-align: center; border-left: solid 2px black;">balance</th>

		<th rowspan="2" style="text-align: center; border-left: solid 2px black; width: 200px; vertical-align: middle;">ops</th>

	</tr>
	<tr>

		<th style="width: 20px; text-align: center; vertical-align: middle;">#</th>
		<th style="width: 75px;">user</th>
		<th style="width: 75px;">team</th>
		<th style="width: 50px;">cost</th>
		<th style="min-width: 100px; width: 100px;">time</th>
		<th style="min-width: 100px; width: 100px;"><b style="color: red;">cancel</b></th>

		<th style="width: 25px; text-align: center; border-left: solid 2px black;">mid</th>
		<th style="width: 75px; text-align: right;">team1</th>
		<th style="width: 25px; text-align: center;">r</th>
		<th style="width: 25px;"></th>
		<th style="width: 25px; text-align: center;">r</th>
		<th style="width: 75px;">team2</th>
		<th style="min-width: 100px; width: 100px;">begin time</th>
		<th style="min-width: 100px; width: 100px;"><b style="color: green">result</b></th>
		<th style="min-width: 100px; width: 100px;"><b style="color: red">cancel</b></th>



		<th style="width: 25px; border-left: solid 2px black; text-align: center;">bid</th>
		<th style="width: 100px;">user</th>
		<th style="width: 50px;">cost</th>
		<th style="min-width: 100px; width: 100px;">time</th>
		<th style="min-width: 100px; width: 100px;"><b style="color: red;">cancel</b></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($bets as $b) : ?>
		<tr>
			<td style="text-align: center;"><?=$b->id ?></td>

			<td>
				<?php $user = $b->getUser (); ?>
				<?php if ($user != null) : ?>
					<?=$user->login ?>
				<?php else : ?>
					<b style="color: red;">user <?=$b->uid ?> not found</b>
				<?php endif; ?>
			</td>

			<td>
				<?php $team = $b->getTeam(); ?>
				<?php if ($team != null) : ?>
					<?=$team->shortname ?>
				<?php else : ?>
					<b style="color: red;">team not found</b>
				<?php endif; ?>
			</td>
			<td><?=$b->cost ?></td>
			<td><?=date("d.m.Y H:i", $b->time) ?></td>
			<td>
				<?php if ($b->canceltime > 0) : ?>
					<b style="color: red;"><?=date("d.m.Y H:i", $b->canceltime) ?></b>
				<?php endif; ?>
			</td>


			<td style="text-align: center; border-left: solid 2px black;"><?=$b->matchid ?></td>

			<?php $match = $b->getMatch(); ?>
			<?php if ($match != null) : ?>

				<td style="text-align: right">
					<?php $team1 = $match->getTeam1(); ?>
					<?php if ($team1 != null) : ?>
						<?=$team1->shortname ?>
					<?php else : ?>
						<b style="color: red;">team 1 not found</b>
					<?php endif; ?>
				</td>

				<td style="text-align: center">
					<?php if ($match->result1 != null) : ?>
						<?=$match->result1 ?>
					<?php endif; ?>
				</td>

				<td>vs</td>

				<td style="text-align: center">
					<?php if ($match->result2 != null) : ?>
						<?=$match->result2 ?>
					<?php endif; ?>
				</td>


				<td>
					<?php $team2 = $match->getTeam2(); ?>
					<?php if ($team2 != null) : ?>
						<?=$team2->shortname ?>
					<?php else : ?>
						<b style="color: red;">team 2 not found</b>
					<?php endif; ?>
				</td>

				<td>
					<?=date('d.m.Y H:s', $match->begintime) ?>
				</td>

				<td>
					<?php if ($match->resulttime > 0) : ?>
						<b style="color: green;"><?=date('d.m.Y H:s', $match->resulttime) ?></b>
					<?php endif; ?>
				</td>

				<td>
					<?php if ($match->canceltime > 0) : ?>
						<b style="color: red;"><?=date('d.m.Y H:s', $match->canceltime) ?></b>
					<?php endif; ?>
				</td>



			<?php else : ?>
				<td style="color: red;">Match <?=$b->matchid ?> not found</td>
			<?php endif; ?>


			<?php $balance = $b->getBalance(); ?>
			<?php if ($balance == null) : ?>
				<td style="border-left: solid 2px black;" colspan="5"></td>
			<?php else :  ?>
				<td style="border-left: solid 2px black; text-align: center;"><?=$balance->id ?></td>

				<td>
					<?php $user = $balance->getUser(); ?>
					<?php if ($user == null) : ?>
						<b style="color: red;">user <?=$balance->uid ?> not found</b>
					<?php else : ?>
						<?=$user->login ?>
					<?php endif; ?>
				</td>

				<td><?=$balance->cost ?></td>

				<td><?=date('d.m.Y H:s', $balance->time) ?></td>

				<td>
					<?php if ($balance->canceltime > 0) : ?>
						<b style="color: red;"><?=date('d.m.Y H:s', $balance->canceltime) ?></b>
					<?php endif; ?>
				</td>

			<?php endif; ?>

			<td style="text-align: left; border-left: solid 2px black;">
				<?php if (!$b->isCanceled()) : ?>
					<a href="javascript:cancelBet(<?=$b->id ?>)">cancel bet</a>
				<?php endif; ?>

				<?php if ($balance != null && !$balance->isCanceled()) : ?>
					| <a href="javascript:cancelBalance(<?=$balance->id ?>)">cancel balance</a>
				<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
</table>