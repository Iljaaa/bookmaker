<?php $this->pageTitle = 'Registration'; ?>
<?php $this->breadcrumbs[yii::t('user_registration', 'Registration')] = $this->createUrl('/users/registration'); ?>
<h1><?=yii::t('user_registration', 'New user registration') ?></h1>

<?=$this->renderPartial('/users/registration/form', array('model' => $model)); ?>