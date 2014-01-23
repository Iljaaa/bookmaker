<?php
/* @var $this SiteController */

$this->pageTitle=Yii::app()->name;
?>

<h1>Welcome to <i><?php echo CHtml::encode(Yii::app()->name); ?></i></h1>

<table>
	<thead>

	</thead>
	<tbody>
		<?php foreach ($matches as $m) :
			$team1 = $m->getTeam1();
			$team2 = $m->getTeam2();
			if ($team1 == null || $team2 == null) continue;
			?>
			<tr>
				<td><?=$m->id ?></td>
				<td style="text-align: right; width: 100px;"><?=$team1->shortname ?></td>
				<td style="text-align: center; width: 25px;">
					<img src="<?=$team1->getIcoUrl() ?>" style="width: 20px;" />
				</td>
				<td style="text-align: center;width: 20px">vs</td>
				<td style="text-align: center; width: 25px;">
					<img src="<?=$team2->getIcoUrl() ?>" style="width: 20px;" />
				</td>
				<td style="text-align: left; width: 100px;"><?=$team2->shortname ?></td>
				<td style="text-align: center;"><?=$m->factor1 ?>&nbsp;x&nbsp;<?=$m->factor2 ?></td>
				<td>
					<?php if (date('d.m.Y', $m->begintime) == date('d.m.Y')) :  ?>
						Today <?=date('H:i', $m->begintime) ?>
					<?php else : ?>
						<time><?=$m->getBeginTime() ?></time>
					<?php endif; ?>
				</td>
				<td><?=$m->getRemainingTimeString() ?></td>
				<td>
					<?php $champ = $m->getChamp(); ?>
					<?php if ($champ != null) : ?>
						<?=$champ->name ?>
					<?php endif; ?>
				</td>
				<td>
					<a href="<?=$this->createUrl('/match/'.$m->id) ?>">goto match</a>
				</td>
				<?php if ($m->result1 > 0 || $m->result2 > 0) : ?>
					<td><?=$m->result1 ?> : <?=$m->result2 ?></td>
				<?php endif; ?>
			</tr>

		<?php endforeach; ?>
	</tbody>
</table>

<p>Congratulations! You have successfully created your Yii application.</p>

<p>You may change the content of this page by modifying the following two files:</p>
<ul>
	<li>View file: <code><?php echo __FILE__; ?></code></li>
	<li>Layout file: <code><?php echo $this->getLayoutFile('main'); ?></code></li>
</ul>

<p>For more details on how to further develop this application, please read
the <a href="http://www.yiiframework.com/doc/">documentation</a>.
Feel free to ask in the <a href="http://www.yiiframework.com/forum/">forum</a>,
should you have any questions.</p>


