<?php
/**
 * Модель: "Заинтересованные персоны" -> Секретарь.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $job_title
 *
 * @property string $individual_id
 * @property string $organization_id
 * @property string $contractor_id
 */
class InterestedPersonSecretary extends InterestedPersonAbstract
{
    /**
     * Возвращает тип заинтересованного лица.
     * @return string
     */
    public function getViewPerson()
    {
        return MViewInterestedPerson::SECRETARY;
    }

    /**
     * Возвращает тип заинтересованного лица для страницы.
     * @return string
     */
    public function getPageTypePerson()
    {
        return MPageTypeInterestedPerson::SECRETARY;
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
	 * @return InterestedPersonSecretary
	 */
	public static function model($className = __CLASS__)
    {
		return parent::model($className);
	}

    /**
     * Сохранение секретаря.
     * @param InterestedPersonSecretary $old_model
     * @return array Если успешно, сохранилось, возвращает массив со значениями:
     * [id, type_lico, id_yur, type_yur, date, number_stake],
     * иначе возвращает NULL.
     * @throws CException
     */
    public function save(InterestedPersonSecretary $old_model = null)
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
                'job_title',
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
                'job_title' => 'Наименование должности',

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
                array('job_title', 'required'),
                array('job_title', 'length', 'max' => 100),

                array('individual_id', 'required', 'on' => 'typeIndividual'),
                array('individual_id', 'in', 'range' => array_keys(Individual::model()->listNames($this->forceCached)), 'on' => 'typeIndividual'),

                array('organization_id', 'required', 'on' => 'typeOrganization'),
                array('organization_id', 'in', 'range' => array_keys(Organization::model()->getListNames($this->forceCached)), 'on' => 'typeOrganization'),

                array('contractor_id', 'required', 'on' => 'typeContractor'),
                array('contractor_id', 'in', 'range' => array_keys(Contractor::model()->getListNames($this->forceCached)), 'on' => 'typeContractor'),
            )
		);
	}
}