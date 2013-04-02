<?php
/**
 * Группа физических лиц
 * User: Forgon
 * Date: 26.02.13
 *
 * @param string $parent
 * @param string $name
 * @param string $level
 *
 * @param string $deleted
 */
class PEGroup extends SOAPModel {
	static public $values = array();

	/**
	 * Объект модели
	 * @static
	 *
	 * @param string $className
	 *
	 * @return Banks
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * Список групп физ.лиц
	 *
	 * @return PEGroup[]
	 */
	public function findAll() {
		$filters = SoapComponent::getStructureElement($this->where);
		if (!$filters) $filters = array(array());
		$request = array('filters' => $filters, 'sort' => array($this->order));
		$ret = $this->SOAP->listIndividualsGroups($request);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Получение группы физ.лиц
	 *
	 * @param $id
	 * @return bool|PEGroup
	 * @internal param array $filter
	 */
	public function findByPk($id) {
		$ret = $this->SOAP->getIndividualsGroups(array('id' => $id));
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_elem(current($ret), __CLASS__);
	}

	/**
	 * Сохранение группы физ.лиц
	 * @return array
	 */
	public function save() {
		$cacher = new CFileCache();
		$cacher->add('PEGroups_values', false, 1);
		$attr = $this->attributes;
		if (!$this->getprimaryKey()) unset($attr['id']);
		if ($attr['parent'] === NULL) $attr['parent'] = '';
		unset($attr['deleted']);

		$data = array('data' => SoapComponent::getStructureElement($attr));
		$ret = $this->SOAP->saveIndividualsGroup($data);
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
			'parent'        => 'Родительская группа',
			//'level'         => 'Уровень',
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

	/**
	 * Удаление группы физ.лиц
	 *
	 * @return bool
	 */
	public function delete() {
		$cacher = new CFileCache();
		$cacher->add('PEGroups_values', false, 1);
		if ($pk = $this->getprimaryKey()) {
			$ret = $this->SOAP->deleteIndividualsGroup(array('id' => $pk));
			return $ret->return;
		}
		return false;
	}

	/**
	 * Список доступных значений групп физ.лиц
	 * @return array
	 */
	static function getValues() {
		$cacher = new CFileCache();
		$cache = $cacher->get('PEGroups_values');
		if ($cache === false) {
			if (!self::$values) {
				$elements = self::model()->where('deleted', false)->findAll();
				$return   = array();
				if ($elements) { foreach ($elements as $elem) {
					$return[$elem->getprimaryKey()] = $elem->name;
				} }
				self::$values = $return;

			}
			$cacher->add('PEGroups_values', self::$values, 3000);
		} elseif (!self::$values) {
			self::$values = $cache;
		}
		return self::$values;
	}

	// ТУДУ: исключить своих потомков
	/**
	 * Получить доступные данному элементу родительские элементы
	 * @return array
	 */
	public function getParentValues() {
		$elements = $this->getValues();
		$pk = $this->getprimaryKey();
		if ($pk && isset($elements[$pk])) unset($elements[$pk]);
		return $elements;
	}
}