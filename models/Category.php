<?php

/**
 * This is the model class for table "category".
 *
 * The followings are the available columns in table 'category':
 * @property integer $id
 * @property integer $id_parent
 * @property integer $position
 * @property string $slug
 * @property string $title
 */
class Category extends CActiveRecord
{
		
	public $slug_update;

	/**
	 * Returns the static model of the specified AR class.
	 * @return Category the static model class
	 */
	public static function model($className=__CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * @return string the associated database table name
	 */
	public function tableName()
	{
		return 'category';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('id_parent, title', 'required'),
			array('id_parent, position', 'numerical', 'integerOnly'=>true),
			array('id_parent', 'existingParent'),
			array('slug, title', 'length', 'max'=>50),
			array('slug_update', 'safe'),
			array('position', 'numerical'),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, id_parent, position, slug, title', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
//			'products'	=>	array(self::MANY_MANY, 'Product', 'product_category(category_id, product_id)')
		);
	}

	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'id' => 'ID',
			'id_parent' => 'Szülő',
			'position' => 'Pozíció',
			'slug' => 'Slug',
			'slug_update'	=>	'Slug módosítása',
			'title' => 'Cím',
		);
	}

	/**
	 * Retrieves a list of models based on the current search/filter conditions.
	 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
	 */
	public function search()
	{
		// Warning: Please modify the following code to remove attributes that
		// should not be searched.

		$criteria=new CDbCriteria;

		$criteria->compare('id',$this->id);
		$criteria->compare('id_parent',$this->id_parent);
		$criteria->compare('position',$this->position);
		$criteria->compare('slug',$this->slug,true);
		$criteria->compare('title',$this->title,true);

		return new CActiveDataProvider(get_class($this), array(
			'criteria'=>$criteria,
		));
	}

	public function behaviors(){
		$update = @$_POST['Category']['slug_update'] ? true : false;

		return array(
			'SlugBehavior' => array(
				'class' => 'application.models.behaviors.SlugBehavior',
				'slug_col' => 'slug',
				'title_col' => 'title',
				//'max_slug_chars' => 125,
				'overwrite' => $update,
				)
			);
	}

	public function getMainCategoryList($dummy=0) {
		$result = Yii::app()->db->createCommand()
			->select('id, title')
			->from('category')
			->where('id_parent<=:id', array(':id'=>1))
			->order('id_parent ASC, title ASC')
			->queryAll();

		$list = array();
		foreach ($result as $row) {
			$list[$row['id']] = $row['title']; 
		}
		if ($dummy) $list[] = 'xxx';

		return $list;
	}

	/*public static function arrayToObject($array) {
		if(!is_array($array)) {
			return $array;
		}

		$object = new stdClass();
		if (is_array($array) && count($array) > 0) {
			foreach ($array as $name=>$value) {
				$name = strtolower(trim($name));
				if (!empty($name)) {
					$object->$name = self::arrayToObject($value);
				}
			}
			return $object; 
		}
		else {
			return FALSE;
		}
	}
/*
	public static function dataTreeObject() {
		$data = self::dataTree();
		//$obj = self::arrayToObject($data);
		print_r($data);

		return $data;
	}

	public static function dataTreeSimple() {
		$refs = array();
		$list = array();

		
		$result = Yii::app()->db->createCommand()
			->select('*')
			->from('category')
			//->where('id>:id', array(':id'=>1))
			->order('id_parent ASC, position ASC')
			->queryAll();

		foreach($result as $data) {
			$thisref = &$refs[ $data['id'] ];
			//$thisref['id_parent'] = $data['id_parent'];

			$thisref[] = $data['title'];
			
			if ($data['id_parent'] == 0) {
				$list[ $data['id'] ] = &$thisref;
			} else {
				$refs[ $data['id_parent'] ]['children']/*[ $data['id'] ]* / = &$thisref;
			}
		}
		return $list;	
	}*/

	public static function getElementCounts() {
		$result = Yii::app()->db->createCommand()
		->select('pc.category_id, COUNT(*) as cnt')
		->from('product_category pc')
		->join('product p', 'pc.product_id = p.id')
		->where('p.deleted<1')
		->group('pc.category_id')
		->queryAll();

		$return = array();
		foreach ($result as $data) {
			$return[$data['category_id']] = $data['cnt'];
		}

		return $return;
	}

	public static function dataTree($buttons = false) {
		$refs = array();
		$list = array();

		
		$result = Yii::app()->db->createCommand()
			->select('*')
			->from('category')
			//->where('id>:id', array(':id'=>1))
			->order('id_parent ASC, position ASC')
			->queryAll();

		foreach($result as $data) {
			$thisref = &$refs[ $data['id'] ];
			$thisref['id_parent'] = $data['id_parent'];

			$button = array(
				'addChild' => CHtml::link(CHtml::image('/images/icons/sm2_addChild.png','Alkategória létrehozása'), array('category/create', 'id_parent'=>$data['id']), array('title'=>'Alkategória létrehozása')),
				'update' => CHtml::link(CHtml::image('/images/icons/update.png','Szerkesztés'), array('category/update', 'id'=>$data['id']), array('title'=>'Szerkesztés')),
				'order' => CHtml::link(CHtml::image('/images/icons/order.png','Sorrend'), array('category/order', 'id'=>$data['id']), array('title'=>'Sorrend')),
				'delete' => CHtml::ajaxLink(
					CHtml::image('/images/icons/sm2_delete.png','delete'),
					Yii::app()->createUrl('category/delete', array('id'=>$data['id'])),
					array(
						'type'=>'POST',
						'success'=>'function(data) {
									top.location.href="'.Yii::app()->createUrl('category/index').'"; 
							}',
						),
					array(
						'href'=>Yii::app()->createUrl('category/delete', array('id'=>$data['id'])),
						'confirm' => 'Biztosan törlöd?',
						'title'=>'Törlés',
						)
					),
			);
			
			if ($buttons) {
				$thisref['text'] = "<span title='{$data['slug']}'>".$data['title']."</span>";
				if ($data['id_parent'] <= 1) {
					$thisref['text'] .= ' ' . $button['addChild'];
					$thisref['text'] .= ' ' . $button['order'];
				}
				if ($data['id'] > 1) {
					$thisref['text'] .= ' ' . $button['update'];
					$thisref['text'] .= ' ' . $button['delete'];
				}
			} else {
				$thisref['text'] = $data['title'];
			}
			$thisref['slug'] = $data['slug'];

			if ($data['id_parent'] == 0) {
				$list[ $data['id'] ] = &$thisref;
			} else {
				$refs[ $data['id_parent'] ]['children'][ $data['id'] ] = &$thisref;
			}
		}
		return $list;	
	}
	
	public function beforeDelete(){

		if ($this->id_parent == 0) throw new CHttpException(400, 'A főkategória nem törölhető.');

		$res = Yii::app()->db->createCommand()
			->select('COUNT(*) as cnt')
			->from('category')
			->where('id_parent=:id', array('id'=>$this->id))
			->queryRow();

		if ($res['cnt'] > 0) throw new CHttpException(400, 'Csak üres kategória törölhető.');
		
		return parent::beforeDelete();
	}
	
	function existingParent() {
		if (!array_key_exists($this->id_parent, $this->getMainCategoryList())) {
			$this->addError('id_parent', 'A megadott kategória nem létezik, vagy nem főkategória.');
			return true;
		}
		return false;
	}
}
