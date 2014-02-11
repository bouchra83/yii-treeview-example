<?php
$this->breadcrumbs=array(
	'Kategógiák'=>array('index'),
	'Szerkesztés',
);

?>

<h1>'<?php echo $model->title; ?>' szerkesztése</h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
