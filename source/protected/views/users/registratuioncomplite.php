<?php
$this->pageTitle = yii::t('user_registration', 'Registration is completed');
$this->breadcrumbs[yii::t('user_registration', 'Registration')] = $this->createUrl('/users/registration');
$this->breadcrumbs[yii::t('user_registration', 'Registration is completed')] = $this->createUrl('/users/registrationcomplite');
?>

<h1><?=yii::t('user_registration', 'Registration is completed') ?></h1>

<div class="f-message f-message-success">
	<?=yii::t('user_registration', 'Registration is completed') ?>. <br />

	<?php if (yii::app()->language == 'ru') : ?>
		<p>На Ваш email отправлено письмо с подтверждение регистрации</p>
	<?php else : ?>
		<p>To your email sent to you with confirmation of your registration</p>
	<?php endif; ?>

</div>