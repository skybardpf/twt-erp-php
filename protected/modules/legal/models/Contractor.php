<?php
/**
 * Модель: Контрагенты, сторонние организации.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @property string $id
 * @property string $name
 * @property string $country
 * @property string $country_name
 * @property string $creation_date
 * @property bool   $deleted
 * @property string $parent
 *
 * @property string $group_id
 * @property array  $signatories
 * @property string $json_signatories
 */
class Contractor extends AbstractOrganization {
    const TYPE = 'Организация';
    const PREFIX_CACHE_ID_LIST_FULL_DATA_GROUP_BY = '_list_full_data_group_by';

	/**
	 * @static
	 * @param string $className
	 * @return Contractor
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

    /**
     * Список контрагентов.
     *
     * @return Contractor[]
     */
    public function findAll()
    {
        $filters = SoapComponent::getStructureElement($this->where);

        $ret = $this->SOAP->listContragents(array(
            'filters' => (!$filters) ? array(array()) : $filters,
            'sort' => array($this->order)
        ));
        $ret = SoapComponent::parseReturn($ret);
        return $this->publish_list($ret, __CLASS__);
    }

    /**
     * @param string $id
     * @return Contractor Возвращает контрагента.
     */
    public function findByPk($id)
    {
        $ret = $this->SOAP->getContragent(array('id' => $id));
        $ret = SoapComponent::parseReturn($ret);
        return $this->publish_elem(current($ret), __CLASS__);
    }

    /**
     * Удаление.
     * @return bool
     */
    public function delete()
    {
        if ($pk = $this->getprimaryKey()) {
            $ret = $this->SOAP->deleteContragent(array('id' => $pk));
            $ret = SoapComponent::parseReturn($ret, false);
            if ($ret){
                $this->clearCache();
            }
            return $ret;
        }
        return false;
    }

	/**
	 * Сохранение контрагента
	 * @return string Возвращает id созданой записи (при добавлении) или
     * id отредактированного контрагента.
	 */
	public function save() {
        $data = $this->getAttributes();

        if (!$this->primaryKey){
            unset($data['id']);
            $data['creation_date'] = date('Y-m-d');
            $data['parent'] = '000000129';  // TODO изменить, когда будет авторизация
            $data['creator'] = 'Малхасян';  // TODO изменить, когда будет авторизация
        } elseif (is_null($data['parent'])) {
            $data['parent'] = '000000129';
        }
        unset($data['deleted']);
        unset($data['signatories']);

        if ($data['country'] == self::COUNTRY_RUSSIAN_ID){
            $data['vat_nom'] = '';
            $data['reg_nom'] = '';
            $data['sert_nom'] = '';
        } else {
            $data['inn'] = '';
            $data['kpp'] = '';
        }

//        var_dump($data);die;

        $ret = $this->SOAP->saveContragent(array(
            'data' => SoapComponent::getStructureElement($data),
            'signatories' => $this->signatories
        ));

        $this->clearCache();

        return SoapComponent::parseReturn($ret, false);
	}

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels() {
        return array(
            "id"            => '#',
            "country"       => 'Страна', // id
            "country_name"  => '',

            "name"          => 'Наименование',
            "full_name"     => 'Полное наименование',
            'creation_date' => 'Дата добавления',
            'parent'        => '',
            'gendirector'   => 'Ген. директор',
            'creator'       => 'Пользователь, добавивший в систему',
            'deleted'       => 'Помечен на удаление',
            'sert_date'     => 'Дата государственной регистрации',
            'okopf'         => 'Организационно-правовая форма',
            'profile'       => 'Основной вид деятельности',
            'yur_address'   => 'Юридический адрес',
            'fact_address'  => 'Фактический адрес',
            'email'         => 'Email',
            'phone'         => 'Телефон',
            'fax'           => 'Факс',
            'comment'       => 'Комментарий',
            'info'          => 'Дополнительная информация',

            'signatories'   => 'Подписанты',
            'json_signatories'   => '', // private
            'group_id'      => 'Группа',

            // --- Для российских компаний
            'inn'           => 'ИНН',
            'kpp'           => 'КПП',

            // --- Для нероссийских компаний
            'vat_nom'       => 'VAT',
            'reg_nom'       => 'Регистрационный номер',
            'sert_nom'      => 'Номер сертификата',
        );
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		return array(
			array('country', 'required'),
			array('country', 'in', 'range' => array_keys(Countries::getValues())),

            array('gendirector', 'required'),
            array('gendirector', 'in', 'range' => array_keys(ContactPersonForContractors::getValues())),

            array('okopf', 'required'),
            array('okopf', 'in', 'range' => array_keys(CodesOKOPF::getValues())),

            array('profile', 'required'),
            array('profile', 'in', 'range' => array_keys(ContractorTypesActivities::getValues())),
//
			array('name, full_name', 'required'),
            array('name', 'length', 'max' => 50),
            array('full_name', 'length', 'max' => 100),

            /**
             * Russian country
             */
            array('inn, kpp', 'safe', 'on' => 'foreignCountry'),
            array('inn', 'validateInn', 'on' => 'russianCountry'),
            array('kpp', 'length', 'max' => 9, 'on' => 'russianCountry'),

            /**
             * Foreign country
             */
            array('reg_nom, sert_nom, vat_nom', 'safe', 'on' => 'russianCountry'),
            array('vat_nom, reg_nom, sert_nom', 'length', 'max' => 50, 'on' => 'foreignCountry'),

            array('sert_date', 'date', 'format' => 'yyyy-MM-dd'),

            array('info, comment', 'length', 'max' => 50),
            array('yur_address, fact_address, fax, phone', 'length', 'max' => 150),

            array('email', 'email'),

            array('json_signatories', 'validJson'),
		);
	}

    /**
     * @return array
     */
    public function getDataGroupBy(){
        $cache_id = get_class($this).self::PREFIX_CACHE_ID_LIST_FULL_DATA_GROUP_BY;
        $groups = Yii::app()->cache->get($cache_id);
        if ($groups !== false) {
            $data = $this->getFullData();

            $groups = array();
            foreach($data as $v){
                if (!empty($v->group_id)){
                    if (isset($groups[$v->group_id])){
                        $groups[$v->group_id][] = $v;
                    } else {
                        $groups[$v->group_id] = array($v);
                    }
                }
            }
            Yii::app()->cache->set($cache_id, $groups);
        }
        return $groups;
    }

    /**
     * @param string $attribute
     */
    public function validJson($attribute)
    {
        if (null === CJSON::decode($this->$attribute)){
            $this->addError($attribute, 'Не правильный формат JSON строки.');
        }
    }
}