<?php
/**
 * Статья движения денежных средств
 * User: Forgon
 * Date: 21.02.13
 * @property int $id
 * @property string $name
 * @property string $deleted
 *
 */
class DDSArticle extends SOAPModel {

	/**
	 * @static
	 *
	 * @param string $className
	 *
	 * @return LUser
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * Список движения денежных средств
	 *
	 * @return DDSArticle[]
	 */
	public function findAll() {
		$ret = $this->SOAP->listDDS();
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels() {
		return array(
			'id'            => '#',
			'name'          => 'Название',
			'deleted'       => 'Удален'
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		return array(
			array('name', 'required'),
			array('id, name', 'safe', 'on'=>'search'),
		);
	}

}