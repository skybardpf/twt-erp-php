<?php
/**
 * Модель: "Заинтересованные персоны" -> Руководители.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $job_title
 * @property string $role
 * @property string $document_base
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
	 * Получение руководителя.
	 * @param string $id
	 * @param string $typeLico
	 * @param string $orgId
	 * @param string $orgType
	 * @param string $date
	 * @return InterestedPersonLeader
	 */
	public function findByPk($id, $typeLico, $orgId, $orgType, $date)
    {
		$ret = $this->SOAP->getPersonLeader(
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
                'role',
                'job_title',
                'document_base',

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
                'document_base' => 'Документ основание',

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

                array('document_base', 'required'),
//                array('document_base', 'date', 'format' => 'yyyy-MM-dd'),
            )
		);
	}
}