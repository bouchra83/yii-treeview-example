<div class="form">

<?php $form=$this->beginWidget('CActiveForm', array(
	'id'=>'category-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="note">Fields with <span class="required">*</span> are required.</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row">
		<?php 
			echo $form->labelEx($model,'id_parent');
			echo $form->dropDownList($model,'id_parent', 
			$model->getMainCategoryList(), 
			array(
				'options'=>array($model->id_parent=>array('selected'=>true))
				)
			);
			?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'title'); ?>
		<?php echo $form->textField($model,'title',array('maxlength'=>50)); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'slug_update'); ?>
		<?php echo $form->checkBox($model,'slug_update'); ?>
	</div>
	<p class="hint">A Slug módosítása csak akkor ajánlott, ha rövid idő telt el az aktuális slug mentése óta.</p>

	<div class="row nolabel buttons">
		<?php echo CHtml::submitButton($model->isNewRecord ? 'Create' : 'Save'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
