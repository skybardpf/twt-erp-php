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
 * @property string $currency
 *
 * @property string $individual_id
 * @property string $organization_id
 * @property string $contractor_id
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
     * Сохранение номинального акционера.
     * @param InterestedPersonShareholder $old_model
     * @return array Если успешно, сохранилось, возвращает массив со значениями:
     * [id, type_lico, id_yur, type_yur, date, number_stake],
     * иначе возвращает NULL.
     * @throws CException
     */
    public function save(InterestedPersonShareholder $old_model = null)
    {
        $data = $this->getAttributes();

        if ($this->type_lico == MTypeInterestedPerson::INDIVIDUAL)
            $data['id'] = $data['individual_id'];
        elseif($this->type_lico == MTypeInterestedPerson::ORGANIZATION)
            $data['id'] = $data['organization_id'];
        elseif($this->type_lico == MTypeInterestedPerson::CONTRACTOR)
            $data['id'] = $data['contractor_id'];
        else
            throw new CException('Указан неизвестный тип заинтересованного лица.');

        $data['deleted'] = ($data['deleted'] == 1) ? true : false;
        $data['type_person'] = $this->viewPerson;

        unset($data['individual_id']);
        unset($data['organization_id']);
        unset($data['contractor_id']);
        unset($data['person_name']);

        return $this->saveData($data, $old_model);
    }

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
                'type_stake',
                'count_stake',
                'nominal_stake',
                'currency',

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
                'type_stake' => 'Тип акций',
                'count_stake' => 'Кол-во акций',
                'nominal_stake' => 'Номинал акций',
                'currency' => 'Валюта номинала акций',

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

                array('count_stake', 'required'),
                array('count_stake', 'numerical', 'integerOnly' => true, 'min'=> 0),

                array('nominal_stake', 'required'),
                array('nominal_stake', 'numerical', 'integerOnly' => true, 'min'=> 0),

                array('type_stake', 'required'),
                array('type_stake', 'in', 'range' => array_keys(self::getStockTypes())),

                array('currency', 'required'),
                array('currency', 'in', 'range' => array_keys(Currency::model()->listNames($this->forceCached))),

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