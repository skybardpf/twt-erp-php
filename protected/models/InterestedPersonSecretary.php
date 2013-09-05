<?php
/**
 * Модель: "Заинтересованные персоны" -> Секретарь.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $job_title
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
            )
		);
	}
}