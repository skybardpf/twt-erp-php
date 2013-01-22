<?php
/**
 * User: Forgon
 * Date: 09.01.13
 * @property int $id
 * @property string $full_name
 * @property string $name
 * @property string $country
 * @property string $resident
 * @property string $type_no_res
 * @property string $contragent
 * @property string $group_name
 * @property string $comment
 * @property string $inn
 * @property string $kpp
 * @property string $ogrn
 * @property string $yur_address
 * @property string $fact_address
 * @property string $reg_nom
 * @property string $sert_nom
 * @property string $vat_nom
 * @property string $profile
 * @property string $deleted
 *

 */
class LegalEntities extends SOAPModel {

	public $attributes4Save = array('id', 'name', 'kpp', 'ogrn', 'yur_address', 'fact_address');

	/**
	 * @static
	 *
	 * @param string $className
	 *
	 * @return LegalEntities
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
			$ret = $this->SOAP->deleteLegalEntity(array('id' => '1'.$pk));
			return $ret->return;
		}
		return false;
	}

	public function save() {
		if ($pk = $this->getprimaryKey()) {
			$this->id = '1'.$pk;
		}

		$attrs4save = array();
		foreach ($this->attributes4Save as $field_name) {
			if (array_key_exists($field_name, $this->attributes)) {
				$attrs4save[$field_name] = $this->attributes[$field_name];
			}
		}

		$ret = $this->SOAP->saveLegalEntity(array('data' => SoapComponent::getStructureElement($attrs4save)));

		if ($ret->return == 'false'  or $ret->return == false) {
			return false;
		} else {
			if (empty($this->id)) {
				$this->id = $ret->return;
			}
			return true;
		}
	}

	/**
	 * Get list of LegalEntities
	 *
	 * @param array $filter
	 * @param array $sort
	 * @return array
	 */
	public function findAll($filter = array(), $sort = array()) {
		$ret = $this->SOAP->listLegalEntities(array(
				'filters' => SoapComponent::getStructureElement(array(
					'yurlica' => '*',
//					'id' => '*'
				) + $filter),
				'sort' => SoapComponent::getStructureElement(array(
					'id' => 'asc'
				) + $sort)
			)
		);
		return SoapComponent::parseReturn($ret, get_class($this));
	}

	/**
	 * Get one legal entity
	 *
	 * @param $id
	 * @return bool|\LegalEntities
	 * @internal param array $filter
	 */
	public function findByPk($id) {
		$ret = $this->SOAP->getLegalEntity(array('id' => '1'.$id));
		if ($ret->return) {
			$object = new self();
			$object->setAttributes((array)$ret->return, false);
			return $object;
		}
		return false;
	}


	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels()
	{
		return array(
			'id'            => '#',
			'name'          => 'Сокращенное наименование',
			'full_name'     => 'Полное имя',
			'country'       => 'Страна юрисдикции',
			'resident'      => 'Резидент РФ',
			'type_no_res'   => 'Тип нерезидента',
			'contragent'    => 'Контрагент',
			'group_name'    => 'Группа контрагентов',
            'comment'       => 'Комментарий',
			'inn'           => 'ИНН',
			'kpp'           => 'КПП',
			'ogrn'          => 'ОГРН',
			'yur_address'   => 'Адрес юридический',
			'fact_address'  => 'Адрес фактический',
			'reg_nom'       => 'Регистрационный номер',
			'sert_nom'      => 'Номер сертификата о регистрации',
			'sert_date'     => 'Дата сертификата о регистрации',
			'vat_nom'       => 'VAT-номер',
			'profile'       => 'Основной вид деятельности',
			'deleted'       => 'Помечен на удаление'
			/*
Английское наименование (текст);
*/
		);
	}

	// TODO rules
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('name, full_name, country', 'required'),
			array('resident, contragent, type_no_res, group_name, comment, inn, kpp, ogrn, yur_address, fact_address, reg_nom, sert_nom, sert_date, vat_nom, profile', 'safe'),
			array('show', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, show', 'safe', 'on'=>'search'),

			/*
			Английское наименование (текст);
			*/

			/*
			Страна юрисдикции (выбор из справочника, обязательное);
			Резидент РФ (флаг: да или нет);
			Контрагент (флаг: да – сторонее лицо или нет – собственное; обязательное);
			Тип нерезидента (выбор из списка);
			Группа контрагентов (выбор из справочника);

		 */
		);
	}
}