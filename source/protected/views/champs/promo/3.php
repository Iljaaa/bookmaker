<script type="text/javascript">
	$(document).ready (function (){
		$("body").css('background-image', 'url(/images/other/international2013.jpg)');
		$("body").css('background-repeat', 'no-repeat');
		$("body").css('background-position', 'top center');
	});
</script>

<?=$this->renderPartial('/champs/view', array (
	'champ'                 => $champ,
	'matches'               => $matches,
	'matchesCount'          => $matchesCount,
	'matchesWitchResult'    => $matchesWitchResult,
	'teams'                 => $teams,
	'stat'                  => $stat,
	'order'                 => $order,
	'teamsByPoints'         => $teamsByPoints
)); ?>