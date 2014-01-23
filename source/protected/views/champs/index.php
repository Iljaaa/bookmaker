<?php
$this->breadcrumbs=array(
	'Champs' => $this->createUrl('/champs'),
); ?>

<h1>Champs</h1>

<table>
	<thead>
		<tr>
			<th style="width: 20px;"></th>
			<th>Champ name</th>
			<th style="width: 100px;">Matches count</th>
			<th style="width: 50px;"></th>
		</tr>
	</thead>
	<tbody>
		<?php foreach ($champs as $c) : ?>
		<tr>
			<td style="text-align: center;"><?=$c->id ?></td>
			<td><a href="<?=$this->createUrl('/champ/'.$c->id) ?>"><?=$c->name ?></a></td>
			<td style="text-align: center;"><?=$c->getMatchesCount() ?></td>
			<td>
				<?php if (!yii::app()->user->isGuest) : ?>
					<a href="<?=$this->createUrl('/champ/'.$c->id.'/edit') ?>">edit</a>
				<?php endif; ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
	<tfoot>
	<tr>
		<td colspan="3">
			<?php if (!yii::app()->user->isGuest) : ?>
				<a href="<?=$this->createUrl('/champs/add') ?>" class="f-bu f-bu-default">add champ</a>
			<?php endif; ?>
		</td>
	</tr>
	</tfoot>
</table>