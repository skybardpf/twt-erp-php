<?php
/**
 * User: Forgon
 * Date: 11.01.13
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
	 * @static
	 *
	 * @param string $className
	 *
	 * @return Banks
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Get list of Banks
	 *
	 * @return array
	 */
	public function findAll() {
		$ret = $this->SOAP->listBanks();
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	public function findByPk($id) {
		/*$ret = $this->SOAP->getCounterpartiesGroups(array('id' => $id));
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_elem(current($ret), __CLASS__);
		$ret = $this->SOAP->listBanks();
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);*/
		throw new CHttpException(500, 'Нет метода getBank()');
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
	public function rules()
	{
		return array(
			array('name, country, city, address, phone, cor_sh, swift', 'required'),
			array('id, name, show', 'safe', 'on'=>'search'),
		);
	}
}