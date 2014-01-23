<h1>Delete match (<?=$match->id ?>)</h1>

<?php $flash = yii::app()->user->getFlash('match-delete', ''); ?>
<?php if ($flash != '') : ?>
<div class="f-message f-message-error"><?=$flash ?></div>
<?php endif; ?>

<?php if ($match->canDelete()) : ?>
	<?=CHtml::beginForm() ?>
		<?=CHtml::hiddenField('command', 'delete') ?>
		<?=CHtml::submitButton('Delete', array('class'=>"f-bu f-bu-warning")) ?>
	<?=CHtml::endForm(); ?>
<?php else : ?>
	<h2>Match can't be deleted</h2>

	<ul>
		<?php if ($match->getBetsCount() > 0) : ?>
			<li>Match have bets</li>
		<?php endif; ?>
	</ul>

<?php endif; ?>