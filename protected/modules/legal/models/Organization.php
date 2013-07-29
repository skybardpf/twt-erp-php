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
class Organization extends SOAPModel {
	static protected $_nonResidentValues = array();
	static protected $_groupNameValues = array();
	static protected $_countryValues = array();

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
	 * Метод объекта - удаляет себя
	 * @return bool
	 */
	public function delete() {
		if ($pk = $this->primaryKey) {
            $ret = $this->SOAP->deleteOrganization(array('id' => $pk));
            $this->clearCache();
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
		$cacher->set(__CLASS__.'_values', false, 1);

		$attr = $this->getAttributes();

        $attr['creation_date'] = date('Y-m-d');
        if($attr['sert_date'] == ''){
            $attr['sert_date'] = date('Y-m-d', 0);
        }

		// New record
		if (!$this->primaryKey) {
            unset($attr['id']);
            $attr['creator'] = 'Малхасян'; // TODO изменить, когда будет авторизация
        } else {
            $cacher->set(__CLASS__.'_objects_'.$this->primaryKey, false, 1);
        }
		unset($attr['deleted']);

        $ret = $this->SOAP->saveOrganization(array(
            'data' => SoapComponent::getStructureElement($attr, array('convert_boolean' => true)))
        );
        $ret = SoapComponent::parseReturn($ret, false);

        $this->clearCache();

		return $ret;
	}

	/**
	 * Список Собственных Юр.Лиц
	 *
	 * @return Organization[]
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
	 * @return bool|Organization
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
     * Сбрасываем кеш по данной организации и для списка организаций.
     */
    public function clearCache()
    {
        if ($this->primaryKey){
            Yii::app()->cache->delete(__CLASS__.'_'.$this->primaryKey);
        }
        Yii::app()->cache->delete(__CLASS__.'_list');
    }

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels() {
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

            'creation_date' => 'Дата создания',
            'creator'       => 'Пользователь, добавивший в систему',

            'deleted'       => 'Помечен на удаление',                // +

        );
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		return array(
			array('country', 'required'),
			array('country', 'in', 'range' => array_keys(Countries::getValues())),

            array('okopf', 'required'),
            array('okopf', 'in', 'range' => array_keys(CodesOKOPF::getValues())),

			array('name, full_name', 'required'),

            array('id, resident, type_no_res, contragent, parent,
                comment, inn, kpp, ogrn, yur_address, fact_address,
                reg_nom, sert_nom, sert_date, vat_nom, info,
                phone, fax, profile, eng_name', 'safe'
            ),

            array('email', 'email'),

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
        $cache_id = __CLASS__.'_list';
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false) {
            $elements = self::model()->findAll();
            $data = array();
            if ($elements) {
                foreach ($elements as $elem) {
                    $data[$elem->getprimaryKey()] = $elem->name;
                }
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
	}
}