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
	 * @param $id_yur
	 * @return bool|Beneficiary
	 * @internal param array $filter
	 */
	public function findByPk($id, $id_yur) {
		$ret = $this->SOAP->getBeneficiary(array('id' => $id, 'id_yur' => $id_yur));
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_elem(current($ret), __CLASS__);
	}

	/**
	 * Удаление бенефициара
	 *
	 * @return bool
	 */
	public function delete() {
		if ($pk = $this->getprimaryKey()) {
			$ret = $this->SOAP->deleteBeneficiary(array('id' => $pk, 'id_yur' => $this->id_yur));
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
			'id'            => 'Лицо',                                 // +
			'role'          => 'Роль',                              // +
			'id_yur'        => 'Юр.лицо',
			'add_info'      => 'Дополнительные сведения',
			'cost'          => 'Номинальная стоимость пакета акций',
			'deleted'       => 'Помечена на удаление',
			'percent'       => 'Величина пакета акций в процентах',
			'control'       => '',  /*??*/
			'vid'           => 'Вид лица',
			'cur'           => 'Валюта номинальной стоимости',

		);

		/*

		ID (уникальный идентификатор, целое число, обязательное);
		+   Юридическое лицо (выбор из справочника, обязательное);
		+   Вид лица (выбор из вариантов: физ. лицо, юр. лицо; обязательное);
		+   Лицо (выбор из справочника юр. лиц или физ. лиц, обязательное);
		+   Роль (выбор из списка, обязательное);
		+   Величина пакета акций в процентах (дробное число);
		+   Номинальная стоимость пакета акций (дробное число, 2 знака)
		+   Валюта номинальной стоимости (выбор из справочника валют);
		+   Дополнительные сведения (текст).

		*/
	}

	/**
	 * Правила валидации
	 * @return array
	 */
	public function rules()
	{
		return array(
			array('id_yur, vid, id, role', 'required'),
			array('cost, percent, cur, add_info', 'safe')
		);
	}

}