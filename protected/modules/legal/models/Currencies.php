<?php
/**
 * User: Forgon
 * Date: 21.02.13
 *
 * @property int $id
 * @property string $name
 */
class Currencies extends SOAPModel {

	/**
	 * @static
	 *
	 * @param string $className
	 *
	 * @return Currencies
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Get list of Currencies
	 *
	 * @return array
	 */
	public function findAll() {
		$ret = $this->SOAP->listCurrencies();
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels()
	{
		return array(
			'id'            => '#',
			'name'          => 'Название',
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name', 'required'),
			array('id, name', 'safe', 'on'=>'search'),
		);
	}

}