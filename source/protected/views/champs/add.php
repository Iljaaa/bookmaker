<?php
$this->breadcrumbs=array(
	'Champs' => $this->createUrl('/teams'),
	'Add' => ''
); ?>


<h1>New champ</h1>

<?=CHtml::form() ?>

	<div class="f-row">
		<div class="f-input">
			<?=CHtml::error($model, 'id') ?>
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
		<?=Chtml::activeLabel($model, 'parent') ?>
		<div class="f-input">
			<?=CHtml::activeDropDownList($model, 'parent', $model->getChampsForParentSelect() , array('class' => 'g-7')) ?>
			<?=CHtml::error($model, 'parent') ?>
		</div><!-- f-input -->
	</div>

	<div class="f-row">
		<?=Chtml::activeLabel($model, 'description') ?>
		<div class="f-input">
			<?=CHtml::activeTextArea($model, 'description', array('maxlength' => 2000, 'class' => 'g-7', 'style' => 'height: 150px;')) ?>
			<?=CHtml::error($model, 'description') ?>
		</div>
	</div>

	<div class="f-actions">
		<?=CHtml::submitButton('Save', array('class' => 'f-bu f-bu-default')) ?>
	</div>

<?=CHtml::endForm();