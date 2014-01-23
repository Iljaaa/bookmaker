<?php
$this->breadcrumbs=array(
 'Teams' => $this->createUrl('/teams'),
); ?>

<table>
	<caption>Комманды (<?=$teamsCount ?>)</caption>
	<thead>
	<tr>
		<th style="width: 20px; text-align: center;">#</th>
		<th style="width: 25px; text-align: center;">-</th>
		<th>Name</th>
		<th style="width: 50px;">games</th>
		<th style="width: 50px;"></th>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($teams as $t) : ?>
		<tr>
			<td style="text-align: center;"><?=$t->id ?></td>
			<td style="text-align: center;"><img src="<?=$t->getIcoUrl() ?>" style="width: 20px;" /></td>
			<td>
				<?=$t->shortname ?>
				<?php if ($t->shortname != $t->name) : ?>
					(<?=$t->name ?>)
				<?php endif; ?>
			</td>
			<td style="text-align: center;"><?=$t->getAllGamesCount() ?></td>
			<td style="text-align: center;">
				<?php if (!yii::app()->user->isGuest) : ?>
					<a href="<?=$this->createUrl('/team/'.$t->id.'/edit'); ?>">edit</a>
				<?php endif; ?>
			</td>
		</tr>
	<?php endforeach; ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="3">
				<?php if (!yii::app()->user->isGuest) : ?>
					<a href="<?=$this->createUrl('/teams/add') ?>" class="f-bu f-bu-default">add team</a>
				<?php endif; ?>

				<?php $this->widget('application.widgets.Pagenator', array(
					'url' => '/teams',
					'count' => $teamsCount,
					'page'  => $page,
					'pagesize' => 40,
				)); ?>

			</td>
		</tr>
	</tfoot>
</table>