<?php
$this->breadcrumbs=array(
	'Kategógiák'=>array('index'),
	'Sorrend',
);

?>

<h1>Sorrend</h1>

<?php
    // Organize the dataProvider data into a Zii-friendly array
    $items = CHtml::listData($dataProvider->getData(), 'id', 'title');
    // Implement the JUI Sortable plugin
    $this->widget('zii.widgets.jui.CJuiSortable', array(
        'id' => 'orderList',
        'items' => $items,
    ));
    // Add a Submit button to send data to the controller
    echo CHtml::ajaxButton('Sorrend mentése', '', array(
        'type' => 'POST',
				'success'	=> 'function(data) {
									top.location.href="'.Yii::app()->createUrl('category/index').'"; 
							}',
        'data' => array(
            // Turn the Javascript array into a PHP-friendly string
            'Order' => 'js:$("ul#orderList").sortable("toArray").toString()',
        )
    ));
?>

