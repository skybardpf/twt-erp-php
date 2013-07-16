<?php
/**
 * Страна
 * User: Forgon
 * Date: 11.01.13
 *
 * @property int $id
 * @property string $name
 *
 * @property @static array $values
*/
class Countries extends SOAPModel {

	static public $values = array();

	/**
	 * @static
	 *
	 * @param string $className
	 *
	 * @return Countries
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * Список стран
	 *
	 * @return Countries[]
	 */
	public function findAll() {
        $request = array('filters' => array(), 'sort' => array($this->order));

		$ret = $this->SOAP->listCountries($request);
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

	/**
	 * Список доступных значений Стран
	 * id => name
	 *
	 * @return array
	 */
	static function getValues() {
		$cacher = new CFileCache();
		$cache = $cacher->get('countries_values');
		if ($cache === false) {
			if (!self::$values) {
				$elements = self::model()->findAll();
				$return   = array();
				if ($elements) {
                    foreach ($elements as $elem) {
					    $return[$elem->getprimaryKey()] = $elem->name;
				    }
                    asort($return);
                }
				self::$values = $return;

			}
			$cacher->add('countries_values', self::$values, 3000);
		} elseif (!self::$values) {
			self::$values = $cache;
		}
		return self::$values;
	}
}