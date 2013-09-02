<?php
/**
 * Модель: "Заинтересованные персоны" -> Номинальные акционеры.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property int    $value_stake
 * @property string $date_issue_stake
 * @property int    $number_stake
 * @property string $type_stake
 * @property int    $count_stake
 * @property int    $nominal_stake
 * @property string $currency_nominal_stake
 *
 * @property string $percent
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
     * Возвращает тип заинтересованного лица для страницы.
     * @return string
     */
    public function getPageTypePerson()
    {
        return MPageTypeInterestedPerson::SHAREHOLDER;
    }

    /**
     * @return array
     */
    public function listPersonTypes()
    {
        return array(
            MTypeInterestedPerson::ORGANIZATION => 'Организация',
            MTypeInterestedPerson::CONTRACTOR => 'Контрагент',
            MTypeInterestedPerson::INDIVIDUAL => 'Физ. лицо',
        );
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
     * Инициализация перенменных.
     */
    public function afterConstruct()
    {
        $this->type_yur = MTypeOrganization::ORGANIZATION;
        parent::afterConstruct();
    }

    /**
     * Список доступных тип акций.
     * @return array
     */
    public function getStockTypes()
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
		$ret = $this->SOAP->getPersonShareHolder(
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
                'percent',
                'value_stake',
                'date_issue_stake',
                'number_stake',
                'type_stake',
                'count_stake',
                'nominal_stake',
                'currency_nominal_stake',

                'individual_id',
                'organization_id',
                'contractor_id',
            )
        );
    }

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels()
    {
        return array_merge(
            parent::attributeLabels(),
            array(
                'value_stake' => 'Величина пакета акций, %',
                'date_issue_stake' => 'Дата выпуска пакета акций',
                'number_stake' => 'Номер пакета акций',
                'type_stake' => 'Тип акций',
                'count_stake' => 'Кол-во акций',
                'nominal_stake' => 'Номинал акций',
                'currency_nominal_stake' => 'Валюта номинала акций',

                'individual_id' => 'Физическое лицо',
                'organization_id' => 'Организация',
                'contractor_id' => 'Контрагент',
            )
        );
	}

	public function rules()
	{
        return array_merge(
            parent::rules(),
            array(
                array('value_stake', 'required'),
                array('value_stake', 'numerical', 'integerOnly' => true, 'min'=> 0, 'max' => 100),

                array('date_issue_stake', 'required'),
                array('date_issue_stake', 'date', 'format' => 'yyyy-MM-dd'),

                array('number_stake', 'required'),
                array('number_stake', 'numerical', 'integerOnly' => true, 'min'=> 0),

                array('count_stake', 'required'),
                array('count_stake', 'numerical', 'integerOnly' => true, 'min'=> 0),

                array('nominal_stake', 'required'),
                array('nominal_stake', 'numerical', 'integerOnly' => true, 'min'=> 0),

                array('type_stake', 'required'),
                array('type_stake', 'in', 'range' => array_keys(self::getStockTypes())),

                array('currency_nominal_stake', 'required'),
                array('currency_nominal_stake', 'in', 'range' => array_keys(Currency::model()->listNames($this->forceCached))),

                array('individual_id', 'required'),
                array('individual_id', 'in', 'range' => array_keys(Individual::model()->listNames($this->forceCached)), 'on' => 'typeIndividual'),

                array('organization_id', 'required'),
                array('organization_id', 'in', 'range' => array_keys(Organization::model()->getListNames($this->forceCached)), 'on' => 'typeOrganization'),

                array('contractor_id', 'required'),
                array('contractor_id', 'in', 'range' => array_keys(Contractor::model()->getListNames($this->forceCached)), 'on' => 'typeContractor'),
            )
		);
	}
}