<style>
    div.miniform {
    }

    div.miniform div {
        padding-bottom: 5px;
    }

    div.miniform label {
        width: 180px;
        display: inline-block;
    }

    .errorMessage {
        padding-left: 180px;
    }
</style>

<?php
$display = 'none';
if ($changePasswordModel->hasErrors()) $display = 'block';
?>

<div id="change-password-form" style="display: <?=$display ?>">
<h3><?=yii::t('user_personal', 'Change password'); ?></h3>
<?=CHtml::beginForm() ?>

<div class="miniform">
    <div>
        <label><?=yii::t('user_personal', 'Old password') ?></label>
        <span><?=CHtml::activePasswordField($changePasswordModel, 'old_password') ?></span>
    </div>

    <?=CHtml::error($changePasswordModel, 'old_password') ?>

    <div>
        <label><?=yii::t('user_personal', 'New password') ?></label>
        <span><?=CHtml::activePasswordField($changePasswordModel, 'password') ?></span>
    </div>

    <?=CHtml::error($changePasswordModel, 'password') ?>

    <div>
        <label><?=yii::t('user_personal', 'New password confirm') ?></label>
        <span><?=CHtml::activePasswordField($changePasswordModel, 'password_confirm') ?></span>
    </div>

    <?=CHtml::error($changePasswordModel, 'password_confirm') ?>

    <div>
        <label></label>
        <span>
            <?=CHtml::submitButton(yii::t('user_personal', 'Change password'), array ('class' => 'f-bu f-bu-success')) ?>
            <?=Chtml::button(yii::t('main', 'Cancel'), array('onclick' => 'cancelPasswordChanging()', 'class' => 'f-bu f-bu-warning')) ?>
        </span>
    </div>

</div>

<?=CHtml::endForm() ?>
</div>