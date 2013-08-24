<?php
/**
 * Список мест расположения судов.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class CourtLocation extends SOAPModel {
    /**
     * @static
     * @param string $className
     * @return CourtLocation
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Список мест расположения судов.
     * @return CourtLocation[]
     */
    public function findAll() {
        $filters = SoapComponent::getStructureElement($this->where);
        if (!$filters) {
            $filters = array(array());
        }
        $request = array('filters' => $filters, 'sort' => array($this->order));
        $ret = $this->SOAP->listCourtLocations($request);
        $ret = SoapComponent::parseReturn($ret);
        return $this->publish_list($ret, __CLASS__);
    }

    /**
     * Returns the list of attribute names of the model.
     * @return array list of attribute names.
     */
    public function attributeLabels() {
        return array(
            'id' => '#',
            'name' => 'Название',
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('name', 'required'),
        );
    }

    /**
     * Список мест расположения судов. Формат [key => name].
     * Результат сохранеятся в кеш.
     * @return array
     */
    public static function getValues() {
        $cache_id = __CLASS__. '_list';
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false) {
            $elements = self::model()->findAll();
            $data = array();
            if ($elements) {
                foreach ($elements as $elem) {
                    $data[$elem->getprimaryKey()] = $elem->name;
                }
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }
}