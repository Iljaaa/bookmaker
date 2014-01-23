<h1>Пользователи</h1>

<?php $flash = yii::app()->user->getFlash('adminlist', ''); ?>
<?php if ($flash != '') : ?>
	<div class="f-message f-message-success"><?=$flash ?></div>
<?php endif; ?>

<?php $flash = yii::app()->user->getFlash('adminlist-bad', ''); ?>
<?php if ($flash != '') : ?>
	<div class="f-message f-message-error"><?=$flash ?></div>
<?php endif; ?>

<table>
	<thead>
		<tr>
			<th style="width: 20px;">#</th>
			<th>login</th>
			<th>email</th>
			<th>role</th>
			<th>last activity</th>
			<th style="width: 50px; text-align: center;">Bets</th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($users as $m) : ?>
		<tr>
			<td style="text-align: center;"><?=$m->id ?></td>
			<td><?=$m->login ?></td>
			<td><?=$m->email ?></td>
			<td>
				<?php if ($m->role != '') : ?>
					<?=$m->role ?>
				<?php else : ?>
					<i>Not set</i>
				<?php endif; ?>
			</td>
			<td><?=date("d.m.Y H:i", $m->last_activity) ?> </td>
			<td style="text-align: center;">
				<a href="<?=$this->createUrl('/bets/adminlist') ?>?uid=<?=$m->id ?>">[<?=$m->getBetsCount(); ?>]</a>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>