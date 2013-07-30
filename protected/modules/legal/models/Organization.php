<?php
/**
 * Собственные Юр.Лица
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @property int $id
 * @property string $name
 * @property string $full_name
 * @property string $country
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
 */
class Organization extends SOAPModel {
    const COUNTRY_RUSSIAN_ID = 643;

    const PREFIX_CACHE_ID_LIST_INN = '_list_inn';
    const PREFIX_CACHE_ID_LIST_OGRN = '_list_ogrn';
    const PREFIX_CACHE_ID_LIST_FULL_DATA = '_list_full_data';
    const PREFIX_CACHE_ID_LIST_NAMES = '_list_names';

	/**
	 * @static
	 * @param string $className
	 * @return Organization
	 */
	public static function model($className = __CLASS__)
    {
		return parent::model($className);
	}

	/**
	 * Удаляем организацию.
	 * @return bool
	 */
	public function delete()
    {
		if ($pk = $this->primaryKey) {
            $ret = $this->SOAP->deleteOrganization(array('id' => $pk));
            $ret = SoapComponent::parseReturn($ret, false);
            if ($ret){
                $this->clearCache();
            }
            return $ret;
		}
        return false;
	}

	/**
	 * Сохранение организации.
	 * @return array
	 */
	public function save()
    {
		$data = $this->getAttributes();

        if($data['sert_date'] == ''){
            $data['sert_date'] = date('Y-m-d', 0);
        }

		// New record
		if (!$this->primaryKey) {
            unset($data['id']);
            $data['creation_date'] = date('Y-m-d');
            $data['creator'] = 'Малхасян'; // TODO изменить, когда будет авторизация
        }
		unset($data['deleted']);

        if ($data['country'] == self::COUNTRY_RUSSIAN_ID){
            $data['vat_nom'] = '';
            $data['reg_nom'] = '';
            $data['sert_nom'] = '';
        } else {
            $data['inn'] = '';
            $data['kpp'] = '';
            $data['ogrn'] = '';
        }

        $ret = $this->SOAP->saveOrganization(array(
            'data' => SoapComponent::getStructureElement($data, array('convert_boolean' => true)))
        );
        $ret = SoapComponent::parseReturn($ret, false);
        /**
         * Очищаем кеши связанные с организацией.
         */
        $this->clearCache();

		return $ret;
	}

	/**
	 * Список Собственных Юр.Лиц
	 *
	 * @return Organization[]
	 */
	public function findAll()
    {
		$filters = SoapComponent::getStructureElement($this->where);
		if (!$filters) $filters = array(array());
		$request = array('filters' => $filters, 'sort' => array($this->order));

		$ret = $this->SOAP->listOrganizations($request);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Собственное Юр.Лицо
	 * @param string $id
	 * @return Organization
	 */
	public function findByPk($id)
    {
        $data = $this->SOAP->getOrganization(array('id' => $id));
        $data = SoapComponent::parseReturn($data);
        $data = current($data);
		return $this->publish_elem($data, __CLASS__);
	}

    /**
     * Сбрасываем кеш по данной организации, для списка организаций, списка ИНН и ОГРН.
     */
    public function clearCache()
    {
        if ($this->primaryKey){
            Yii::app()->cache->delete(__CLASS__.'_'.$this->primaryKey);
        }
        Yii::app()->cache->delete(__CLASS__.self::PREFIX_CACHE_ID_LIST_FULL_DATA);
        Yii::app()->cache->delete(__CLASS__.self::PREFIX_CACHE_ID_LIST_NAMES);
        Yii::app()->cache->delete(__CLASS__.self::PREFIX_CACHE_ID_LIST_OGRN);
        Yii::app()->cache->delete(__CLASS__.self::PREFIX_CACHE_ID_LIST_INN);
    }

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels()
    {
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
	public function rules()
    {
		return array(
			array('country', 'required'),
			array('country', 'in', 'range' => array_keys(Countries::getValues())),

            array('okopf', 'required'),
            array('okopf', 'in', 'range' => array_keys(CodesOKOPF::getValues())),

            array('profile', 'required'),
            array('profile', 'in', 'range' => array_keys(ContractorTypesActivities::getValues())),

			array('name, full_name', 'required'),
            array('name', 'length', 'max' => 50),
            array('full_name', 'length', 'max' => 100),

            array('inn, kpp, ogrn', 'safe', 'on' => 'foreignCountry'),
            array('inn', 'validateInn', 'on' => 'russianCountry'),
            array('ogrn', 'validateOgrn', 'on' => 'russianCountry'),
            array('kpp', 'length', 'max' => 9, 'on' => 'russianCountry'),

            array('reg_nom, sert_nom, vat_nom', 'safe', 'on' => 'russianCountry'),
            array('vat_nom, reg_nom, sert_nom', 'length', 'max' => 50, 'on' => 'foreignCountry'),

            array('info, comment', 'length', 'max' => 50),
            array('yur_address, fact_address, fax, phone', 'length', 'max' => 150),

            array('email', 'email'),
            array('sert_date', 'date', 'format' => 'yyyy-MM-dd'),



//			array('id, title, show', 'safe', 'on'=>'search'),
		);
	}

	/**
	 *  Список названий организаций.
     *  @return array Формат [id => name]
	 */
	public static function getValues() {
        $cache_id = __CLASS__.self::PREFIX_CACHE_ID_LIST_NAMES;
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false) {
            $elements = self::getFullValues();
            $data = array();
            foreach ($elements as $elem) {
                $data[$elem->primaryKey] = $elem->name;
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
	}

    /**
     *  Список организаций.
     *  @return array [id => {Organization}]
     */
    public static function getFullValues() {
        $cache_id = __CLASS__.self::PREFIX_CACHE_ID_LIST_FULL_DATA;
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false) {
            $elements = self::model()->where('deleted', false)->findAll();
            $data = array();
            if ($elements) {
                foreach ($elements as $elem) {
                    $data[$elem->primaryKey] = $elem;
                }
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     *  Список уже существующих ИНН.
     *  @return array Формат [inn => id]
     */
    public static function listInn() {
        $cache_id = __CLASS__.self::PREFIX_CACHE_ID_LIST_INN;
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false) {
            $elements = self::getFullValues();
            $data = array();
            foreach ($elements as $elem) {
                if (!empty($elem->inn)){
                    $data[$elem->inn] = $elem->primaryKey;
                }
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     *  Список уже существующих ОГРН.
     *  @return array
     */
    public static function listOgrn() {
        $cache_id = __CLASS__.self::PREFIX_CACHE_ID_LIST_OGRN;
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false) {
            $elements = self::getFullValues();
            $data = array();
            foreach ($elements as $elem) {
                if (!empty($elem->ogrn)){
                    $data[$elem->ogrn] = $elem->primaryKey;
                }
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * Валидация ИНН.
     * @param string $attribute
     */
    public function validateInn($attribute)
    {
        if (!empty($this->$attribute)){
            if (!$this->isValidInn($this->$attribute)){
                $this->addError($attribute, 'Неправильный формат ИНН.');
            } else {
                $list = self::listInn();
                if ($this->primaryKey){
                    if (isset($list[$this->$attribute]) && ($list[$this->$attribute] != $this->primaryKey)){
                        $this->addError($attribute, 'Такой ИНН уже используется.');
                    }
                } elseif (isset($list[$this->$attribute])){
                    $this->addError($attribute, 'Такой ИНН уже используется.');
                }
            }
        }
    }

    /**
     * Валидация ОГРН.
     * @param string $attribute
     */
    public function validateOgrn($attribute)
    {
        if (!empty($this->$attribute)){
            $msg = $this->isValidOgrn($this->$attribute);
            if (!empty($msg)){
                $this->addError($attribute, $msg);
            } else {
                $list = self::listOgrn();
                if ($this->primaryKey){
                    if (isset($list[$this->$attribute]) && ($list[$this->$attribute] != $this->primaryKey)){
                        $this->addError($attribute, 'Такой ОГРН уже используется другой организацией.');
                    }
                } elseif (isset($list[$this->$attribute])){
                    $this->addError($attribute, 'Такой ОГРН уже используется другой организацией.');
                }
            }
        }
    }

    /**
     * @author Evgeniy Chernishev <EvgeniyRRU@gmail.com>
     * Метод выполняет проверку 13-значного ОГРН или 15-значного ОГРНИП
     * стандартному алгоритму
     * @param $value - значение 13-значного ОГРН или 15-значного ОГРНИП
     * @return string $msg - в случае успеха ничего не возвращает
     * в случае ошибки возвращает сообщение об ошибке
     */
    protected function isValidOgrn($value)
    {
        if (!ctype_digit($value)){
            return Yii::t('validator', 'Ошибка. ОГРН должен состоять только из цифр.');
        }

        if(strlen($value) == 13) {
            $check        = substr($value, 0, 12); // просто написать % для определения остатка тут не получилось
            $checkValue1  = $check / 11; // видать php на больших числах считает остаток не точно.
            $checkValue   = $check - (floor($checkValue1)) * 11;
            $controlValue = substr($value, 12);
        } elseif(strlen($value) == 15) {
            $check        = substr($value, 0, 14);
            $checkValue1  = $check / 11;
            $checkValue   = $check - (floor($checkValue1)) * 11;
            $controlValue = substr($value, 14);
        } else {
            return Yii::t('validator', 'Ошибка. ОГРН должен содержать 13 или 15 символов');
        }

        if($checkValue == 10) {
            $checkValue = 0;
        }
        if($checkValue == $controlValue) {
            return '';
        }
        return $msg = Yii::t('validate', "Ошибка. Неверный ОГРН.");
    }

    /**
     * Функция проверяет правильность ИНН.
     * @param string $inn
     * @return bool
     */
    protected function isValidInn($inn)
    {
        if ( preg_match('/\D/', $inn) )
            return false;

        $inn = (string) $inn;
        $len = strlen($inn);

        if ($len === 10){
            return $inn[9] === (string) (((
                        2*$inn[0] + 4*$inn[1] + 10*$inn[2] +
                        3*$inn[3] + 5*$inn[4] +  9*$inn[5] +
                        4*$inn[6] + 6*$inn[7] +  8*$inn[8]
                    ) % 11) % 10);
        } elseif ( $len === 12 ) {
            $num10 = (string) (((
                        7*$inn[0] + 2*$inn[1] + 4*$inn[2] +
                        10*$inn[3] + 3*$inn[4] + 5*$inn[5] +
                        9*$inn[6] + 4*$inn[7] + 6*$inn[8] +
                        8*$inn[9]
                    ) % 11) % 10);

            $num11 = (string) (((
                        3*$inn[0] +  7*$inn[1] + 2*$inn[2] +
                        4*$inn[3] + 10*$inn[4] + 3*$inn[5] +
                        5*$inn[6] +  9*$inn[7] + 4*$inn[8] +
                        6*$inn[9] +  8*$inn[10]
                    ) % 11) % 10);

            return $inn[11] === $num11 && $inn[10] === $num10;
        }
        return false;
    }
}