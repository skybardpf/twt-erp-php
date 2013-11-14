<?php
/**
 * Сущность: Банковский счет.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $bank          (БИК or СВИФТ)
 *
 * @property string $bank_bik      идентификатор банка, в котором открыт счет (БИК)
 * @property string $bank_swift    идентификатор банка, в котором открыт счет (СВИФТ)
 * @property string $bank_name     название банка
 *
 * @property string $correspondent_bank_name
 * @property string $correspondent_bank
 * @property string $correspondent_bik
 * @property string $correspondent_swift
 *
 * @property string $name          наименование счета (представление)
 * @property string $id_yur        идентификатор юрлица-владельца счета
 * @property string $yur_name      название юр. лица
 * @property string $type_yur      Тип юрлица ("Контрагенты", "Организации")
 * @property bool   $deleted       признак пометки удаления (булево)
 * @property string $s_nom         номер счета (для российских счетов)
 *
 * @property string $type_account  вид счета
 * @property string $type_service  вид обслуживания счета
 * @property string $currency      идентификатор валюты счета
 * @property array  $managing_persons  массив идентификаторов физических лиц – управляющих счетом персон
 * @property string $management_method  метод управления счетом управляющими персонами
 */
class SettlementAccount extends SOAPModel
{
    const TYPE_VIEW_NOT_SELECTED = '---not_selected---';
    const PREFIX_CACHE_LIST_MODELS = '_list_models';
    const PREFIX_CACHE_LIST_MODELS_BY_ORG = '_list_models_by_org_';
    const PREFIX_CACHE_LIST_NAMES = '_list_names';

//    public $typeView;   // отформатированное представление
    public $json_managing_persons = '[]';

	/**
	 * @static
	 *
	 * @param string $className
	 *
	 * @return SettlementAccount
	 */
	public static function model($className = __CLASS__)
    {
		return parent::model($className);
	}

    /**
     * После __construct
     */
    public function afterConstruct()
    {
        $this->bank = '';
        $this->name = self::TYPE_VIEW_NOT_SELECTED;
        $this->type_yur = 'Организации';
        $this->correspondent_bank = '';
        $this->managing_persons = array();
        parent::afterConstruct();
    }

    /**
     * Список доступных видов счетов.
     * @static
     * @return  array
     */
    public static function getAccountTypes()
    {
        return array(
            'Расчетный' => 'Расчетный',
            'Депозитный' => 'Депозитный',
            'Ссудный'   => 'Ссудный',
            'Аккредитивный' => 'Аккредитивный',
            'Иной'      => 'Иной',
        );
    }

    /**
     * Список доступных видов обслуживания счета.
     * @static
     * @return array
     */
    public static function getServiceTypes()
    {
        return array(
            'Самостоятельно' => 'Самостоятельно',
            'По доверению подписанту' => 'По доверению подписанту',
            'Обслуживание у нас' => 'Обслуживание у нас'
        );
    }

    /**
     * Список доступных видов представление счета.
     * @return  array
     */
    public function getTypeView()
    {
        return array(
            '<ВидСчета> в <Банк>' => $this->type_account.' в '.$this->bank_name,
            '<НомерСчета>, <Банк>' => $this->s_nom.', '.$this->bank_name,
            '<Банк> (<ВидСчета>)' => $this->bank_name.' ('.$this->type_account.')'
        );
    }

    /**
     *  Список доступных видов обслуживания счета.
     *
     *  @static
     *  @return  array
     */
    public static function getManagementMethods()
    {
        return array(
            'ВсеВместе' => 'Требуются подписи всех',
            'ПоОдному' => 'Требуется подпись любого',
        );
    }

	/**
	 * Удаление Расчетного счета
	 * @return bool
	 */
	public function delete()
    {
		if ($this->primaryKey) {
			$ret = $this->SOAP->deleteSettlementAccount(array('id' => $this->primaryKey));
            $ret = SoapComponent::parseReturn($ret, false);
            if ($ret){
                $this->clearCache();
            }
            return $ret;
		}
		return false;
	}

	/**
	 * Сохранение Расчетного счета
	 * @return array
	 */
	public function save()
    {
		$data = $this->getAttributes();

		if (!$this->primaryKey) {
            unset($data['id']);
        }
		unset($data['deleted']);
        unset($data['managing_persons']);
        unset($data['json_managing_persons']);

        unset($data['bank_bik']);
        unset($data['bank_swift']);
        unset($data['bank_name']);

        unset($data['correspondent_swift']);
        unset($data['correspondent_bik']);
        unset($data['correspondent_bank_name']);

        $management_method = array(
            'Все вместе' => 'ВсеВместе',
            'По одному' => 'ПоОдному',
        );
        $data['management_method'] = isset($management_method[$data['management_method']]) ? $management_method[$data['management_method']] : $management_method['Все вместе'];

        $type_view = $this->getTypeView();
        $data['name'] = $type_view[$data['name']];

        $service = array(
            'Самостоятельно' => 'Самостоятельно',
            'По доверению подписанту' => 'ПоДоверениюПодписанту',
            'Обслуживание у нас' => 'ОбслуживаниеУНас'
        );
        $data['type_service'] = isset($service[$data['type_service']]) ? $service[$data['type_service']] : $service['Самостоятельно'];

		$ret = $this->SOAP->saveSettlementAccount(
            array(
                'data' => SoapComponent::getStructureElement($data),
                'managing_persons' => $this->managing_persons
            )
        );
		$ret = SoapComponent::parseReturn($ret, false);
        $this->clearCache();
		return $ret;
	}

    /**
     * Очищаем кэш.
     */
    public function clearCache()
    {
        if($this->primaryKey)
            Yii::app()->cache->delete(__CLASS__.self::PREFIX_CACHE_MODEL_PK.$this->primaryKey);
        if ($this->id_yur)
            Yii::app()->cache->delete(__CLASS__.self::PREFIX_CACHE_LIST_MODELS_BY_ORG.$this->id_yur);
        Yii::app()->cache->delete(__CLASS__.self::PREFIX_CACHE_LIST_MODELS);
        Yii::app()->cache->delete(__CLASS__.self::PREFIX_CACHE_LIST_NAMES);
    }

	/**
	 * Список Расчетных счетов
	 * @return SettlementAccount[]
	 */
	protected function findAll()
    {
		$filters = SoapComponent::getStructureElement($this->where);
		$request = array(
            'filters' => ($filters == array()) ? array(array()) : $filters,
            'sort' => ($this->order == array()) ? array(array()) : $this->order
        );

		$ret = $this->SOAP->listSettlementAccount($request);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Расчетный счет
	 * @param string $id
	 * @param bool $forceCache
	 * @return SettlementAccount
     * @throws CHttpException
	 */
	public function findByPk($id, $forceCache=false)
    {
        $cache_id = __CLASS__ . self::PREFIX_CACHE_MODEL_PK . $id;
        if ($forceCache || ($model = Yii::app()->cache->get($cache_id)) === false){
            $ret = $this->SOAP->getSettlementAccount(array('id' => $id));
            $ret = SoapComponent::parseReturn($ret);
            /**
             * @var SettlementAccount $model
             */
            $model = $this->publish_elem(current($ret), __CLASS__);
            if ($model === null) {
                throw new CHttpException(404, 'Не найден банковский счет.');
            }
            $model->json_managing_persons = CJSON::encode($model->managing_persons);

            $model->correspondent_bank = ((int)$model->correspondent_bik > 0) ? $model->correspondent_bik : (!empty($model->correspondent_swift) ? $model->correspondent_swift : '');
            $model->correspondent_bank_name = Bank::model()->getName($model->correspondent_bank, $forceCache);

            $model->bank = ((int)$model->bank_bik > 0) ? $model->bank_bik : (!empty($model->bank_swift) ? $model->bank_swift : '');
            $model->bank_name = Bank::model()->getName($model->bank);

            Yii::app()->cache->set($cache_id, $model, 0);
        }
        $model->forceCached = $forceCache;
        return $model;
	}

    /**
     * @return array
     */
    public function attributeNames()
    {
        return array(
            'id',           // string
            'name',         // string

            'id_yur',       // string
            'type_yur',     // string
            'deleted',      // bool

            'bank',         // string
            'bank_name',    // string
            'bank_bik',     // string
            'bank_swift',   // string

            'type_account', // string
            'type_service', // string

            's_nom',        // string
            'iban',         // string
            'currency',     // string

            'data_open',    // date
            'data_closed',  // date
            'address',      // string
            'contact',      // string

            'managing_persons', // array
            'management_method',// string

            'correspondent_bank_name',  // string
            'correspondent_bank',       // string
            'correspondent_bik',        // string
            'correspondent_swift',      // string

            'json_managing_persons',      // string
        );
    }

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels()
    {
		return array(
			'name'          => 'Представление',
            'id_yur'        => 'Юр.лицо',
            'type_yur'      => 'Тип юр.лица',

            'bank'          => 'Банк БИК / SWIFT',
			'bank_name'     => 'Название банка',
            'correspondent_bank_name' => 'Банк-корреспондент',
            'correspondent_bank'  => 'Банк-корреспондент. БИК / SWIFT',

            'type_service'  => 'Вид обслуживания счета',
            'type_account'  => 'Вид счета',

            's_nom'         => 'Номер счета',
            'iban'          => 'IBAN',
            'currency'      => 'Валюта',
            'data_open'     => 'Дата открытия',
            'data_closed'   => 'Дата закрытия',

            'address'       => 'Адрес отделения',
            'contact'       => 'Контакты в отделении',

            'managing_persons' => 'Управляющие персоны',
            'management_method' => 'Метод управления',
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
//            array('s_nom', 'Number', 'integerOnly'=>true),
            array('s_nom', 'length', 'max' => 20),

            array('iban', 'length', 'max' => 33),

            array('currency', 'required'),
            array('currency', 'in', 'range'  => array_keys(Currency::model()->listNames($this->forceCached))),

            array('bank', 'required'),
            array('bank', 'isValidBank'),

            array('correspondent_bank', 'isValidBankCor'),

            array('type_account', 'required'),
            array('type_account', 'in', 'range'  => array_keys(SettlementAccount::getAccountTypes())),

            array('type_service', 'required'),
            array('type_service', 'in', 'range'  => array_keys(SettlementAccount::getServiceTypes())),

            array('name', 'required'),
            array('name', 'in', 'range' => array_keys($this->getTypeView())),

            array('data_open, data_closed', 'date', 'format' => 'yyyy-MM-dd'),

            array('address', 'length', 'max' => 100),
            array('contact', 'length', 'max' => 100),

            array('management_method', 'required'),
            array('management_method', 'in', 'range'  => array_keys(SettlementAccount::getManagementMethods())),

            array('managing_persons', 'required'),
            array('json_managing_persons', 'validJson'),

            array('bank_name, correspondent_bank_name', 'safe'),
        );
    }

    /**
     * Проверка правильности введенного идентификатора банка.
     * @param string $attribute
     */
    public function isValidBank($attribute)
    {
        if ($attribute === ''){

        }
        $name = Bank::model()->getName($this->$attribute, $this->forceCached);
        if (empty($name)){
            $this->addError($attribute, '{'.$this->getAttributeLabel($attribute).'} - Необходимо указать правильный БИК / SWIFT');
        }
    }

    /**
     * Проверка правильности введенного идентификатора банка.
     * @param string $attribute
     */
    public function isValidBankCor($attribute)
    {
        if (!empty($this->$attribute)){
            $name = Bank::model()->getName($this->$attribute, $this->forceCached);
            if (empty($name)){
                $this->addError($attribute, '{'.$this->getAttributeLabel($attribute).'} - Необходимо указать правильный БИК / SWIFT');
            }
        }
    }

    /**
     * Список всех счетов
     * @param bool $forceCache
     * @return SettlementAccount[]
     */
    public function listModels($forceCache=false){
        $cache_id = __CLASS__.self::PREFIX_CACHE_LIST_MODELS;
        if ($forceCache || ($data = Yii::app()->cache->get($cache_id)) === false){
            $data = $this->where('deleted', false)->findAll();
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * Список счетов для указанной организации.
     * @param Organization $org
     * @param bool $forceCache
     * @return SettlementAccount[]
     */
    public function listModelsByOrganization(Organization $org, $forceCache=false){
        $cache_id = __CLASS__.self::PREFIX_CACHE_LIST_MODELS_BY_ORG.$org->primaryKey;
        if ($forceCache || ($data = Yii::app()->cache->get($cache_id)) === false){
            $data = $this
                ->order('bank', 'asc')
                ->order('currency', 'asc')
                ->where('deleted', false)
                ->where('id_yur', $org->primaryKey)
                ->findAll();
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * Список наименовай счетов.
     * @param bool $forceCache
     * @return array
     */
    public function listNames($forceCache=false){
        $cache_id = __CLASS__.self::PREFIX_CACHE_LIST_NAMES;
        if ($forceCache || ($data = Yii::app()->cache->get($cache_id)) === false){
            $data = array();
            $models = $this->listModels($forceCache);
            foreach ($models as $model){
                $data[$model->primaryKey] = $model->name;
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }
}