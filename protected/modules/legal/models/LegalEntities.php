<?php
/**
 * User: Forgon
 * Date: 09.01.13
 * @property int $id
 * @property string $full_name
 * @property string $name
 * @property string $country
 *
 */
class LegalEntities extends SOAPModel {

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

		}
	}

	/**
	 * Get list of LegalEntities
	 *
	 * @return array
	 */
	public function findAll() {
		$ret = $this->SOAP->listLegalEntities(array(
				'filters' => SoapComponent::getStructureElement(array(
					'yurlica' => '*', 'id' => '*'
				)),
				'sort' => SoapComponent::getStructureElement(array(
					'id' => 'asc'
				))
			)
		);
		$return = array();
		if ($ret->return) {
			$ret = (array)$ret->return;
			if (!empty($ret['ЮрЛицо'])) {
				$return = array();
				foreach ($ret['ЮрЛицо'] as $elem) {
					$object = new self();
					$object->setAttributes((array)$elem, false);
					$return[] = $object;
				}
			}
		}
		return $return;
	}

	/**
	 * Get one legal entity
	 *
	 * @param array $filter
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
			'name'          => 'Название',
			'full_name'     => 'Полное имя',
			'country'       => 'Страна юрисдикции',
			'resident'      => 'Не является резидентом РФ',
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
Сокращенное наименование (текст, обязательное);
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
			array('full_name, country', 'required'),
			array('contragent, type_no_res, group_name, comment, inn, kpp, ogrn, yur_address, fact_address, reg_nom, sert_nom, sert_date, vat_nom, profile', 'safe'),
			array('show', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, show', 'safe', 'on'=>'search'),

			/*
			Сокращенное наименование (текст, обязательное);
			Английское наименование (текст);
			*/

			/*
			Страна юрисдикции (выбор из справочника, обязательное);
			Не является резидентом РФ (флаг: да или нет);
			Контрагент (флаг: да – сторонее лицо или нет – собственное; обязательное);
			Тип нерезидента (выбор из списка);
			Группа контрагентов (выбор из справочника);

		 */
		);
	}
}