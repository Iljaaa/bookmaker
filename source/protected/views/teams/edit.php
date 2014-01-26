<?php
$this->breadcrumbs=array(
	'Teams' => $this->createUrl('/teams'),
	'Edit' => ''
); ?>


<h1>Team <?=$team->shortname ?> edit</h1>

<?php $flash = yii::app()->user->getFlash ('team-edit'); ?>
<?php if ($flash != '') : ?>
	<div class="f-message f-message-success">
		<?=$flash ?>
	</div>
<?php endif; ?>

<?=CHtml::form() ?>

<div class="f-row">
	<?=Chtml::activeLabel($model, 'shortname') ?>
	<div class="f-input">
		<?=CHtml::activeTextField($model, 'shortname', array('maxlength' => 30, 'class' => 'g-3')) ?>
		<?=CHtml::error($model, 'shortname') ?>
	</div><!-- f-input -->
</div>

	<div class="f-row">
		<?=Chtml::activeLabel($model, 'name') ?>
		<div class="f-input">
			<?=CHtml::activeTextField($model, 'name', array('maxlength' => 200, 'class' => 'g-7')) ?>
			<?=CHtml::error($model, 'name') ?>
		</div><!-- f-input -->
	</div>

    <div class="f-row">
        <?=Chtml::activeLabel($model, 'description') ?>
        <div class="f-input">
            <?=CHtml::activeTextArea($model, 'description', array('maxlength' => 2000, 'class' => 'g-7', 'style' => 'height: 150px;')) ?>
            <?=CHtml::error($model, 'description') ?>
        </div>
    </div>

    <div class="f-row">
        Preview
        <div class="f-input">
            <?=yii::app()->wiky->parse($model->description) ?>
        </div>
    </div>

<div class="f-actions">
	<?=CHtml::submitButton('Save', array('class' => 'f-bu f-bu-default')) ?>
</div>

<?=CHtml::endForm(); ?>

<?=$this->renderPartial('/site/pages/wiky_markup_help') ?>