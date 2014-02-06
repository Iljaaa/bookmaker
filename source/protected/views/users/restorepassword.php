<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	yii::t('user_registration', 'Registration') => $this->createUrl('/users/registration'),
	yii::t('user_registration', 'Restore password')
);
?>

<h1><?=yii::t('user_registration', 'Restore password') ?></h1>

<?php $message = yii::app()->user->getFlash('resotrepassword', ''); ?>
<?php if ($message != '') : ?>
<div class="f-message f-message-success">
	<?=$message ?>
</div>
<?php endif; ?>

<?=CHtml::beginForm(); ?>

	<div class="f-row" style="min-height: 28px;">
		<?=CHtml::activeLabel($model, 'email') ?>
		<div class="f-input">
			<?=CHtml::activeTextField($model, 'email', array('maxlength' => 128, 'class'=>'g-4')) ?>
			<span class="f-input-comment">
            <?=yii::t('user_registration', 'Input your email for restore password'); ?>
			</span>
			<?=CHtml::error($model, "email"); ?>
		</div>
	</div>

	<div class="f-row">
		<div class="f-actions">
			<?=CHtml::submitButton(yii::t('user_registration', 'Registration'), array('class'=>'f-bu f-bu-success')); ?>
		</div>
	</div>


<?=CHtml::endForm() ?>