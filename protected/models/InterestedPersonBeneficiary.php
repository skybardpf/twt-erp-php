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
                array('value_stake', 'validValueStake'),
            )
        );
    }

    public function validValueStake()
    {
        if (!$this->primaryKey){
            $p = $this->_getPercentBeneficiary();
            if ($this->type_stake === 'Обыкновенные'){
                if (isset($p['common_value_stake']) && ($p['common_value_stake'] + $this->value_stake) > 100){
                    $this->addError('value_stake', 'Обычных акций будет '.($p['common_value_stake'] + $this->value_stake).'%. Не может быть больше 100%');
                }
            } elseif ($this->type_stake === 'Привилегированные'){
                if (isset($p['privileged_value_stake']) && ($p['privileged_value_stake'] + $this->value_stake) > 100){
                    $this->addError('value_stake', 'Привилегированных акций будет '.($p['privileged_value_stake'] + $this->value_stake).'%. Не может быть больше 100%');
                }
            }
        }
    }

    /**
     * @return array
     */
    private function _getPercentBeneficiary()
    {
        $model = $this->SOAP->getPercentBeneficiary(array(
            'filters' => SoapComponent::getStructureElement(array(
                'id_yur' => $this->id_yur,
                'type_yur' => $this->type_yur,
            )),
            'sort' => array(array()),
        ));

        $model = SoapComponent::parseReturn($model);
        return isset($model[0]) ? $model[0] : array();
    }
}