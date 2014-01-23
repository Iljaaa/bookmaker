<?php $this->pageTitle = 'Registration'; ?>

<h1>New User registration</h1>

<?=$this->renderPartial('/users/registration/form', array('model' => $model)); ?>