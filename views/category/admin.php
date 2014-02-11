<?php
$this->breadcrumbs=array(
	'Kategóriák'=>array('index'),
	'Adminisztráció',
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('category-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1>Kategóriák adminisztrációja</h1>

<?php echo CHtml::link('Részletes keresés','#',array('class'=>'search-button')); ?>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php $this->widget('zii.widgets.grid.CGridView', array(
	'id'=>'category-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		'id',
		'id_parent',
		'position',
		'slug',
		'title',
		array(
			'class'=>'CButtonColumn',
			'template'=>'{update}{delete}',			
		),
	),
)); ?>
