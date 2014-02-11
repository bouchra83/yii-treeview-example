<?php

class AjaxController extends Controller
{

	public function actionSimpleTree()
	{
		Yii::import('application.extensions.SimpleTreeWidget');    
		SimpleTreeWidget::performAjax();
	}

}
