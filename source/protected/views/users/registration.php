<?php $this->pageTitle = 'Registration'; ?>
<h1><?=yii::t('user_registration', 'New user registration') ?></h1>

<?=$this->renderPartial('/users/registration/form', array('model' => $model)); ?>