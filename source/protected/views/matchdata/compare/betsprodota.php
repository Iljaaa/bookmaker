<?php if ($match == null) : ?>
	<p>Match not found</p>
<?php else : ?>
	<span><?=$match->getTeam1() ?></span>&nbsp;
	<span><?=$match->getKoef1() ?></span>&nbsp;
	<span>vs</span>&nbsp;
	<span><?=$match->getKoef2() ?></span>&nbsp;
	<span><?=$match->getTeam2() ?></span>
<?php endif; ?>
