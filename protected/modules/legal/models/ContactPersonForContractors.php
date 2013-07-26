<?php
/**
 * Список контактных лиц для контрагентов.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class ContactPersonForContractors extends SOAPModel {
    /**
     * @static
     * @param string $className
     * @return Countries
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Список контактных лиц для контрагентов.
     * @return ContactPersonForContractors[]
     */
    public function findAll() {
        $filters = SoapComponent::getStructureElement($this->where);
        if (!$filters) {
            $filters = array(array());
        }
        $request = array('filters' => $filters, 'sort' => array($this->order));
        $ret = $this->SOAP->listContactPersonsForContractors($request);

//        var_dump($ret);die;
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
            'name' => 'ФИО',
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
     * Список контактных лиц для контрагентов. Формат [key => name].
     * Результат сохранеятся в кеш.
     * @return array
     */
    public static function getValues() {
        $cache = new CFileCache();
        $cache_id = __CLASS__. 'values';
        $data = $cache->get($cache_id);
        if ($data === false) {
            $elements = self::model()->findAll();
            $data = array();
            if ($elements) {
                foreach ($elements as $elem) {
                    $data[$elem->getprimaryKey()] = $elem->name;
                }
            }
            $cache->add($cache_id, $data, 3000);
        }
        return $data;
    }
}