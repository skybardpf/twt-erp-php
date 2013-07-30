<?php
/**
 * Модель: "Заинтересованные персоны".
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @property string $id_yur
 * @property string $type_yur       Тип юр. лица: Организации, контрагенты
 * @property string $role
 * @property string $date           Дата встепления в должность.
 * @property string $lico           Название юр. лица, либо ФИО физ. лица.
 * @property string $type_lico      Тип лица: юр. лицо или физ. лицо.
 * @property string $typeStock      Вид акций (обычные, привелигерованные)
 * @property string $numPack        Номер пакета акций
 * @property bool   $deleted
 */
class InterestedPerson extends SOAPModel
{
    const ROLE_SHAREHOLDER = 'Номинальный акционер';
    const ROLE_BENEFICIARY = 'Бенефициар';
    const ROLE_DIRECTOR = 'Директор';
    const ROLE_SECRETARY = 'Секретарь';
    const ROLE_MANAGER = 'Менеджер';

    const TYPE_LICO_ORGANIZATION = 'Организации';
    const TYPE_LICO_INDIVIDUAL = 'ФизическиеЛица';

//    Директор Бенефициар НоминальныйАкционер Секретарь Менеджер

	/**
	 * Объект модели
	 * @static
	 * @param string $className
	 * @return InterestedPerson
	 */
	public static function model($className = __CLASS__)
    {
		return parent::model($className);
	}

    /**
     * Список доступных ролей.
     * @static
     * @return array
     */
    public static function getRoles()
    {
        return array(
            self::ROLE_SHAREHOLDER => self::ROLE_SHAREHOLDER,
            self::ROLE_BENEFICIARY => self::ROLE_BENEFICIARY,
            self::ROLE_DIRECTOR => self::ROLE_DIRECTOR,
            self::ROLE_SECRETARY => self::ROLE_SECRETARY,
            self::ROLE_MANAGER => self::ROLE_MANAGER,
        );
    }

    /**
     * Список доступных тип лиц.
     * @static
     * @return array
     */
    public static function getPersonTypes()
    {
        return array(
            self::TYPE_LICO_INDIVIDUAL => 'Физическое лицо',
            self::TYPE_LICO_ORGANIZATION => 'Юридическое лицо',
        );
    }

    /**
     * Список доступных тип акций.
     * @static
     * @return array
     */
    public static function getStockTypes()
    {
        return array(
            'Обыкновенные' => 'Обыкновенные',
            'Привилегированные' => 'Привилегированные',
        );
    }

	/**
	 * Список заинтересованных лиц
	 *
	 * @return InterestedPerson[]
	 */
	public function findAll()
    {
        $filters = SoapComponent::getStructureElement($this->where);
        $ret = $this->SOAP->listInterestedPersons(array(
            'filters' => (!$filters) ? array(array()) : $filters,
            'sort' => array($this->order)
        ));
        $ret = SoapComponent::parseReturn($ret);
        return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Заинтересованное лицо
	 *
	 * @param $id
	 * @param $id_yur
	 * @param $role
	 * @return bool|InterestedPerson
	 * @internal param array $filter
	 */
	public function findByPk($id, $id_yur, $role)
    {
		$ret = $this->SOAP->getInterestedPerson(
            array(
                'id' => $id,
                'id_yur' => $id_yur,
                'role' => $role
            )
        );
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_elem(current($ret), __CLASS__);
	}

	/**
	 * Удаление заинтересованного лица
	 *
	 * @return bool
	 */
	public function delete()
    {
		if ($pk = $this->getprimaryKey()) {
			$ret = $this->SOAP->deleteInterestedPersons(
                array(
                    'id' => $pk,
                    'id_yur' => $this->id_yur,
                    'role' => $this->role
                )
            );
			return $ret->return;
		}
		return false;
	}

    /**
     * Сохранение заинтересованного лица.
     *
     * @return string Если успешно, сохранилось, возвращает id записи.
     * @throws CHttpException
     */
    public function save()
    {
        $data = $this->getAttributes();

        if (!$this->primaryKey){
            unset($data['id']);
        }
        $data['deleted'] = ($data['deleted'] == 1) ? false : true;

//        if ($data['type_lico'] == self::TYPE_LICO_INDIVIDUAL){
//            $data['id'] = $data['list_individuals'];
//        } elseif ($data['type_lico'] == self::TYPE_LICO_ORGANIZATION){
//            $data['id'] = $data['list_organizations'];
//        } else {
//            throw new CHttpException(500, 'Неизвестный тип лица.');
//        }
        unset($data['list_organizations']);
        unset($data['list_individuals']);
        unset($data['yur_url']);
        unset($data['type_lico']);

        $data['type_lico'] = "Организации";
//        $data['role'] = "НоминальныйАкционер";

        $ret = $this->SOAP->saveInterestedPerson(array(
            'data' => SoapComponent::getStructureElement($data),
        ));
        return SoapComponent::parseReturn($ret, false);
    }

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels()
    {
		return array(
			'id'            => 'Лицо',
			'role'          => 'Роль',
			'add_info'      => 'Дополнительные сведения',
			'cost'          => 'Номинальная стоимость пакета акций',
			'percent'       => 'Величина пакета акций, %',
//			'vid'           => 'Вид лица', // (выбор из справочника юр. лиц или физ. лиц, обязательное); Физические лица
//			'cur'           => 'Валюта номинальной стоимости',
			'deleted'       => 'Текущее состояние',
			'id_yur'        => 'Юр.Лицо',
//			'name'          => 'Название',

            'yur_url'       => '',
            'type_yur'      => '',
            'lico'          => 'Лицо',
            'type_lico'     => 'Тип лица',
            'nominal'       => 'Номинал акции',
            'currency'      => 'Валюта',
            'quantStock'    => 'Кол-во акций',
            'date'          => 'Дата вступления в должность',
            'dateIssue'     => 'Дата выпуска пакета акций',
            'numPack'       => 'Номер пакета акций',
            'typeStock'     => 'Вид акций',
            'job_title'     => 'Наименование должности',

            'list_individuals'     => 'Список физ. лиц',
            'list_organizations'     => 'Список юр. лиц',
        );

		/*

	-	ID (уникальный идентификатор, целое число, обязательное);
	+	Юридическое лицо (выбор из справочника, обязательное);
	+	Вид лица (выбор из вариантов: физ. лицо, юр. лицо; обязательное);
	+	Лицо (выбор из справочника юр. лиц или физ. лиц, обязательное);
	+	Роль (выбор из списка, обязательное);
	+	Величина пакета акций (дробное число);
	+	Номинальная стоимость пакета акций (дробное число, 2 знака)
	+	Валюта номинальной стоимости (выбор из справочника валют);
	+	Дополнительные сведения (текст).

		id:10000000007,
        id_yur:0000000005,
		add_info:,
		cost:0,
		percent:0,
		control:,
		role:Номинальный акционер,
		vid:Физические лица,
		cur:
		deleted:false,
		*/
	}

	public function rules()
	{
        $stock_keys = array_keys(array_merge(array('' => ''), InterestedPerson::getStockTypes()));

//        var_dump($stock_keys);die;
		return array(
//			array('type_lico', 'required'),
//            array('type_lico', 'in', 'range' => array_keys(InterestedPerson::getPersonTypes())),

//			array('role', 'required'),
//			array('role', 'in', 'range' => array_keys(InterestedPerson::getRoles())),

//			array('list_individuals', 'in', 'range' => array_keys(Individuals::getValues())),
//			array('list_organizations', 'in', 'range' => array_keys(Organization::getValues())),

            array('job_title', 'length', 'max' => 100),

            array('date', 'required'),
            array('date', 'date', 'format' => 'yyyy-MM-dd'),

            array('dateIssue', 'date', 'format' => 'yyyy-MM-dd'),

            array('deleted', 'required'),
            array('deleted', 'boolean'),

            array('percent', 'numerical', 'integerOnly' => true),
            array('percent', 'validPercent'),
            array('numPack', 'numerical', 'integerOnly' => true),
            array('quantStock', 'numerical', 'integerOnly' => true),

            array('typeStock', 'in', 'range' => $stock_keys),

//			array('role, id_yur, id, vid', 'required'),
			array('add_info', 'safe'),

//			array('id, title, show', 'safe', 'on'=>'search'),
		);
	}

    /**
     * @param $attribute
     */
    public function validPercent($attribute)
    {
        if ($this->$attribute < 0 || $this->$attribute > 100){
            $this->addError($attribute, 'Величина пакета акций в %, должна находиться в диапозоне от 0 до 100.');
        }
    }
}