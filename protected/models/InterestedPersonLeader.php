<?php
/**
 * Модель: "Заинтересованные персоны" -> Руководители.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $job_title
 * @property string $role
 * @property string $individual_id
 * @property string $contractor_id
 */
class InterestedPersonLeader extends InterestedPersonAbstract
{
    /**
     * Возвращает тип заинтересованного лица.
     * @return string
     */
    public function getViewPerson()
    {
        return MViewInterestedPerson::LEADER;
    }

    /**
     * Возвращает тип заинтересованного лица для страницы.
     * @return string
     */
    public function getPageTypePerson()
    {
        return MPageTypeInterestedPerson::LEADER;
    }

    /**
     * @return array
     */
    public function listPersonTypes()
    {
        return array(
            MTypeInterestedPerson::CONTRACTOR => 'Контрагент',
            MTypeInterestedPerson::INDIVIDUAL => 'Физ. лицо',
        );
    }

	/**
	 * @static
	 * @param string $className
	 * @return InterestedPersonLeader
	 */
	public static function model($className = __CLASS__)
    {
		return parent::model($className);
	}

    /**
     * Сохранение номинального акционера.
     * @param InterestedPersonLeader $old_model
     * @return array Если успешно, сохранилось, возвращает массив со значениями:
     * [id, type_lico, id_yur, type_yur, date, number_stake],
     * иначе возвращает NULL.
     * @throws CException
     */
    public function save(InterestedPersonLeader $old_model = null)
    {
        $data = $this->getAttributes();

//        if (!$this->primaryKey){
//            unset($data['id']);
//        }
        if ($this->type_lico == MTypeInterestedPerson::INDIVIDUAL)
            $data['id'] = $data['individual_id'];
        elseif($this->type_lico == MTypeInterestedPerson::CONTRACTOR)
            $data['id'] = $data['contractor_id'];
        else
            throw new CException('Указан неизвестный тип заинтересованного лица.');

        $data['deleted'] = ($data['deleted'] == 1) ? true : false;

        unset($data['individual_id']);
        unset($data['contractor_id']);
        unset($data['person_name']);

        $data['type_person'] = $this->viewPerson;

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
                'role',
                'job_title',
                'individual_id',
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

                array('contractor_id', 'required', 'on' => 'typeContractor'),
                array('contractor_id', 'in', 'range' => array_keys(Contractor::model()->getListNames($this->forceCached)), 'on' => 'typeContractor'),
            )
		);
	}
}