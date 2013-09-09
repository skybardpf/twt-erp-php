<?php
/**
 * Модель: "Заинтересованные персоны" -> Менеджеры.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $job_title
 * @property string $individual_id
 */
class InterestedPersonManager extends InterestedPersonAbstract
{
    /**
     * Возвращает тип заинтересованного лица.
     * @return string
     */
    public function getViewPerson()
    {
        return MViewInterestedPerson::MANAGER;
    }

    /**
     * Возвращает тип заинтересованного лица для страницы.
     * @return string
     */
    public function getPageTypePerson()
    {
        return MPageTypeInterestedPerson::MANAGER;
    }

    /**
     * @return array
     */
    public function listPersonTypes()
    {
        return array(
            MTypeInterestedPerson::INDIVIDUAL => 'Физ. лицо',
        );
    }

	/**
	 * @static
	 * @param string $className
	 * @return InterestedPersonManager
	 */
	public static function model($className = __CLASS__)
    {
		return parent::model($className);
	}

    /**
     * Сохранение менеджера.
     * @param InterestedPersonManager $old_model
     * @return array Если успешно, сохранилось, возвращает массив со значениями:
     * [id, type_lico, id_yur, type_yur, date, number_stake],
     * иначе возвращает NULL.
     * @throws CException
     */
    public function save(InterestedPersonManager $old_model = null)
    {
        if ($this->type_lico != MTypeInterestedPerson::INDIVIDUAL)
            throw new CException('Указан неизвестный тип заинтересованного лица.');

        $data = $this->getAttributes();

        $data['id'] = $data['individual_id'];
        $data['deleted'] = ($data['deleted'] == 1) ? true : false;
        $data['type_person'] = $this->viewPerson;

        unset($data['individual_id']);
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
                'individual_id'
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

                array('individual_id', 'required'),
                array('individual_id', 'in', 'range' => array_keys(Individual::model()->listNames($this->forceCached)), 'on' => 'typeIndividual'),
            )
		);
	}
}