<h1>My bets</h1>

<?php if (isset($bets) && count($bets) > 0) : ?>

	<table>
		<thead>
			<tr>
				<th style="width: 50px;">#</th>
				<th>Match</th>
				<th style="width: 300px;">Bet</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php foreach ($bets as $b) :

				$match = $b->getMatch();
				if ($match == null) continue;

				$matchTeam1 = $match->getTeam1 ();
				if ($matchTeam1 == null) continue;

				$matchTeam2 = $match->getTeam2 ();
				if ($matchTeam2 == null) continue;

				$betTeam = $b->getTeam();
				if ($betTeam == null) continue;

				$matchWinnerId = $match->getWinnerId();
				$matchWinner = $match->getWinner();

				?>
				<tr>
					<td>#<?=$b->id ?></td>
					<td>
						<?php if ($matchWinnerId == $matchTeam1->id) : ?>
							<b><?=$matchTeam1->shortname ?></b>
						<?php else : ?>
							<?=$matchTeam1->shortname ?>
						<?php endif; ?>

						<?php if ($match->isFinish()) : ?>
							[<?=$match->result1 ?>]
						<?php endif; ?>

						vs

						<?php if ($match->isFinish()) : ?>
							[<?=$match->result2 ?>]
						<?php endif; ?>

						<?php if ($matchWinnerId == $matchTeam2->id) : ?>
							<b><?=$matchTeam2->shortname ?></b>
						<?php else : ?>
							<?=$matchTeam2->shortname ?>
						<?php endif; ?>
					</td>
					<td>
						you bet <b><?=$b->cost ?>$</b> on <b><?=$betTeam->shortname ?></b>
						<?php $balance = $b->getBalance() ?>
						<?php if ($balance != null) : ?>
							<b style="color: green">And you win <?=($balance->cost - $b->cost) ?>$</b>
						<?php endif; ?>
					</td>
					<td>
						<a href="<?=$match->getUrl() ?>">match info
					</td>
				</tr>
			<?php endforeach; ?>
		</tbody>
	</table>

<?php else : ?>
	<p>No bets</p>
<?php endif; ?>