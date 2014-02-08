<?php
$this->breadcrumbs=array(
    yii::t('user_personal', 'User details')
);

$this->pageTitle = yii::t('user_personal', 'User details');
?>

<script type="text/javascript" src="<?php echo Yii::app()->request->baseUrl; ?>/js/users.js"></script>


<h1>User details: <?=yii::app()->user->name ?></h1>

<?php $message = yii::app()->user->getFlash('user', ''); ?>
<?php if ($message != '') : ?>
<div class="f-message f-message-success"><?=$message ?></div>
<?php endif; ?>

<table>
    <tbody>
        <tr>
            <td style="width: 100px;"><?=yii::t('main', 'Login'); ?></td>
            <td style="width: 100px;"><?=$user->login ?></td>
            <td></td>
        </tr>
        <tr>
            <td><?=yii::t('main', 'Password'); ?></td>
            <td>*****</td>
            <td>
                <input type="button" onclick="startChangePassowrd()" class="f-bu f-bu-default" value="<?=yii::t('user_personal', 'Change password'); ?>" />
            </td>
        </tr>
        <tr>
            <td>Email</td>
            <td><?=$user->email ?></td>
            <td>
                <input type="button" onclick="startChangeEmail()" class="f-bu f-bu-default" value="<?=yii::t('user_personal', 'Change email'); ?>" />
            </td>
        </tr>
    </tbody>
</table>

<?=$this->renderPartial('/users/index/change_password', array ('changePasswordModel' => $changePasswordModel)) ?>
<?=$this->renderPartial('/users/index/change_email', array ('changeEmailModel' => $changeEmailModel)) ?>

