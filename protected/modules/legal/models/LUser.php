<?php
/**
 * User: Forgon
 * Date: 21.02.13
 *
 * @property int $id
 * @property string $name
 */
class LUser extends SOAPModel {

	static public $values = array();

	/**
	 * @static
	 *
	 * @param string $className
	 *
	 * @return LUser
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
		$ret = $this->SOAP->listUsers();
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
	 * Список доступных значений Пользователей
	 * @return array
	 */
	static function getValues() {
		$cacher = new CFileCache();
		$cache = $cacher->get('LUsers_values');
		if ($cache === false) {
			if (!LUser::$values) {
				$elements = self::model()->findAll();
				$return   = array();
				if ($elements) { foreach ($elements as $elem) {
					$return[$elem->getprimaryKey()] = $elem->name;
				} }
				LUser::$values = $return;
			}
			$cacher->add('LUsers_values', LUser::$values, 30);
		} elseif (!LUser::$values) {
			LUser::$values = $cache;
		}
		return LUser::$values;
	}
}