<?php
/**
 * Модель: "Заинтересованные персоны" -> Бенефициары.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $total_count_stake
 */
class InterestedPersonBeneficiary extends InterestedPersonShareholder
{
    /**
     * Возвращает тип заинтересованного лица.
     * @return string
     */
    public function getViewPerson()
    {
        return MViewInterestedPerson::BENEFICIARY;
    }

    /**
     * Возвращает тип заинтересованного лица для страницы.
     * @return string
     */
    public function getPageTypePerson()
    {
        return MPageTypeInterestedPerson::BENEFICIARY;
    }

    /**
	 * @static
	 * @param string $className
	 * @return InterestedPersonBeneficiary
	 */
	public static function model($className = __CLASS__)
    {
		return parent::model($className);
	}

    /**
     * @return array
     */
    public function attributeNames()
    {
        return array_merge(
            parent::attributeNames(),
            array(
                'total_count_stake',
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
                'total_count_stake' => 'Общее кол-во акций, %',
            )
        );
    }

    public function rules()
    {
        return array_merge(
            parent::rules(),
            array(
                array('total_count_stake', 'required'),
                array('total_count_stake', 'numerical', 'integerOnly' => true, 'min'=> 0, 'max' => 1000),
            )
        );
    }

//    /**
//     * Сохранение номинального акционера.
//     * @param InterestedPersonBeneficiary $old_model
//     * @return array Если успешно, сохранилось, возвращает массив со значениями:
//     * [id, type_lico, id_yur, type_yur, date, number_stake],
//     * иначе возвращает NULL.
//     * @throws CException
//     */
//    public function save(InterestedPersonBeneficiary $old_model = null)
//    {
//        $data = $this->getAttributes();
//
//        if ($this->type_lico == MTypeInterestedPerson::INDIVIDUAL)
//            $data['id'] = $data['individual_id'];
//        elseif($this->type_lico == MTypeInterestedPerson::ORGANIZATION)
//            $data['id'] = $data['organization_id'];
//        elseif($this->type_lico == MTypeInterestedPerson::CONTRACTOR)
//            $data['id'] = $data['contractor_id'];
//        else
//            throw new CException('Указан неизвестный тип заинтересованного лица.');
//
//        $data['deleted'] = ($data['deleted'] == 1) ? true : false;
//        $data['type_person'] = $this->viewPerson;
//
//        unset($data['individual_id']);
//        unset($data['organization_id']);
//        unset($data['contractor_id']);
//        unset($data['person_name']);
//
//        return $this->saveData($data, $old_model);
//    }
}