<?php for($p = 1; $p <= $pagesCount; $p++) : ?>
	<?php  if ($p == $this->page) : ?>
		&nbsp;[<?=$p ?>]
	<?php else : ?>
		&nbsp;<a href="<?=yii::app()->controller->createUrl($this->url) ?>?page=<?=$p ?>"><?=$p ?></a>
	<?php endif; ?>
<?php endfor; ?>