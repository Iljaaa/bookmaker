<script type="text/javascript">
	$(document).ready(function (){
		var matchId = parseInt($("#matchid").val());
		if (matchId == 0 || matchId == undefined) return;

		$.ajax({
			url         : "<?=$this->createUrl('/matchData/compare/') ?>",
			data        : {match : matchId, source : 'dota2lounge'},
			cache       : false,
			complete    : function () {
				$('#dota2lounge-loader').hide();
			},
			error       : function (jqXHR, textStatus, errorThrown) {
				$("#dota2lounge-content").show();
				$("#dota2lounge-content").html('Request error:'+errorThrown);
				console.log (jqXHR);
				console.log (textStatus);
				console.log (errorThrown);
			},
			success     : function (data, textStatus,jqXHR) {
				$("#dota2lounge-content").show();
				$("#dota2lounge-content").html(data);
			}
		});


		$.ajax({
			url         : "<?=$this->createUrl('/matchData/compare/') ?>",
			data        : {match : matchId, source : 'egamingbets'},
			cache       : false,
			complete    : function () {
				$('#egamingbets-loader').hide();
			},
			error       : function (jqXHR, textStatus, errorThrown) {
				$("#egamingbets-content").show();
				$("#egamingbets-content").html('Request error:'+errorThrown);
				console.log (jqXHR);
				console.log (textStatus);
				console.log (errorThrown);
			},
			success     : function (data, textStatus,jqXHR) {
				$("#egamingbets-content").show();
				$("#egamingbets-content").html(data);
			}
		});

		$.ajax({
			url         : "<?=$this->createUrl('/matchData/compare/') ?>",
			data        : {match : matchId, source : 'betsprodota'},
			cache       : false,
			complete    : function () {
				$('#betsprodota-loader').hide();
			},
			error       : function (jqXHR, textStatus, errorThrown) {
				$("#betsprodota-content").show();
				$("#betsprodota-content").html('Request error:'+errorThrown);
				console.log (jqXHR);
				console.log (textStatus);
				console.log (errorThrown);
			},
			success     : function (data, textStatus,jqXHR) {
				$("#betsprodota-content").show();
				$("#betsprodota-content").html(data);
			}
		});

	})
</script>

<h1>Forecast</h1>

<table>
	<tbody>
		<tr>
			<td style="height: 50px; width: 200px">
				<img src="/images/other/dota2lounge.com.png" />
			</td>
			<td style="vertical-align: middle">
				<div id="dota2lounge-loader">
					<img src="/images/loader.gif" style="float: left; margin-right: 15px;" />
					<div style="padding: 7px 0 0 0;">
						<span style="padding-top: 5px;">Загрузка</span>
					</div>
				</div>
				<div id="dota2lounge-content" style="display: none;">

				</div>
			</td>
		</tr>
		<tr>
			<td style="height: 50px; width: 200px">
				<img src="/images/other/egamingbets.com.jpg" style="width: 150px" />
			</td>
			<td style="vertical-align: middle">
				<div id="egamingbets-loader">
					<img src="/images/loader.gif" style="float: left; margin-right: 15px;" />
					<div style="padding: 7px 0 0 0;">
						<span style="padding-top: 5px;">Загрузка</span>
					</div>
				</div>
				<div id="egamingbets-content" style="display: none;">

				</div>
			</td>
		</tr>

		<tr>
			<td style="height: 50px; width: 200px; text-align: center;">
				<img src="/images/other/bets.prodota.png" style="height: 60px" />
			</td>
			<td style="vertical-align: middle">
				<div id="betsprodota-loader">
					<img src="/images/loader.gif" style="float: left; margin-right: 15px;" />
					<div style="padding: 7px 0 0 0;">
						<span style="padding-top: 5px;">Загрузка</span>
					</div>
				</div>
				<div id="betsprodota-content" style="display: none;">

				</div>
			</td>
		</tr>
	</tbody>
</table>