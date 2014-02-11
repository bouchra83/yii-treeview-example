<?php
$this->breadcrumbs=array(
	'Kategóriák',
);
?>

<h1>Kategóriák</h1>

<?php if(Yii::app()->user->hasFlash('flash')):?>
    <div class="info">
        <?php echo Yii::app()->user->getFlash('flash'); ?>
    </div>
<?php endif; ?>

<?php 
echo CHtml::link('Adminisztráció >', array('admin'), array('style'=>'display:block; margin-bottom:1em;'));

$this->widget('CTreeView',array(
        'data'=>$dataTree,
        'htmlOptions'=>array(
					'id'=>'treeview-categ',
                'class'=>'treeview-red',//there are some classes that ready to use
        ),
));

?>
