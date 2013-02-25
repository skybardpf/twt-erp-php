<?php
/**
 * Юр.Лица
 *
 * User: Forgon
 * Date: 09.01.13
 * @property int $id
 * @property string $name
 * @property string $eng_name
 * @property string $full_name
 * @property string $country
 * @property string $resident
 * @property string $type_no_res
 * @property string $contragent
 * @property string $parent
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
 * @property array NonResidentValues
 * @property array GroupNameValues
 * @property array CountryValues
 */
class LegalEntities extends SOAPModel {

	public $attributes4Save = array('id', 'name', 'kpp', 'ogrn', 'yur_address', 'fact_address');

	static protected $_nonResidentValues = array();
	static protected $_groupNameValues = array();
	static protected $_countryValues = array();
	static public $values = array();

	/**
	 * @static
	 *
	 * @param string $className
	 *
	 * @return LegalEntities
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * Удаление Юр.Лица
	 *
	 * @return bool
	 */
	public function delete() {
		$cacher = new CFileCache();
		$cacher->add('LEntity_values', false, 1);
		if ($pk = $this->getprimaryKey()) {
			$ret = $this->SOAP->deleteLegalEntity(array('id' => '1'.$pk));
			return $ret->return;
		}
		return false;
	}

	/**
	 * Сохранение Юр.Лица
	 * @return array
	 */
	public function save() {
		$cacher = new CFileCache();
		$cacher->add('LEntity_values', false, 1);
		if ($pk = $this->getprimaryKey()) {
			$this->id = $pk;
		}

		$attrs4save = array();
		foreach ($this->attributes4Save as $field_name) {
			if (array_key_exists($field_name, $this->attributes)) {
				$attrs4save[$field_name] = $this->attributes[$field_name];
			}
		}

		$attrs = $this->getAttributes();

		if (!$this->getprimaryKey()) unset($attrs['id']); // New record
		$attrs['contragent'] = (boolean)$this->contragent;
		$attrs['resident']   = (boolean)$this->resident;
		unset($attrs['deleted']);

		$ret = $this->SOAP->saveLegalEntity(array('data' => SoapComponent::getStructureElement($attrs)));
		$ret = SoapComponent::parseReturn($ret, false);
		return $ret;
	}

	/**
	 * Список Юр.Лиц
	 *
	 * @return LegalEntities[]
	 */
	public function findAll() {
		$filters = SoapComponent::getStructureElement($this->where);
		if (!$filters) $filters = array(array());
		$request = array('filters' => $filters, 'sort' => array($this->order));

		$ret = $this->SOAP->listLegalEntities($request);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Юр.Лицо
	 *
	 * @param $id
	 * @return bool|LegalEntities
	 * @internal param array $filter
	 */
	public function findByPk($id) {
		$ret = $this->SOAP->getLegalEntity(array('id' => $id));
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
			'name'          => 'Сокращенное наименование',          // +
			'full_name'     => 'Полное имя',                        // +
			'country'       => 'Страна юрисдикции',                 // + id
			'resident'      => 'Резидент РФ',                       // + boolean
			'type_no_res'   => 'Тип нерезидента',                   // + int
			'contragent'    => 'Контрагент',                        // + boolean
			'parent'        => 'Группа контрагентов',               // +
            'comment'       => 'Комментарий',                       // +
			'inn'           => 'ИНН',                               // +
			'kpp'           => 'КПП',                               // +
			'ogrn'          => 'ОГРН',                              // +
			'yur_address'   => 'Адрес юридический',                 // +
			'fact_address'  => 'Адрес фактический',                 // +
			'reg_nom'       => 'Регистрационный номер',             // +
			'sert_nom'      => 'Номер сертификата о регистрации',   // +
			'sert_date'     => 'Дата сертификата о регистрации',    // +
			'vat_nom'       => 'VAT-номер',                         // +
			'profile'       => 'Основной вид деятельности',         // +
			'eng_name'      => 'Английское наименование',           // +
			'deleted'       => 'Помечен на удаление'                // +
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		return array(
			array('country, name, full_name', 'required'),
			array('id, resident, type_no_res, contragent, parent, comment, inn, kpp, ogrn, yur_address, fact_address, reg_nom, sert_nom, sert_date, vat_nom, profile, eng_name', 'safe'),

			array('id, title, show', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * Типы нерезидентов
	 * @return array
	 */
	public function getNonResidentValues() {
		if (!LegalEntities::$_nonResidentValues) {
			$ret = $this->SOAP->listNonResidentsTypes();
			$ret = SoapComponent::parseReturn($ret);
			LegalEntities::$_nonResidentValues = $ret;
		}
		return LegalEntities::$_nonResidentValues;
	}

	/**
	 * Список доступных значений Юр.Лиц
	 * @return array
	 */
	static function getValues() {
		$cacher = new CFileCache();
		$cache = $cacher->get('LEntity_values');
		if ($cache === false) {
			if (!self::$values) {
				$elements = self::model()->findAll();
				$return   = array();
				if ($elements) { foreach ($elements as $elem) {
					$return[$elem->getprimaryKey()] = $elem->name;
				} }
				self::$values = $return;

			}
			$cacher->add('LEntity_values', self::$values, 30);
		} elseif (!self::$values) {
			self::$values = $cache;
		}
		return self::$values;
	}
}