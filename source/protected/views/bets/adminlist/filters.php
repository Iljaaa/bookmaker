<div>
	<?php if (isset($criteria->params[':mid'])) : ?>
		<p>Match filter : <?=$criteria->params[':mid'] ?></p>
	<?php endif; ?>
	<?php if (isset($criteria->params[':uid'])) : ?>
		<p>User filter : <?=$criteria->params[':uid'] ?></p>
	<?php endif; ?>
</div>