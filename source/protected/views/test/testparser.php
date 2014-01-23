<?php
$parsers = array(
	'betsprodota',
	'dota2lounge',
	'egamingbets',
	'prodota',
	'starladder'
);

?>

<?php
$select = array ();
foreach ($parsers as $p) $select[$p] = $p;
?>

<h1>Parser test</h1>

<?=CHtml::beginForm('', 'get'); ?>
<?=CHtml::dropDownList('parser', 0, $select) ?>
<?=CHtml::submitButton('test') ?>
<?=CHtml::endForm(); ?>