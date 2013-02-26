<?php
/**
 * Группы контрагентов
 * User: Forgon
 * Date: 11.01.13
 *
 * @property int $id
 * @property string $name
 * @property int $parent
 * @property int $deleted
 * @property int $level
 */
class CounterpartiesGroups extends SOAPModel {

	static public $values = array();

	/**
	 * @static
	 *
	 * @param string $className
	 *
	 * @return CounterpartiesGroups
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * Удаление (снять или поставить на удаление)
	 *
	 * @return bool
	 */
	public function delete() {
		$cacher = new CFileCache();
		$cacher->add('CounterpartiesGroups_values', false, 1);
		if ($pk = $this->getprimaryKey()) {
			$ret = $this->SOAP->deleteCounterpartiesGroup(array('id' => $pk));
			return $ret->return;
		}
		return false;
	}

	/**
	 * Список групп контрагентов
	 *
	 * @return CounterpartiesGroups[]
	 */
	public function findAll() {
		$filters = SoapComponent::getStructureElement($this->where);
		if (!$filters) $filters = array(array());
		$request = array('filters' => $filters, 'sort' => array($this->order));
		$ret = $this->SOAP->listCounterpartiesGroups($request);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Получение группы контрагентов
	 *
	 * @param $id
	 * @return bool|CounterpartiesGroups
	 * @internal param array $filter
	 */
	public function findByPk($id) {
		$ret = $this->SOAP->getCounterpartiesGroups(array('id' => $id));
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_elem(current($ret), __CLASS__);
	}

	/**
	 * Сохранение группы контрагентов
	 * @return array
	 */
	public function save() {
		$cacher = new CFileCache();
		$cacher->add('CounterpartiesGroups_values', false, 1);
		$attr = $this->attributes;
		if (!$this->getprimaryKey()) unset($attr['id']);
		if ($attr['parent'] === NULL) $attr['parent'] = '';
		unset($attr['deleted']);

		$data = array('data' => SoapComponent::getStructureElement($attr));
		$ret = $this->SOAP->saveCounterpartiesGroup($data);
		$ret = SoapComponent::parseReturn($ret);
		return $ret;
	}

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels() {
		return array(
			'id'            => '#',
			'name'          => 'Название',
			'parent'        => 'Родительский элемент',
			'deleted'       => 'Удален'
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		return array(
			array('name', 'required'),
			array('deleted, parent', 'safe'),
			array('id, name, deleted', 'safe', 'on'=>'search'),
		);
	}

	// ТУДУ: исключить своих потомков
	/**
	 * Получить доступные данному элементу родительские элементы
	 * @return array
	 */
	public function getParentValues() {
		$elements = $this->where('deleted', false)->findAll();
		$return   = array();
		$pk = $this->getprimaryKey();
		if ($elements) { foreach ($elements as $elem) {
			if (!$pk || $elem->getprimaryKey() != $pk) $return[$elem->getprimaryKey()] = $elem->name;
		} }
		return $return;
	}

	/**
	 * Список доступных значений групп контрагентов
	 * @return array
	 */
	static function getValues() {
		$cacher = new CFileCache();
		$cache = $cacher->get('CounterpartiesGroups_values');
		if ($cache === false) {
			if (!self::$values) {
				$elements = self::model()->where('deleted', false)->findAll();
				$return   = array();
				if ($elements) { foreach ($elements as $elem) {
					$return[$elem->getprimaryKey()] = $elem->name;
				} }
				self::$values = $return;

			}
			$cacher->add('CounterpartiesGroups_values', self::$values, 3000);
		} elseif (!self::$values) {
			self::$values = $cache;
		}
		return self::$values;
	}
}