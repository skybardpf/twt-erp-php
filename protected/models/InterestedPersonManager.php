<?php
/**
 * Модель: "Заинтересованные персоны" -> Менеджеры.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $job_title
 */
class InterestedPersonManager extends InterestedPersonAbstract
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
	 * @static
	 * @param string $className
	 * @return InterestedPersonManager
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
	 * @return InterestedPersonManager
	 */
	public function findByPk($id, $typeLico, $orgId, $orgType, $date)
    {
		$ret = $this->SOAP->getPersonManager(
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
                'job_title',
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