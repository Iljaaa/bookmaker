<?php
$this->pageTitle = yii::t('user_registration', 'Registration is completed');
$this->breadcrumbs[yii::t('user_registration', 'Registration')] = $this->createUrl('/users/registration');
$this->breadcrumbs[yii::t('user_registration', 'Confirm registration')] = $this->createUrl('/users/confirmregistration');
?>

<h1><?=yii::t('user_registration', 'Confirm registration') ?></h1>


<?php if (isset($goodMessage) && $goodMessage != '') : ?>
	<div class="f-message f-message-success">
		<?=yii::t('user_registration', $goodMessage); ?>
	</div>
<?php endif; ?>


<?php if (isset($errorMessage) && $errorMessage != '') : ?>
<div class="f-message f-message-error">
	<?=yii::t('user_registration', $errorMessage); ?>
</div>
<?php endif; ?>