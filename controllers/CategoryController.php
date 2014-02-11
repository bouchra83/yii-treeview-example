<?php

class CategoryController extends Controller
{
	/**
	 * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
	 * using two-column layout. See 'protected/views/layouts/column2.php'.
	 */
	public $layout='//layouts/column3';

	/**
	 * @return array action filters
	 */
	public function filters()
	{
		return array(
			'accessControl', // perform access control for CRUD operations
		);
	}

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array(
			array('allow',  // allow all users to perform 'index' and 'view' actions
				'actions'=>array('index'),
				'users'=>array('*'),
			),
			array('allow', // allow authenticated user to perform 'create' and 'update' actions
				'actions'=>array('create','update', 'admin', 'delete', 'order'),
				'users'=>array('@'),
			),
			array('allow', // allow admin user to perform 'admin' and 'delete' actions
				//'actions'=>array('admin','delete'),
				'users'=>array('admin'),
			),
			array('deny',  // deny all users
				'users'=>array('*'),
			),
		);
	}

	/**
	 * Handles the ordering of models.
	 */
	public function actionOrder()
	{
		// Handle the POST request data submission
		if (isset($_POST['Order']))
		{
			// Since we converted the Javascript array to a string,
			// convert the string back to a PHP array
			$models = explode(',', $_POST['Order']);

			for ($i = 0; $i < sizeof($models); $i++)
			{
				if ($model = Category::model()->findbyPk($models[$i]))
				{
					$model->position = $i;

					$model->save();
				}
			}
			Yii::app()->user->setFlash('flash', "A kívánt sorrend beállítva.");
			Yii::app()->end();
		}
		// Handle the regular model order view
		else
		{
			$dataProvider = new CActiveDataProvider('Category', array(
						'pagination' => false,
						'criteria' => array(
							'condition' => 'id_parent = ' . $_GET['id'],
							'order' => 'position ASC',
							),
						));

			$this->render('order',array(
						'dataProvider' => $dataProvider,
						));
		}
	}
	

	/**
	 * Creates a new model.
	 * If creation is successful, the browser will be redirected to the 'view' page.
	 */
	public function actionCreate()
	{
		$model=new Category;

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);
		if (isset($_GET['id_parent'])) $model->id_parent = $_GET['id_parent'];

		if(isset($_POST['Category']))
		{
			$model->attributes=$_POST['Category'];
			if($model->save()) {
				Yii::app()->user->setFlash('flash', "Sikeres létrehozás.");
				$this->redirect(array('index'));
			}
		}

		$this->render('create',array(
			'model'=>$model,
		));
	}

	/**
	 * Updates a particular model.
	 * If update is successful, the browser will be redirected to the 'view' page.
	 * @param integer $id the ID of the model to be updated
	 */
	public function actionUpdate($id)
	{
		if ($id==1) {
			Yii::app()->user->setFlash('flash', "A főkategória nem szerkeszthető.");
			$this->redirect(array('index'));
		}

		$model=$this->loadModel($id);

		// Uncomment the following line if AJAX validation is needed
		// $this->performAjaxValidation($model);

		if(isset($_POST['Category']))
		{
			$model->attributes=$_POST['Category'];
			if($model->save()) {
				Yii::app()->user->setFlash('flash', "Sikeres szerkesztés.");
				$this->redirect(array('index','id'=>$model->id));
			}
		}
print_r($model->slug_update);
		$this->render('update',array(
			'model'=>$model,
		));
	}

	/**
	 * Deletes a particular model.
	 * If deletion is successful, the browser will be redirected to the 'index' page.
	 * @param integer $id the ID of the model to be deleted
	 */
	public function actionDelete($id)
	{
		if(Yii::app()->request->isPostRequest)
		{
			// we only allow deletion via POST request
			try {
				$this->loadModel($id)->delete();
				Yii::app()->user->setFlash('flash', "Sikeres törlés.");
			} catch (Exception $e) {
				Yii::app()->user->setFlash('flash', $e->getMessage());
			}

		}
		else
			throw new CHttpException(400,'Invalid request. Please do not repeat this request again.');
	}

	/**
	 * Lists all models.
	 */
	public function actionIndex()
	{
		$dataProvider=new CActiveDataProvider('Category');
		$this->render('index',array(
			'dataTree'=>Category::dataTree(true),
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model=new Category('search');
		$model->unsetAttributes();  // clear any default values
		if(isset($_GET['Category']))
			$model->attributes=$_GET['Category'];

		$this->render('admin',array(
			'model'=>$model,
		));
	}

	/**
	 * Returns the data model based on the primary key given in the GET variable.
	 * If the data model is not found, an HTTP exception will be raised.
	 * @param integer the ID of the model to be loaded
	 */
	public function loadModel($id)
	{
		$model=Category::model()->findByPk((int)$id);
		if($model===null)
			throw new CHttpException(404,'The requested page does not exist.');
		return $model;
	}

	/**
	 * Performs the AJAX validation.
	 * @param CModel the model to be validated
	 */
	protected function performAjaxValidation($model)
	{
		if(isset($_POST['ajax']) && $_POST['ajax']==='category-form')
		{
			echo CActiveForm::validate($model);
			Yii::app()->end();
		}
	}
}
