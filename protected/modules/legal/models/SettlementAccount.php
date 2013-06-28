<?php
/**
 *  Сущность банковский счет.
 *
 *  User: Skibardin A.A.
 *  Date: 28.06.13
 *
 *  @property string $bank          идентификатор банка, в котором открыт счет (БИК или СВИФТ)
 *  @property string $name          наименование счета (представление)
 *  @property string $id_yur        идентификатор юрлица-владельца счета
 *  @property string $type_yur      Тип юрлица ("Контрагенты", "Организации")
 *  @property string $deleted       признак пометки удаления (булево)
 *  @property string $s_nom         номер счета (для российских счетов)
 *  @property string $vid           вид счета
 *  @property string $service       вид обслуживания счета
 *  @property int    $cur           идентификатор валюты счета
 *  @property array  $managing_persons  массив идентификаторов физических лиц – управляющих счетом персон
 *  @property string $management_method  метод управления счетом управляющими персонами
 */
class SettlementAccount extends SOAPModel {
    public $bank_name = '';
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
            'Депзитный' => 'Депзитный',
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

		if (!$this->getprimaryKey()) {
            unset($data['id']);
        }
		unset($data['deleted']);
        unset($data['managing_persons']);

        unset($data['bank_name']);
        unset($data['corrbank']);
        unset($data['corr_account']);

        // Что подставлять ?????
        $data['type_yur'] = 'Организации';
        $data['type_recomend'] = 'ФизическиеЛица';
        $data['recomend'] = '0000000007';
        $data['e_nom'] = '1234-02-13';

        $management_method = array(
            'Все вместе' => 'ВсеВместе',
            'По одному' => 'ПоОдному',
        );
        $data['management_method'] = $management_method[$data['management_method']];

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
			'name'          => 'Представление',                     // +
            'id_yur'        => 'Юр.лицо',                           // +
            'type_yur'      => 'Тип юр.лица',                       // +
            'deleted'       => 'Помечен на удаление',               // +
			'bank'          => 'БИК / SWIFT',                       // +
			'bank_name'     => 'Название банка',                    // +
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
//            array('typ_doc', 'in', 'range'  => array_keys(PowerAttorneysLE::getDocTypes())),
//            array('type_yur', 'in', 'range' => array_keys(PowerAttorneysLE::getYurTypes())),
//            array('id_lico', 'in', 'range'  => array_keys(Individuals::getValues())),
//			array('id_yur', 'in', 'range'  => array_keys(Organizations::getValues())),
//            id_yur,
            array(
                's_nom, iban, cur, vid, service, bank, name, address, contact, management_method, data_open, data_closed',
                'required'
            ),
            array('data_open, data_closed', 'date', 'format' => 'yyyy-MM-dd'),
//            array('', 'safe'),

            array('bank_name, str_managing_persons', 'safe'),
        );
    }
}