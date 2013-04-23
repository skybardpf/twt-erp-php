<?php
/**
 * Собственные Юр.Лица
 *
 * User: Forgon
 * Date: 23.04.2013 от рождества Христова
 *
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
class Organizations extends SOAPModel {
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
	 * Удаление Собственного Юр.Лица
	 *
	 * @return bool
	 */
	public function delete() {
		$cacher = new CFileCache();
		$cacher->set('Organizations_values', false, 1);
		if ($pk = $this->getprimaryKey()) {
			$ret = $this->SOAP->deleteOrganization(array('id' => $pk));
			return $ret->return;
		}
		return false;
	}

	/**
	 * Сохранение Юр.Лица
	 * @return array
	 */
	/*public function save() {
		$cacher = new CFileCache();
		$cacher->set('LEntity_values', false, 1);

		$attrs = $this->getAttributes();

		if (!$this->getprimaryKey()) unset($attrs['id']); // New record
		unset($attrs['deleted']);

		$ret = $this->SOAP->saveLegalEntity(array('data' => SoapComponent::getStructureElement($attrs)));
		$ret = SoapComponent::parseReturn($ret, false);
		return $ret;
	}*/

	/**
	 * Список Собственных Юр.Лиц
	 *
	 * @return Organizations[]
	 */
	public function findAll() {
		$filters = SoapComponent::getStructureElement($this->where);
		if (!$filters) $filters = array(array());
		$request = array('filters' => $filters, 'sort' => array($this->order));

		$ret = $this->SOAP->listOrganizations($request);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Собственное Юр.Лицо
	 *
	 * @param $id
	 * @return bool|Organizations
	 * @internal param array $filter
	 */
	public function findByPk($id) {
		$ret = $this->SOAP->getOrganization(array('id' => $id));
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
			'full_name'     => 'Полное наименование',               // +
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
/*
		id
		sert_nom:,
		country:643,
		eng_name:,
		reg_nom:,
		id:000000001,
		deleted:false,
		resident:true,
		profile:,
		full_name:ЗАО ТВТ консалт,
		inn:7726700622,
		type_no_res:,
		sert_date:,
		ogrn:1127746529426,
		yur_address:115230, Москва г, Электролитный проезд, дом № 1, строение 3,
		name:ТВТконсалт,
		fact_address:109240, Москва г, Николоямская ул, дом № 26, строение 3,
		kpp:772601001,
		vat_nom:
*/
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
	 * Список доступных значений Собственных Юр.Лиц
	 * @return array
	 */
	static function getValues() {
		$cacher = new CFileCache();
		$cache = $cacher->get('Organizations_values');
		if ($cache === false) {
			if (!self::$values) {
				$elements = self::model()->findAll();
				$return   = array();
				if ($elements) { foreach ($elements as $elem) {
					$return[$elem->getprimaryKey()] = $elem->name;
				} }
				self::$values = $return;

			}
			$cacher->add('Organizations_values', self::$values, 3000);
		} elseif (!self::$values) {
			self::$values = $cache;
		}
		return self::$values;
	}
}