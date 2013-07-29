<?php
/**
 * User: Forgon
 * Date: 02.04.13
 */

class SAManager extends SOAPModel {
	/**
	 * @static
	 *
	 * @param string $className
	 *
	 * @return SAManager
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * Список управляющих расчетными счетами
	 *
	 * @return SAManager[]
	 */
	public function findAll() {
		$filters = SoapComponent::getStructureElement($this->where);
		if (!$filters) $filters = array(array());
		$request = array('filters' => $filters, 'sort' => array($this->order));

		$ret = $this->SOAP->listSettlementAccountManager($request);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Управляющий расчетным счетом
	 *
	 * @param $id
	 * @return bool|SAManager
	 * @internal param array $filter
	 */
	public function findByPk($id) {
		$ret = $this->SOAP->getSettlementAccountManager(array('id' => $id));
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_elem(current($ret), __CLASS__);
	}

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels() {
		return array(
			'id'                => '#',                                 // +
			'managing_rights'   => 'Вид прав на управление',            // +
			'id_acc'            => 'Расчетный счет',
			'manager'           => 'Физическое лицо',
			'user'              => '',
			'deleted'           => 'На удаление'
		);
		/*
		 *
		 \"id\":\"000000002\", \"managing_rights\":\"\", \"id_acc\":\"000000020\", \"manager\":\"10000000005\", \"user\":\"\", \"deleted\":false}]"

		    ID (уникальный идентификатор, целое число, обязательное);
			Расчетный счет (выбор из справочника, обязательное);
			Вид прав на управление (текст, обязательное);
			Физическое лицо (выбор из справочника физ.лиц, обязательное).

		 */
	}
}