<?php
/**
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class EventForm extends CFormModel
{
    public $for_organization = 1;
    public $country_id = null;

    /**
     * @return array
     */
    public function attributeLabels()
    {
        return array(
            'for_organization' => 'Фильтр',
            'country_id' => 'Страна',
        );
    }

    /**
     * @return array
     */
    public function rules()
    {
        $countries = Country::model()->listNames();
        $countries[''] = '--- Все ---';
        return array(
            array('for_organization', 'in', 'range' => array(1, 2)),
            array('country_id', 'in', 'range' => array_keys($countries)),
        );
    }
}