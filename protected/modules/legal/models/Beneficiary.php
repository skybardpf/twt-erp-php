<?php
/**
 * User: Forgon
 * Date: 02.04.13
 */

class Beneficiary extends SOAPModel {
	/**
	 * @static
	 *
	 * @param string $className
	 *
	 * @return Beneficiary
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * Список Бенефициаров
	 *
	 * @return Beneficiary[]
	 */
	public function findAll() {
		$filters = SoapComponent::getStructureElement($this->where);
		if (!$filters) $filters = array(array());
		$request = array('filters' => $filters, 'sort' => array($this->order));

		$ret = $this->SOAP->listBeneficiaries($request);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Бенефициар
	 *
	 * @param $id
	 * @return bool|Beneficiary
	 * @internal param array $filter
	 */
	public function findByPk($id) {
		$ret = $this->SOAP->getBeneficiary(array('id' => $id));
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_elem(current($ret), __CLASS__);
	}

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels() {
		return array(
			'id'            => '#',                                 // +
			'role'          => 'Роль',                              // +
			'id_yur'        => 'Юр.лицо'
		);
	}
}