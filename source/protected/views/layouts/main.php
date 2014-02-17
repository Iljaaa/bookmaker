<?php /* @var $this Controller */ ?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="en" />

	<link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.png" type="image/x-icon" />
	<link rel="icon" href="<?php echo Yii::app()->request->baseUrl; ?>/favicon.png" type="image/x-icon" />
	
	<script type="text/javascript" src="/js/jquery-2.0.3.min.js"></script>

	<?php /*
	<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
	<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script> */ ?>

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/framework.css" media="screen, projection" />
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="g" style="background-color: white;">

	<div class="g-row" style="height: 34px; margin-top: 15px;">
		<div class="g-12">
			<h1 style="width: 500px; float: left;"><?php echo CHtml::encode(Yii::app()->name); ?></h1>
            <div style="float: right;">
                <div style="padding: 0 0 0; text-align: right;">
                    Current time:<br />
                    CET : <b><?=date ('d.m.Y H:i'); ?></b><br />
                    MOS : <b><?=date ('d.m.Y H:i', (time()+(3*3600))); ?></b><br />
                </div>
            </div>
            <div style="float: right; width: 50px;">
                <div style="padding: 0 0 0; text-align: center;">
                    <?php foreach (Yii::app()->params['languages'] as $l => $langName ) : ?>
                        <?php if ($l == Yii::app()->language) continue; ?>
                        <a href="<?=$this->createUrl('/', array('language' => $l)) ?>"><?=$l ?></a>
                    <?php endforeach; ?>
                </div>
            </div>

		</div>
	</div>

	<div class="g-row" style="margin-top: 0; padding: 0;">
		<div class="g-12">
			<div class="f-nav-bar">
				<div class="f-nav-bar-body">
					<div class="f-nav-bar-title">
						<a href="/">Goto main</a>
					</div>
					<ul class="f-nav">
						<li><a href="<?=$this->createUrl('/teams') ?>">Teams</a></li>
						<li><a href="<?=$this->createUrl('/champs') ?>">Champs</a></li>
						<li><a href="<?=$this->createUrl('/matches') ?>">Mathes</a></li>
						<li>&nbsp;&nbsp;&nbsp;&nbsp;</li>
						<li><a href="<?=$this->createUrl('/site/contact') ?>">Contact</a></li>
						<li><a href="<?=$this->createUrl('/site/about') ?>">About</a></li>
						<li>&nbsp;&nbsp;&nbsp;&nbsp;</li>
						<?php if (Yii::app()->user->isGuest) : ?>
							<li><a href="<?=$this->createUrl('/site/login') ?>">Login</a></li>
							<li><a href="<?=$this->createUrl('/users/registration') ?>">Registration</a></li>
						<?php else : ?>
							<li><a href="<?=$this->createUrl('/mybets') ?>">My Bets</a></li>
                            <li><a href="<?=$this->createUrl('/user/') ?>"><?=yii::t('mainmenu', 'Personal info') ?></a></li>
							<li><a href="<?=$this->createUrl('/site/logout') ?>">Logout</a></li>

							<?php $u = User::getAuthedUser(); ?>
							<?php if ($u != null) : ?>
								<li>&nbsp;&nbsp;&nbsp;&nbsp;</li>
								<li><b>Balance : <span style="color: lightcoral;"><?=$u->balance ?>$</span></b></li>
								<li><a href="<?=$this->createUrl('/site/updateuserbalance/'.$u->id) ?>">update balance</a></li>
							<?php endif; ?>
						<?php endif; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>


	<div class="g-row" style="margin-top: 0px">
		<div class="g-12">
			<?php if(isset($this->breadcrumbs)):?>
				<?php $this->widget('zii.widgets.CBreadcrumbs', array(
					'links'=>$this->breadcrumbs,
				)); ?><!-- breadcrumbs -->
			<?php endif?>
		</div>
	</div>
</div>


<?php echo $content; ?>



<div style="background-color: gray;">

	<?php if (!yii::app()->user->isGuest && yii::app()->user->hasRole('admin')) : ?>
		<div class="g" style="background-color: silver;">
			<div class="g-row" style="height: 34px; margin-top: 15px;">
				<div class="g-12">
					<div style="float: left; margin-left: 20px;">
						<a href="<?=$this->createUrl('/matches/adminlist') ?>">Matches</a>&nbsp;&nbsp;
						<a href="<?=$this->createUrl('/users/adminlist') ?>">Users</a>&nbsp;&nbsp;
						<a href="<?=$this->createUrl('/bets/adminlist') ?>">Bets</a>&nbsp;&nbsp;
						<a href="<?=$this->createUrl('/test/testparser') ?>" target="_blank">Parser test</a>&nbsp;&nbsp;
						<a href="<?=$this->createUrl('/parser/compare') ?>" target="_blank">Parsers compare</a>
					</div>
					<div style="float: right; margin-right: 20px;">
						admin pannel
					</div>
				</div>
			</div>
		</div>
	<?php endif; ?>

	<div class="g">
	<div class="g-row">
		<div class="g-12">
			<div style="float: right; text-align: right; padding: 10px 0 10px 0; color: white;">
				Copyright &copy; <?php echo date('Y'); ?> by dota bet prototype.<br/>
				All Rights Reserved.
			</div>
		</div>
	</div>
	</div>
</div>



</body>
</html>