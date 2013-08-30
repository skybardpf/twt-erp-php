<?php
/**
 * Модель: "Заинтересованные персоны" -> Номинальные акционеры.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $value_stake
 * @property string $date_issue_stake
 * @property string $number_stake
 * @property string $type_stake
 * @property string $count_stake
 * @property string $nominal_stake
 * @property string $currency_nominal_stake
 */
class InterestedPersonShareholder extends InterestedPersonAbstract
{
    /**
     * Возвращает тип заинтересованного лица.
     * @return string
     */
    public function getViewPerson()
    {
        return MViewInterestedPerson::SHAREHOLDER;
    }

	/**
	 * @static
	 * @param string $className
	 * @return InterestedPersonShareholder
	 */
	public static function model($className = __CLASS__)
    {
		return parent::model($className);
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
	 * Получение номинального акционера.
	 * @param string $id
	 * @param string $typeLico
	 * @param string $orgId
	 * @param string $orgType
	 * @param string $date
	 * @return InterestedPersonShareholder
	 */
	public function findByPk($id, $typeLico, $orgId, $orgType, $date)
    {
		$ret = $this->SOAP->getInterestedPerson(
            array(
                'id' => $id,
                'type_lico' => $typeLico,
                'id_yur' => $orgId,
                'type_yur' => $orgType,
                'date' => $date
            )
        );
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_elem(current($ret), __CLASS__);
	}

    /**
     * Сохранение заинтересованного лица.
     *
     * @return string Если успешно, сохранилось, возвращает id записи.
     * @throws CHttpException
     */
//    public function save()
//    {
//        $data = $this->getAttributes();
//
//        if (!$this->primaryKey){
//            unset($data['id']);
//        }
//        $data['deleted'] = ($data['deleted'] == 1) ? false : true;
//
////        if ($data['type_lico'] == self::TYPE_LICO_INDIVIDUAL){
////            $data['id'] = $data['list_individuals'];
////        } elseif ($data['type_lico'] == self::TYPE_LICO_ORGANIZATION){
////            $data['id'] = $data['list_organizations'];
////        } else {
////            throw new CHttpException(500, 'Неизвестный тип лица.');
////        }
//        unset($data['list_organizations']);
//        unset($data['list_individuals']);
//        unset($data['yur_url']);
//        unset($data['type_lico']);
//
//        $data['type_lico'] = "Организации";
////        $data['role'] = "НоминальныйАкционер";
//
//        $ret = $this->SOAP->saveInterestedPerson(array(
//            'data' => SoapComponent::getStructureElement($data),
//        ));
//        return SoapComponent::parseReturn($ret, false);
//    }

    /**
     * @return array
     */
    public function attributeNames()
    {
        return array_merge(
            parent::attributeNames(),
            array(
                'value_stake',
                'date_issue_stake',
                'number_stake',
                'type_stake',
                'count_stake',
                'nominal_stake',
                'currency_nominal_stake',
            )
        );
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
	}

	public function rules()
	{
//        $stock_keys = array_keys(array_merge(array('' => ''), InterestedPersonShareholder::getStockTypes()));

//        var_dump($stock_keys);die;
		return array(
//			array('type_lico', 'required'),
//            array('type_lico', 'in', 'range' => array_keys(InterestedPersonShareholder::getPersonTypes())),

//			array('role', 'required'),
//			array('role', 'in', 'range' => array_keys(InterestedPersonShareholder::getRoles())),

//			array('list_individuals', 'in', 'range' => array_keys(Individual::getValues())),
//			array('list_organizations', 'in', 'range' => array_keys(Organization::getValues())),

//            array('job_title', 'length', 'max' => 100),
//
//            array('date', 'required'),
//            array('date', 'date', 'format' => 'yyyy-MM-dd'),
//
//            array('dateIssue', 'date', 'format' => 'yyyy-MM-dd'),
//
//            array('deleted', 'required'),
//            array('deleted', 'boolean'),
//
//            array('percent', 'numerical', 'integerOnly' => true),
//            array('percent', 'validPercent'),
//            array('numPack', 'numerical', 'integerOnly' => true),
//            array('quantStock', 'numerical', 'integerOnly' => true),
//
//            array('typeStock', 'in', 'range' => $stock_keys),
//
////			array('role, id_yur, id, vid', 'required'),
//			array('add_info', 'safe'),

//			array('id, title, show', 'safe', 'on'=>'search'),
		);
	}

    /**
     * @param $attribute
     */
//    public function validPercent($attribute)
//    {
//        if ($this->$attribute < 0 || $this->$attribute > 100){
//            $this->addError($attribute, 'Величина пакета акций в %, должна находиться в диапозоне от 0 до 100.');
//        }
//    }
}