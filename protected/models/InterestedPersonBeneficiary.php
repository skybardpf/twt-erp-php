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

//    public function rules()
//    {
//        return array_merge(
//            parent::rules(),
//            array(
//                array('total_count_stake', 'numerical', 'integerOnly' => true, 'min'=> 0, 'max' => 1000),
//            )
//        );
//    }
}