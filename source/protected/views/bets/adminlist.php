<?php $this->pageTitle = 'Bets'; ?>
<script type="text/javascript" src="/js/bets_adminlist.js"></script>

<h1>Ставки</h1>

<?php $flash = yii::app()->user->getFlash('adminlist', ''); ?>
<?php if ($flash != '') : ?>
	<div class="f-message f-message-success"><?=$flash ?></div>
<?php endif; ?>

<?php $flash = yii::app()->user->getFlash('adminlist-bad', ''); ?>
<?php if ($flash != '') : ?>
	<div class="f-message f-message-error"><?=$flash ?></div>
<?php endif; ?>


<?php $this->renderPartial('/bets/adminlist/filters', array('criteria' => $criteria)); ?>


<?php if (count($bets) > 0) : ?>
	<?php $this->renderPartial('/bets/adminlist/list', array('bets' => $bets)); ?>
<?php else : ?>
	<p>No bets 4 show</p>
<?php endif; ?>