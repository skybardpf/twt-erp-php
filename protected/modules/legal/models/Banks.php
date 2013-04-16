<?php
/**
 * Банк
 *
 * User: Forgon
 * Date: 11.01.13
 *
 * @property int $id
 * @property string $phone
 * @property string $swift
 * @property string $country
 * @property string $address
 * @property string $bik
 * @property string $city
 * @property string $cor_sh
 * @property string $name
 *
 * @property string $deleted
 */


class Banks extends SOAPModel {

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

	// ТУДУ фильтрация по полям
	/**
	 * Список банков
	 *
	 * @return Banks[]
	 */
	public function findAll() {
		$filters = SoapComponent::getStructureElement($this->where);
		if (!$filters) $filters = array(array());
		$request = array('filters' => $filters, 'sort' => array($this->order));

		$ret = $this->SOAP->listBanks($request);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	// ТУДУ - список банков с фильтрацией по id
	/**
	 * Получить один банк
	 * @param $id
	 *
	 * @return \Banks[]
	 * @throws CHttpException
	 */
	public function findByPk($id) {
		$this->where('id', $id);
		return $this->findAll();
	}

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels() {
		return array(
			'id'            => '#',
			'name'          => 'Название',
			'country'       => 'Страна юрисдикции',
			'city'          => 'Город',
			'address'       => 'Адрес',
			'phone'         => 'Телефон',
			'bik'           => 'БИК код',
			'cor_sh'        => 'Корр. счет',
			'swift'         => 'SWIFT код',
			'deleted'       => 'Помечен на удаление'
			//Адрес отделения
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		return array(
			array('name, country, city, address, phone, cor_sh, swift', 'required'),
			array('id, name, show', 'safe', 'on'=>'search'),
		);
	}
}