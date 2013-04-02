<?php
/**
 * User: Forgon
 * Date: 02.04.13
 */
class InterestedPerson extends SOAPModel {
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
	 * Список заинтересованных лиц
	 *
	 * @return PEGroup[]
	 */
	public function findAll() {
		$filters = SoapComponent::getStructureElement($this->where);
		if (!$filters) $filters = array(array());
		$request = array('filters' => $filters, 'sort' => array($this->order));
		$ret = $this->SOAP->listInterestedPersons($request);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Заинтересованное лицо
	 *
	 * @param $id
	 * @return bool|InterestedPerson
	 * @internal param array $filter
	 */
	public function findByPk($id) {
		$ret = $this->SOAP->getInterestedPerson(array('id' => $id));
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_elem(current($ret), __CLASS__);
	}

	/**
	 * Удаление заинтересованного лица
	 *
	 * @return bool
	 */
	public function delete() {
		if ($pk = $this->getprimaryKey()) {
			$ret = $this->SOAP->deleteInterestedPerson(array('id' => $pk));
			return $ret->return;
		}
		return false;
	}

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels() {
		return array(
			'id'            => '#',
			'role'          => 'Роль',
			'deleted'       => 'Удален',
			'id_yur'        => 'Юр.Лицо'
		);
	}
}