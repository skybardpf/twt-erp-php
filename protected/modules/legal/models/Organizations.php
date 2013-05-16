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
	 * @param $id
	 *
	 * @return bool
	 */
	public function delete_by_id($id) {
		$cacher = new CFileCache();
		$cacher->set('Organizations_values', false, 1);

		$ret = $this->SOAP->deleteOrganization(array('id' => $id));
		return $ret->return;
	}

	/**
	 * Метод объекта - удаляет себя
	 * @return bool
	 */
	public function delete() {
		if ($pk = $this->getprimaryKey()) {
			$this->delete_by_id($pk);
		} else return false;
	}

	/**
	 * Сохранение Юр.Лица
	 * @return array
	 */
	public function save() {
		$cacher = new CFileCache();
		$cacher->set('LEntity_values', false, 1);

		$attrs = $this->getAttributes();

        $attrs['resident'] = (boolean)intval($attrs['resident']);
        
		if (!$this->getprimaryKey()) unset($attrs['id']); // New record
		unset($attrs['deleted']);

		//$ret = $this->SOAP->saveLegalEntity(array('data' => SoapComponent::getStructureElement($attrs))); // DEPRECATED
        $ret = $this->SOAP->saveOrganization(array('data' => SoapComponent::getStructureElement($attrs, array('convert_boolean' => true))));
		$ret = SoapComponent::parseReturn($ret, false);
		return $ret;
	}

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
        // так должно быть по макету
		/*return array(
            'id'            => '#',                                 // +
            'country'       => 'Страна',                            // + id
            'opf'           => 'Организационно-правовая форма',     // 
            'name'          => 'Наименование',                      // +
            'sert_date'      => 'Дата государственной регистрации',  // +
			'inn'           => 'ИНН',                               // +
			'kpp'           => 'КПП',                               // +
			'ogrn'          => 'ОГРН',                              // +
            'vat_nom'       => 'VAT',                               // +
            'reg_nom'       => 'Регистрационный номер',             // +
            'sert_nom'      => 'Номер сертификата',                 // +
            'profile'       => 'Основной вид деятельности',         // +            
			'yur_address'   => 'Юридический адрес',                 // +
			'fact_address'  => 'Фактический адрес',                 // +
            'email'         => 'Email',                             // +
            'phone'         => 'Телефон',                           // +
            'fax'           => 'Факс',                              // +
            'comment'       => 'Комментарий',                       // +
            
            // старые поля, не используются
            'full_name'     => 'Полное наименование',               // +
            'resident'      => 'Резидент РФ',                       // + boolean
            'type_no_res'   => 'Тип нерезидента',                   // + int
            'contragent'    => 'Контрагент',                        // + boolean
            'parent'        => 'Группа контрагентов',               // +
			'eng_name'      => 'Английское наименование',           // +
			'deleted'       => 'Помечен на удаление'                // +
            //'ogrn'          => 'ОГРН',                              // +
		);*/
        // а так есть по тому, что приходит с 1С
        return array(
            "id"            => '#',                                 // +
            "country"       => 'Страна',                            // + id
            //'opf'           => 'Организационно-правовая форма',   // нету
            "name"          => 'Краткое наименование',              // +
            'sert_date'      => 'Дата государственной регистрации',// нету
            'inn'           => 'ИНН',                               // +
            'kpp'           => 'КПП',                               // +
            'vat_nom'       => 'VAT',                               // +
            'reg_nom'       => 'Регистрационный номер',             // +
            'sert_nom'      => 'Номер сертификата',                 // +
            'profile'       => 'Основной вид деятельности',         // +          
            'yur_address'   => 'Юридический адрес',                 // +
            'fact_address'  => 'Фактический адрес',                 // +
            //'email'         => 'Email',                             // нету
            //'phone'         => 'Телефон',                           // нету
            //'fax'           => 'Факс',                              // нету
            //'comment'       => 'Комментарий',                       // нету            
            
            // старые поля, но все еще есть
            "full_name"     => 'Полное наименование',               // +
            'eng_name'      => 'Английское наименование',           // +
            'resident'      => 'Резидент РФ',                       // + boolean
            'type_no_res'   => 'Тип нерезидента',                   // + int
            'deleted'       => 'Помечен на удаление',                // +
            //'ogrn'          => 'ОГРН',                              // +
            
            // новые поля не понятно что в них совать
            'group_name'    => 'group_name'                           
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