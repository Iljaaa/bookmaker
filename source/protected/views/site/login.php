<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>

<h1>Login</h1>

<p>Please fill out the following form with your login credentials:</p>

<p class="note">Fields with <span class="required">*</span> are required.</p>

<?=CHtml::beginForm('', 'post', array ('class' => 'f-horizontal')); ?>

<div class="f-row">
	<label><?=CHtml::activeLabel($model, 'username'); ?></label>
		<?=CHtml::activeTextField($model,'username'); ?>
		<?=CHtml::error($model, 'username'); ?>
</div>

<div class="f-row">
	<label><?=CHtml::activeLabel($model, 'password'); ?></label>
	<div class="f-input">
		<?=CHtml::activePasswordField($model,'password'); ?>
		<?=CHtml::error($model, 'password'); ?>
	</div>
</div>

<div class="f-row">
	<label></label>
	<div class="f-input">
		<?=CHtml::activeCheckBox($model,'rememberMe'); ?>
		<?=CHtml::activeLabel($model, 'rememberMe'); ?>
	</div>
</div>

<div class="f-actions">
	<?=CHtml::submitButton('Login'); ?>
</div>

<?=CHtml::endForm(); ?>
