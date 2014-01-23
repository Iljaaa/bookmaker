<script type="text/javascript">

	var t = 30;
	var doTimer = null;

	$(document).ready(function (){
		redirect();
	});

	function redirect ()
	{

		$("#timerPlace").html(t);

		if (t > 0) {
			doTimer = setTimeout(redirect, 1000);
		}
		else {
			document.location = '/match/'+$("#matchId").val();
		}

		t = t - 1;
	}

	function stopTimer (){
		clearTimeout(doTimer);
	}

</script>

<h1>Bets calk</h1>
<input type="hidden" id="matchId" value="<?=$matchId ?>" />

<div style="margin: 10px 0;">
	You was redirect back to match page in : <span id="timerPlace"></span> s
	<br />
		<a href="javascript:stopTimer()">stop timer</a>
		<a href="<?=$this->createUrl('/match/'.$matchId); ?>">go to match</a>
</div>

<?php if (isset($log) && count($log) > 0) : ?>
	<ul style="color: red;">
	<?php foreach ($log as $l) : ?>
		<li><?=$l ?></li>
	<?php endforeach; ?>
	</ul>
<?php endif; ?>