<?php
$display = 'none';
if ($changeEmailModel->hasErrors()) $display = 'block';
?>

<div id="change-email-form" style="display: <?=$display ?>">
    <h3><?=yii::t('user_personal', 'Change email'); ?></h3>
    <?=CHtml::beginForm() ?>

    <div class="miniform">
        <div>
            <label><?=yii::t('user_personal', 'New email') ?></label>
            <span><?=CHtml::activeEmailField($changeEmailModel, 'email') ?></span>
        </div>

        <?=CHtml::error($changeEmailModel, 'email') ?>

        <div>
            <label></label>
        <span>
            <?=CHtml::submitButton(yii::t('user_personal', 'Change email'), array ('class' => 'f-bu f-bu-success')) ?>
            <?=Chtml::button(yii::t('main', 'Cancel'), array('onclick' => 'cancelChangeEmail()', 'class' => 'f-bu f-bu-warning')) ?>
        </span>
        </div>

    </div>

    <?=CHtml::endForm() ?>
</div>