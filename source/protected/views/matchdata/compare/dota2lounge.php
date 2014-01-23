<?php if ($match == null) : ?>
	<p>Match not found</p>
<?php else : ?>
	<span><?=$match['teams'][0] ?></span>&nbsp;
	<span><?=$match['result'][0] ?></span>&nbsp;
	<span>vs</span>&nbsp;
	<span><?=$match['result'][1] ?></span>&nbsp;
	<span><?=$match['teams'][1] ?></span>
<?php endif; ?>
