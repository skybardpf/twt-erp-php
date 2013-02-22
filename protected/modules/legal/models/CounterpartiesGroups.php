<?php
/**
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

	/**
	 * @static
	 *
	 * @param string $className
	 *
	 * @return CounterpartiesGroups
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Set or remove deletion mark
	 *
	 * @return bool
	 */
	public function delete() {
		if ($pk = $this->getprimaryKey()) {
			$ret = $this->SOAP->deleteCounterpartiesGroup(array('id' => $pk));
			return $ret->return;
		}
		return false;
	}

	/*public function save() {
		if ($pk = $this->getprimaryKey()) {
			$this->id = '1'.$pk;
		}
		$attributes = $this->attributes;
		unset($attributes['name']);
		unset($attributes['deleted']);
		$data = SoapComponent::getStructureElement($attributes);
		CVarDumper::dump($data);
		$this->SOAP->saveLegalEntity(SoapComponent::getStructureElement($this->attributes));
		exit;
		return false;
	}*/

	/**
	 * Get list of LegalEntities
	 *
	 * @return array
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
	 * Get one legal entity
	 *
	 * @param $id
	 * @return bool|\LegalEntities
	 * @internal param array $filter
	 */
	public function findByPk($id) {
		$ret = $this->SOAP->getCounterpartiesGroups(array('id' => $id));
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_elem(current($ret), __CLASS__);
	}

	public function save() {
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
	public function attributeLabels()
	{
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
	public function rules()
	{
		return array(
			array('name', 'required'),
			array('deleted, parent', 'safe'),
			array('id, name, deleted', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * Returns list of available values for parent field (excluding self only)
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
	 * Returns all not deleted groups as array(id => name)
	 * @return array
	 */
	static function getValues() {
		$elements = self::model()->where('deleted', false)->findAll();
		$return   = array();
		if ($elements) { foreach ($elements as $elem) {
			$return[$elem->getprimaryKey()] = $elem->name;
		} }
		return $return;
	}
}