<?php
/**
 * User: Forgon
 * Date: 11.01.13
 * @property int $id
 * @property string $name
*/
class Countries extends SOAPModel {

	/**
	 * @static
	 *
	 * @param string $className
	 *
	 * @return Countries
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Get list of Banks
	 *
	 * @return array
	 */
	public function findAll() {
		$ret = $this->SOAP->listCountries();
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

	/**
	 * Returns all not deleted groups as array(id => name)
	 * @return array
	 */
	static function getValues() {
		$elements = self::model()->findAll();
		$return   = array();
		if ($elements) { foreach ($elements as $elem) {
			$return[$elem->getprimaryKey()] = $elem->name;
		} }
		return $return;
	}
}