<?php
/**
 *  Сущность: Банковский счет.
 *
 *  User: Skibardin A.A.
 *  Date: 28.06.13
 *
 *  @property string $bank          (БИК or СВИФТ)
 *  @property string $bank_bik      идентификатор банка, в котором открыт счет (БИК)
 *  @property string $bank_swift    идентификатор банка, в котором открыт счет (СВИФТ)
 *  @property string $bank_name     название банка
 *  @property string $name          наименование счета (представление)
 *  @property string $id_yur        идентификатор юрлица-владельца счета
 *  @property string $yur_name      название юр. лица
 *  @property string $type_yur      Тип юрлица ("Контрагенты", "Организации")
 *  @property bool   $deleted       признак пометки удаления (булево)
 *  @property string $s_nom         номер счета (для российских счетов)
 *  @property string $vid           вид счета
 *  @property string $service       вид обслуживания счета
 *  @property int    $cur           идентификатор валюты счета
 *  @property array  $managing_persons  массив идентификаторов физических лиц – управляющих счетом персон
 *  @property string $management_method  метод управления счетом управляющими персонами
 */
class SettlementAccount extends SOAPModel {
    public $cur_name    = '';
    public $yur_name    = '';
    public $div_persons = '';
    public $str_managing_persons = '';
	/**
	 * @static
	 *
	 * @param string $className
	 *
	 * @return SettlementAccount
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

    /**
     *  Список доступных видов счетов.
     *
     *  @static
     *  @return  array
     */
    public static function getAccountTypes() {
        return array(
            'Расчетный' => 'Расчетный',
            'Депозитный' => 'Депозитный',
            'Ссудный'   => 'Ссудный',
            'Аккредитивный' => 'Аккредитивный',
            'Иной'      => 'Иной',
        );
    }

    /**
     *  Список доступных видов обслуживания счета.
     *
     *  @static
     *  @return  array
     */
    public static function getServiceTypes() {
        return array(
            'Самостоятельно'    => 'Самостоятельно',
            'По доверению подписанту' => 'По доверению подписанту',
            'Обслуживание у нас' => 'Обслуживание у нас'
        );
    }

    /**
     *  Список доступных видов обслуживания счета.
     *
     *  @static
     *  @return  array
     */
    public static function getManagementMethods() {
        return array(
            'Все вместе'    => 'Все вместе',
            'По одному'     => 'По одному',
        );
    }

    /**
     *  Название банка по его идентификатору БИК или СВИФТ.
     *
     *  @static
     *  @param      string $bank_id (BIK or SWIFT)
     *  @return     string
     */
    public static function getBankName($bank_id) {
        $bank_name = '';
        if (!empty($bank_id)){
            $cache = new CFileCache();
            $cache_id = __CLASS__.'_bank_'.$bank_id;
            $bank_name = $cache->get($cache_id);
            if ($bank_name === false) {
                // BIK
                if (strlen($bank_id) == 9 && ctype_digit($bank_id)){
                    $banks = Banks::model()
                        ->where('deleted', false)
                        ->where('id', $bank_id)
                        ->findAll();
                } else {
                    $banks = Banks::model()
                        ->where('deleted', false)
                        ->where('swift', $bank_id)
                        ->findAll();
                }
                if (!empty($banks) && isset($banks[0]) && !empty($banks[0]->name)){
                    $bank_name = $banks[0]->name;
                    $cache->set($cache_id, $bank_name);
                } else {
                    $bank_name = '';
                }
            }
        }
        return $bank_name;
    }

	/**
	 * Удаление Расчетного счета
	 *
	 * @return bool
	 */
	public function delete() {
		if ($pk = $this->getprimaryKey()) {
			$ret = $this->SOAP->deleteSettlementAccount(array('id' => $pk));
			return $ret->return;
		}
		return false;
	}

	/**
	 * Сохранение Расчетного счета
	 * @return array
	 */
	public function save() {
		$data = $this->getAttributes();
        $data['type_yur'] = 'Организации';
        $data['type_recomend'] = 'ФизическиеЛица';
        $data['recomend'] = '';
        $data['e_nom'] = '';

		if (!$this->getprimaryKey()) {
            unset($data['id']);
        }
		unset($data['deleted']);
        unset($data['managing_persons']);
        unset($data['bank_bik']);
        unset($data['bank_swift']);
        unset($data['bank_name']);
        unset($data['corrbank']);
        unset($data['corr_account']);

        $management_method = array(
            'Все вместе' => 'ВсеВместе',
            'По одному' => 'ПоОдному',
        );
        $data['management_method'] = isset($management_method[$data['management_method']]) ? $management_method[$data['management_method']] : $management_method['Все вместе'];

        $service = array(
            'Самостоятельно' => 'Самостоятельно',
            'По доверению подписанту' => 'ПоДоверениюПодписанту',
            'Обслуживание у нас' => 'ОбслуживаниеУНас'
        );
        $data['service'] = isset($service[$data['service']]) ? $service[$data['service']] : $service['Самостоятельно'];

		$ret = $this->SOAP->saveSettlementAccount(
            array(
                'data' => SoapComponent::getStructureElement($data),
                'managing_persons' => CJSON::decode($this->str_managing_persons)
            )
        );
		$ret = SoapComponent::parseReturn($ret, false);
		return $ret;
	}

	/**
	 * Список Расчетных счетов
	 *
	 * @return SettlementAccount[]
	 */
	public function findAll() {
		$filters = SoapComponent::getStructureElement($this->where);
		if (!$filters) $filters = array(array());
		$request = array('filters' => $filters, 'sort' => array($this->order));

		$ret = $this->SOAP->listSettlementAccount($request);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Расчетный счет
	 *
	 * @param $id
	 * @return bool|SettlementAccount
	 * @internal param array $filter
	 */
	public function findByPk($id) {
		$ret = $this->SOAP->getSettlementAccount(array('id' => $id));
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
			'name'          => 'Представление',                     //
            'id_yur'        => 'Юр.лицо',                           // +
            'type_yur'      => 'Тип юр.лица',                       //
            'deleted'       => 'Помечен на удаление',               //

            'bank'          => 'БИК / SWIFT',                       //
			'bank_name'     => 'Название банка',                    //
			'bank_bik'      => 'БИК',
			'bank_swift'    => 'SWIFT',

            'service'       => 'Вид обслуживания счета',

            's_nom'         => 'Номер счета',
            'iban'          => 'IBAN',
            'cur'           => 'Валюта',
            'vid'           => 'Вид счета',
            'data_open'     => 'Дата открытия',
            'data_closed'   => 'Дата закрытия',

            'address'       => 'Адрес отделения',
            'contact'       => 'Контакты в отделении',

            'managing_persons' => 'Управляющие персоны',
            'management_method' => 'Метод управления',

            'corrbank'      => 'Банк-корреспондент',
            'recomend'      => 'Рекомендатель',

			'e_nom'         => '',
			'corr_account'  => 'Счет банка-корреспондента'
            /*
                Мультивалютный (флаг: да или нет, обязательное);
                Субсчет? (флаг: да или нет, обязательное);
                Родительский счет (другой элемент сущности расчетный счет, обязательное для субсчетов);
            */
		);
	}

    /**
     *  Валидация атрибутов.
     *
     *  @return array
     */
    public function rules()
    {
        return array(
            array('s_nom', 'required'),
            array('s_nom', 'length', 'max' => 20),

            array('iban', 'length', 'max' => 33),

            array('cur', 'required'),
            array('cur', 'in', 'range'  => array_keys(Currencies::getValues())),

            array('bank', 'required'),
            array('bank', 'isValidBank'),

            array('vid', 'required'),
            array('vid', 'in', 'range'  => array_keys(SettlementAccount::getAccountTypes())),

            array('service', 'required'),
            array('service', 'in', 'range'  => array_keys(SettlementAccount::getServiceTypes())),

            array('name', 'length', 'max' => 50),

            array('data_open, data_closed', 'date', 'format' => 'yyyy-MM-dd'),

            array('address', 'length', 'max' => 100),
            array('contact', 'length', 'max' => 100),

            array('management_method', 'required'),
            array('management_method', 'in', 'range'  => array_keys(SettlementAccount::getManagementMethods())),

            array('managing_persons', 'required'),
//            array('managing_persons', 'emptyPersons'),

            array('bank_name, str_managing_persons', 'safe'),
        );
    }

//    public function emptyPersons($attribute){
//        if (empty($this->$attribute) || !is_array($this->$attribute)){
//            $this->addError($attribute, 'Укажите список управляющих персон.');
//        }
//    }

    /**
     *  Проверка правильности введенного идентификатора банка.
     *
     *  @param $attribute
     */
    public function isValidBank($attribute){
        if (!empty($this->$attribute)){
            $name = SettlementAccount::getBankName($this->$attribute);
            if (empty($name)){
                $this->addError($attribute, 'Необходимо указать правильный БИК / SWIFT');
            }
        }
    }
}