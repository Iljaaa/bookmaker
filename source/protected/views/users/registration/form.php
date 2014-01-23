<script type="text/javascript">

	$(document).ready(function (){
		$("#RegistrationForm_password").change(passwordChange);
		$("#RegistrationForm_password").focusout (passwordChange);
		$("#RegistrationForm_password").keypress (passwordChange);
	});

	function passwordChange (){
		var pwd = $("#RegistrationForm_password").val();
		var strMesssage =  passwordChanged(pwd);
		$("#password-strange").html(strMesssage);
	}

	function passwordChanged(pwd)
	{
		var strength = document.getElementById('strength');

		var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g");
		var mediumRegex = new RegExp("^(?=.{6,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g");
		var enoughRegex = new RegExp("(?=.{3,}).*", "g");

		if (pwd.length==0) {
			return '';
		} else if (false == enoughRegex.test(pwd)) {
			return 'Password to short';
		} else if (strongRegex.test(pwd)) {
			return '<span style="color:green">Strong</span>';
		} else if (mediumRegex.test(pwd)) {
			return '<span style="color:orange">Medium</span>';
		} else {
			return '<span style="color:red">Weak</span>';
		}
	}

</script>

<style type="text/css">

	#captcha-place img {
		float: left;
	}

	#captcha-place a {
		display: table-cell;
		height: 50px;
		padding-left: 10px;
		vertical-align: middle;
	}

</style>

<?=CHtml::beginForm(); ?>

<div class="f-row">
	<?=CHtml::activeLabel($model, 'name') ?>
	<div class="f-input">
		<?=CHtml::activeTextField($model, 'name', array('maxlength' => 128, 'class'=>'g-4')) ?>
		<?=CHtml::error($model, "name"); ?>
		<span class="f-input-comment">
			You login on system
		</span>
	</div>

</div>


<div class="f-row" style="min-height: 28px;">
	<?=CHtml::activeLabel($model, 'email') ?>
	<div class="f-input">
		<?=CHtml::activeTextField($model, 'email', array('maxlength' => 128, 'class'=>'g-4')) ?>
		<?=CHtml::error($model, "email"); ?>
		<span class="f-input-comment">
			Using for restore personal data and get notifications
		</span>
	</div>
</div>


<div class="f-row">
	<?=CHtml::activeLabel($model, 'password') ?>
	<div class="f-input">
		<?=CHtml::activePasswordField($model, 'password', array('maxlength' => 128, 'class'=>'g-4')) ?>
		<span id="password-strange"></span>
		<?=CHtml::error($model, "password"); ?>
		<span class="f-input-comment">
			Min 4 symbols, best 6 symbols
		</span>
	</div>

</div>

<div class="f-row">
	<?=CHtml::activeLabel($model, 'password_confirm') ?>
	<div class="f-input">
		<?=CHtml::activePasswordField ($model, 'password_confirm', array('maxlength' => 128, 'class'=>'g-4')) ?>
		<?=CHtml::error($model, "password_confirm"); ?>
	</div>
</div>


<?php if(CCaptcha::checkRequirements()): ?>
	<div class="f-row">
		<?=CHtml::activeLabel($model, 'verifyCode') ?>
		<div class="f-input" id="captcha-place">
			<?php $this->widget('CCaptcha'); ?>
			<div style="clear: both;">
			<?=CHtml::activeTextField($model, 'verifyCode', array('maxlength' => 10, 'class'=>'g-2')) ?>
			</div>
			<?=CHtml::error($model, "verifyCode"); ?>
		</div>
	</div>
<?php endif; ?>

<div class="f-row">
	<div class="f-actions">
		<?=CHtml::submitButton('Registration', array('class'=>'f-bu f-bu-success')); ?>
	</div>
</div>


<?=CHtml::endForm() ?>