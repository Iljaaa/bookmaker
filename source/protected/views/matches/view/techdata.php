<h2>Tech messages</h2>

<?php $messages = $match->getLogMessages(); ?>
<?php if (count($messages) == 0) : ?>
<p>No tech messages</p>
<?php else : ?>

<table>
	<tbody>
		<?php foreach ($messages as $m) : ?>
		<tr>
			<td style="width: 120px;"><?=date('d.m.Y H:i', $m['time']) ?></td>
			<td style="width: 200px;">
				<?php if (isset($m['source']) && $m['source'] != '') : ?>
					source : <?=$m['source'] ?>
				<?php endif; ?>
			</td>
			<td>
				<?=$m['message'] ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>

<?php endif; ?>