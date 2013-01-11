<?php
/**
 * User: Forgon
 * Date: 11.01.13
 *
 * @property int $id
 * @property string $name
 * @property int $pid
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
	/*public function delete() {
		if ($pk = $this->getprimaryKey()) {
			$ret = $this->SOAP->deleteLegalEntity(array('id' => '1'.$pk));
			return $ret->return;
		}
		return false;
	}*/

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
		$ret = $this->SOAP->listCounterpartiesGroups();
		$return = array();
		/*if ($ret->return) {
			$ret = (array)$ret->return;
			if (!empty($ret['ЮрЛицо'])) {
				$return = array();
				if (is_array($ret['ЮрЛицо'])) {
					foreach ($ret['ЮрЛицо'] as $elem) {
						$object = new self();
						$object->setAttributes((array)$elem, false);
						$return[] = $object;
					}
				} else {
					$object = new self();
					$object->setAttributes((array)$ret['ЮрЛицо'], false);
					$return[] = $object;
				}
			}
		}*/
		return $return;
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
			'name'          => 'Название',
			'pid'           => 'Полное имя',

		);
	}

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