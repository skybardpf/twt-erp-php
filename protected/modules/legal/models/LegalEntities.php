<?php
/**
 * User: Forgon
 * Date: 09.01.13
 */
class LegalEntities extends CModel {

	// TODO attributes
	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeNames()
	{
		return array(
			'id' => 'ID',
			'date_create' => 'Date Create',
			'date_edit' => 'Date Edit',
			'lft' => 'Lft',
			'rgt' => 'Rgt',
			'lvl' => 'Lvl',
			'title' => 'Title',
			'show' => 'Show'
		);
	}

	// TODO rules
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('title', 'required'),
			array('show', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, show', 'safe', 'on'=>'search'),
		);
	}
}