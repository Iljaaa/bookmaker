<script type="text/javascript" src="/js/matches_adminlist.js"></script>

<h1>Матчи инструмент адимнистратора</h1>

<?php $flash = yii::app()->user->getFlash('adminlist', ''); ?>
<?php if ($flash != '') : ?>
	<div class="f-message f-message-success"><?=$flash ?></div>
<?php endif; ?>

<?php $flash = yii::app()->user->getFlash('adminlist-bad', ''); ?>
<?php if ($flash != '') : ?>
	<div class="f-message f-message-error"><?=$flash ?></div>
<?php endif; ?>


<p>

	<?=CHtml::beginForm($this->createUrl('/matches/adminlist'), 'get') ?>

	<div class="f-row">
		<label style="width: 90px;">Team</label>
		<div class="f-input" style="margin-left: 100px;">
			<?=CHtml::activeDropDownList($filterForm, 'team', $filterForm->getTeamsList(), array('class' => 'g-4')) ?>
		</div>
	</div>

	<div class="f-row">
		<label style="width: 90px;">Champ</label>
		<div class="f-input" style="margin-left: 100px;">
			<?=CHtml::activeDropDownList($filterForm, 'champ', $filterForm->getChampsList(), array('class' => 'g-4')) ?>
		</div>
	</div>

	<div class="f-row">
		<div class="f-input" style="margin-left: 100px;">
			<?=CHtml::submitButton('Filter', array('class' => 'f-bu f-bu-default')); ?>
		</div>
	</div>

	<?=CHtml::endForm() ?>
</p>

<div>
	<a href="<?=$this->createUrl('/matches/add') ?>" class="f-bu f-bu-default">Add match</a>
	&nbsp;&nbsp;&nbsp;
	count : <?=$matchesCount ?>
</div>
<table style="margin-top: 7px;">
	<thead>
		<tr>
			<th style="width: 20px;">#</th>

			<th style="text-align: right">t1</th>
			<th style="width: 20px;"></th>

			<th>result</th>

			<th style="width: 20px;"></th>
			<th>t2</th>

			<th>factor</th>
			<th>champ</th>
			<th>begin time</th>
			<th>result time</th>
			<th>cancel time</th>
			<th style="width: 50px; text-align: center;">Bets</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($matches as $m) : ?>
		<tr>
			<td style="text-align: center;"><?=$m->id ?></td>
			<?php $t = $m->getTeam1(); ?>

			<td style="text-align: right"><?=$t->shortname;?></td>
			<td><img src="<?=$t->getIcoUrl() ?>" style="width: 20px;" /></td>

			<td style="text-align: center;">
				<?php if ($m->result1 > 0 || $m->result2 > 0) : ?>
					<?=$m->result1 ?> x <?=$m->result2 ?>
				<?php endif; ?>
			</td>

			<?php $t = $m->getTeam2(); ?>
			<td><img src="<?=$t->getIcoUrl() ?>" style="width: 20px;" /></td>
			<td><?=$t->shortname;?></td>

			<td><?=$m->factor1 ?> x <?=$m->factor2 ?></td>
			<td>
				<?php $champ = $m->getChamp() ?>
				<nobr><?=$champ->name ?></nobr>
			</td>
			<td><?=date('d.m.Y H:i.s', $m->begintime) ?></td>
			<td>
				<?php if ($m->resulttime > 0) : ?>
				<?=date('d.m.Y H:i.s', $m->resulttime) ?>
				<?php endif; ?>
			</td>
			<td>
				<?php if ($m->canceltime > 0) : ?>
					<?=date('d.m.Y H:i.s', $m->canceltime) ?>
				<?php endif; ?>
			</td>
			<td style="text-align: center;">
				<a href="<?=$this->createUrl("/bets/adminlist") ?>?mid=<?=$m->id ?>">[<?=$m->getBetsCount(); ?>]</a>
			</td>
			<td>
				<a href="<?=$this->createUrl('/match/'.$m->id) ?>"><nobr>to match</nobr></a><br />
				<?php if (!$m->isCanceled()) : ?>
					<a href="javascript:cancelMatch(<?=$m->id ?>)">cancel</a><br />
				<?php endif; ?>
				<a href="<?=$this->createUrl('/match/'.$m->id.'/delete') ?>">delete</a><br />
				<a href="javascript:recalkMatch(<?=$m->id ?>)">recalck</a><br />
				<?php if (!$m->isFinish()) : ?>
					<a href="<?=$this->createUrl('/match/'.$m->id.'/result') ?>">result</a>
				<?php endif; ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>