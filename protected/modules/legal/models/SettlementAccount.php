<?php
/**
 * User: Forgon
 * Date: 03.04.13
 */
class SettlementAccount extends SOAPModel {
	/**
	 * @static
	 *
	 * @param string $className
	 *
	 * @return SettlementAccount
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * Удаление Расчетного счета
	 *
	 * @return bool
	 */
	public function delete() {
		if ($pk = $this->getprimaryKey()) {
			$ret = $this->SOAP->deleteSettlementAccount(array('id' => $pk));
			return $ret->return;
		}
		return false;
	}

	/**
	 * Сохранение Расчетного счета
	 * @return array
	 */
	public function save() {
		$attrs = $this->getAttributes();

		if (!$this->getprimaryKey()) unset($attrs['id']); // New record
		unset($attrs['deleted']);

		$ret = $this->SOAP->saveSettlementAccount(array('data' => SoapComponent::getStructureElement($attrs)));
		$ret = SoapComponent::parseReturn($ret, false);
		return $ret;
	}

	/**
	 * Список Расчетных счетов
	 *
	 * @return SettlementAccount[]
	 */
	public function findAll() {
		$filters = SoapComponent::getStructureElement($this->where);
		if (!$filters) $filters = array(array());
		$request = array('filters' => $filters, 'sort' => array($this->order));

		$ret = $this->SOAP->listSettlementAccount($request);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Расчетный счет
	 *
	 * @param $id
	 * @return bool|SettlementAccount
	 * @internal param array $filter
	 */
	public function findByPk($id) {
		$ret = $this->SOAP->getSettlementAccount(array('id' => $id));
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
			'name'          => 'Название',                          // +
			'id_yur'        => 'Юр.лицо',                           // +
			'deleted'       => 'Помечен на удаление',               // +
			'bank'          => 'Банк',               // +
			'corrbank'      => 'Банк-корреспондент',
			'service'       => '',
			'recomend'      => 'Рекомендатель',
			'data_closed'   => 'Дата закрытия',
			'address'       => '',
			's_nom'         => 'Номер счета',
			'cur'           => 'Валюта',
			'vid'           => 'Вид счета',
			'iban'          => 'IBAN',
			'contact'       => 'Контакты в отделении',
			'data_open'     => 'Дата открытия',
			'e_nom'         => '',
			'corr_account'  => 'Счет банка-корреспондента'
/*
Мультивалютный (флаг: да или нет, обязательное);
Субсчет? (флаг: да или нет, обязательное);
Родительский счет (другой элемент сущности расчетный счет, обязательное для субсчетов);
*/
		);
	}
}