<script type="text/javascript">

	function beginEdit (fileIndex) {
		$("#dataTable-"+fileIndex).find('input').removeAttr('disabled');
		$("#dataTable-controls-"+fileIndex).slideDown();


	}

    /***
     * Добавляем строку
     */
	function addRow (dataIndex)
	{

		var newRowIndex = maxRowAddIndex(dataIndex) + 1;

		var html = "<tr newrowindex='"+newRowIndex+"'>";

		html += "<td style='width: 165px; padding-left: 0;'>";
		html += "<input type='text' name='newrow["+newRowIndex+"][key]' value='' class='g-3' />";
		html += "</td><td style='width: 25px; text-align: center;'>=></td>";
		html += '<td>';
		html += "<input type='text' name='newrow["+newRowIndex+"][value]' value='' class='g-3' />";
		html += "</td>";
		html += "</tr>";

		$("table#dataTable-"+dataIndex+" tbody").append (html);
	}

	function maxRowAddIndex (dataIndex)
	{
		var rows = $("table#dataTable-"+dataIndex+" tbody tr");
		var rowIndex = 0;

		$(rows).each(function (index, element)
		{
			var attrVal = $(element).attr('newrowindex');
			if (attrVal == undefined) return;

			var i = parseInt(attrVal);
			if (i > rowIndex) rowIndex = i;

		});

		return rowIndex;
	}

	function cancelEdit (){
		document.location = document.location;
	}

</script>

<h1>Teams & Champs compare tables</h1>

<?php $flash = yii::app()->user->getFlash('parser-compare-bad', ''); ?>
<?php if ($flash != '') : ?>
	<div class="f-message f-message-error"><?=$flash ?></div>
<?php endif; ?>

<?php $flash = yii::app()->user->getFlash('parser-compare-good', ''); ?>
<?php if ($flash != '') : ?>
	<div class="f-message f-message-success"><?=$flash ?></div>
<?php endif; ?>


<table>
	<tbody>

		<?php $i = 0; ?>
		<?php foreach ($files as $f) : ?>
		<?php $i++; ?>
		<tr>
			<th><?=$f['name'] ?></th>
			<th><?=date("d.m.Y H:s:i", $f['touch']) ?></th>
			<th style="text-align: right;">
				<a href="javascript:beginEdit(<?=$i ?>)">edit</a>
			</th>
		</tr>
		<tr>
			<td colspan="4" style="padding: 0 0 0 55px; ">
				<?=CHtml::beginForm('', 'post'); ?>
				<table style="margin: -1px 0 0 0" id="dataTable-<?=$i ?>">
					<tbody>
						<?php foreach ($f['data'] as $key => $val) : ?>
							<tr>
								<td style="width: 165px; padding-left: 0;">
									<?=CHtml::textField('data['.$f['name'].']['.$key.'][key]', $key, array('class' => 'g-3', 'disabled' => 'disabled')) ?>
								</td>
								<td style="width: 25px; text-align: center;">=></td>
								<td>
									<?=CHtml::textField('data['.$f['name'].']['.$key.'][value]', $val, array('class' => 'g-3', 'disabled' => 'disabled')) ?>
								</td>
								<td>

								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>
				<div style="padding: 5px 0 15px 0; display: none;" id="dataTable-controls-<?=$i ?>">
					<?=CHtml::submitButton('save', array('class' => '')) ?>
					<input type="button"  class="" value="Отмена" onclick="cancelEdit()" />
					<input type="button"  class="" value="Add row" onclick="addRow(<?=$i ?>)" />
				</div>
				<?=CHtml::endForm() ?>
			</td>
		</tr>
		<?php endforeach; ?>
	</tbody>
</table>