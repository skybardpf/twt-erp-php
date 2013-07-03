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
		$cacher->set(__CLASS__.'_values', false, 1);
		$cacher->set(__CLASS__.'_objects_'.$id, false, 1);

		$ret = $this->SOAP->deleteOrganization(array('id' => $id));
		return $ret->return;
	}

	/**
	 * Метод объекта - удаляет себя
	 * @return bool
	 */
	public function delete() {
		if ($pk = $this->primaryKey) {
			return $this->delete_by_id($pk);
		} else return false;
	}

	/**
	 * Сохранение Юр.Лица
	 * @return array
	 */
	public function save() {
		$cacher = new CFileCache();
		$cacher->set(__CLASS__.'_values', false, 1);

		$attrs = $this->getAttributes();

        //$attrs['resident'] = (boolean)intval($attrs['resident']);
        $attrs['creation_date'] = date('Y-m-d');
        if($attrs['sert_date'] == ''){
            $attrs['sert_date'] = date('Y-m-d', 0);
        }

		// New record
		if (!$this->primaryKey) unset($attrs['id']);
		else $cacher->set(__CLASS__.'_objects_'.$this->primaryKey, false, 1);

		unset($attrs['deleted']);

        $responce = array();
        try{
            $ret = $this->SOAP->saveOrganization(array('data' => SoapComponent::getStructureElement($attrs, array('convert_boolean' => true))));
            $ret = SoapComponent::parseReturn($ret, false);
            $responce = array(
                'error' => false,
                'errorMessage' => '',
                'id' => $ret
            );
        }
        catch (Exception $e){
            $responce = array(
                'error' => true,
                'errorMessage' => $e->getMessage(),
                'id' => null
            );
        }
		return $responce;
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
		$cacher = new CFileCache();
		$data = $cacher->get(__CLASS__.'_objects_'.$id);
		if (!$data) {
			$data = $this->SOAP->getOrganization(array('id' => $id));
			$data = SoapComponent::parseReturn($data);
			$data = current($data);
			$cacher->set(__CLASS__.'_objects_'.$id, $data, self::CACHE_TTL);
		} else {
			if (YII_DEBUG) Yii::log('model '.__CLASS__.' id:'.$id.' from cache', CLogger::LEVEL_INFO, 'soap');
		}
		return $this->publish_elem($data, __CLASS__);
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
            "id"            => '#',
            "country"       => 'Страна',
            "name"          => 'Наименование',
            "full_name"     => 'Полное наименование',
            'sert_date'     => 'Дата государственной регистрации',
            'inn'           => 'ИНН',
            'kpp'           => 'КПП',
            'ogrn'          => 'ОГРН',
            'vat_nom'       => 'VAT',
            'reg_nom'       => 'Регистрационный номер',
            'sert_nom'      => 'Номер сертификата',
            'info'          => 'Дополнительная информация',
            'profile'       => 'Основной вид деятельности',
            'yur_address'   => 'Юридический адрес',
            'fact_address'  => 'Фактический адрес',
            'email'         => 'Email',
            'phone'         => 'Телефон',
            'fax'           => 'Факс',
            'comment'       => 'Комментарий',
            'okopf'         => 'Организационно-правовая форма',
            'creation_date' => 'creation_date',

            // старые поля
            //'opf'           => 'Организационно-правовая форма',   // нету
            //'eng_name'      => 'Английское наименование',           // +
            //'resident'      => 'Резидент РФ',                       // + boolean
            //'type_no_res'   => 'Тип нерезидента',                   // + int
            'deleted'       => 'Помечен на удаление',                // +

        );
		/*
		"id":"000000001",
		"country":"643",
		"name":"ТВТконсалт",

		'sert_nom' => ''
        'eng_name' => ''
        'reg_nom' => ''
        'deleted' => false
        'resident' => true
        'profile' => ''
        'full_name' => 'ЗАО \"ТВТ консалт\"'
        'inn' => '7726700622'
        'type_no_res' => ''
        'sert_date' => ''
        'ogrn' => '1127746529426'
        'yur_address' => '115230, Москва г, Электролитный проезд, дом № 1, строение 3'
        'fact_address' => '109240, Москва г, Николоямская ул, дом № 26, строение 3'
        'kpp' => '772601001'
        'vat_nom' => ''
		*/
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		return array(
			array('country', 'required'),
			array('country', 'in', 'range' => array_keys(Countries::getValues())),

			array('name, full_name', 'required'),

            array('id, resident, type_no_res, contragent, parent,
                comment, inn, kpp, ogrn, yur_address, fact_address,
                reg_nom, sert_nom, sert_date, vat_nom,
                profile, eng_name', 'safe'
            ),

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
	 *  Список доступных значений Собственных Юр.Лиц
	 *
     *  @return array
	 */
	static function getValues() {
		$cacher = new CFileCache();
		$cache = $cacher->get(__CLASS__.'_values');
		if ($cache === false) {
			if (!self::$values) {
				$elements = self::model()->findAll();
				$return   = array();
				if ($elements) { foreach ($elements as $elem) {
					$return[$elem->getprimaryKey()] = $elem->name;
				} }
				self::$values = $return;

			}
			$cacher->add(__CLASS__.'_values', self::$values, 3000);
		} elseif (!self::$values) {
			self::$values = $cache;
		}
		return self::$values;
	}
}