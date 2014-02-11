<?php
$this->breadcrumbs=array(
	'Kategóriák'=>array('index'),
	'Létrehozás',
);

?>

<h1>Kategória létrehozása</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
