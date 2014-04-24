<?php $this->breadcrumbs = array(
    yii::t('user_personal', 'User details') => $this->createUrl('/user'),
    yii::t('bets_user', 'My bets')
); ?>
<h1>My bets</h1>

<?=CHtml::beginForm ('filterForm', 'get') ?>
<?=Chtml::hiddenField('begin', $filterForm->begin) ?>
<?=Chtml::hiddenField('begin', $filterForm->finish) ?>
<?=Chtml::endForm() ?>


<?php if ($firstBet != null) : ?>
    First bet : <?=date('d.m.Y H:i', $firstBet->time) ?>
<?php endif; ?>

<div>
    <?php if ($firstBet->time < $filterForm->begin) : ?>
        <a href="<?=$this->createUrl('/mybets') ?>?m=<?=($filterForm->month - 1) ?>"><< <?=yii::t('bets_user', 'Earlier') ?></a>
    <?php endif; ?>

    <?=date('d.m.Y H:i', $filterForm->begin) ?> - <?=date('d.m.Y H:i', $filterForm->finish) ?>

    <?php if ($filterForm->month < BetsFilterForm::getCurrentYear()) : ?>
        <a href="<?=$this->createUrl('/mybets') ?>?m=<?=($filterForm->month + 1) ?>"><?=yii::t('bets_user', 'Later') ?> >></a>
    <?php endif; ?>
</div>






<?php if (isset($bets) && count($bets) > 0) : ?>

	<table>
		<thead>
			<tr>
				<th style="width: 50px;">#</th>
				<th>Match</th>
                <th style="width: 120px;">Bet time</th>
				<th style="width: 300px;">Bet</th>
				<th></th>
			</tr>
		</thead>
		<tbody>
			<?php
            $prevTime = 0;
            foreach ($bets as $b) : ?>

                <?php foreach ($payments as $p) : ?>
                    <?php if ($p->time <= $b->time && $p->time >= $prevTime) : ?>
                        <tr><td>aaa</td></tr>
                    <?php endif; ?>
                <?php endforeach; ?>

                <?php

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
                        <?=date('d.m.Y H:i', $b->time) ?>
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
			<?php
            $prevTime = $b->time;
            endforeach; ?>
		</tbody>
	</table>

<?php else : ?>
	<p>No bets</p>
<?php endif; ?>