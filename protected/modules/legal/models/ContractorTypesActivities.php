<?php
/**
 * Модель: Виды деятельности контрагентов.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @property string $id
 * @property string $name
 */

class ContractorTypesActivities extends SOAPModel {
    /**
     * @static
     * @param string $className
     * @return ContractorTypesActivities
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Список видов деятельности контрагентов.
     * @return ContractorTypesActivities[]
     */
    public function findAll()
    {
        $filters = SoapComponent::getStructureElement($this->where);
        $ret = $this->SOAP->listTypeActContr(
            array(
                'filters' => (!$filters) ? array(array()) : $filters,
                'sort' => array($this->order)
            ));
        $ret = SoapComponent::parseReturn($ret);
        return $this->publish_list($ret, __CLASS__);
    }

    /**
     * Returns the list of attribute names of the model.
     * @return array list of attribute names.
     */
    public function attributeLabels() {
        return array(
            "id"            => '#',
            "name"          => 'Наименование',
        );
    }

    /**
     *  Список доступных видов деятельности. С сохранением в кеше.
     *
     *  @return array
     */
    public static function getValues() {
        /**
         * @var $cache CFileCache
         */
        $cache = new CFileCache();
        $data = $cache->get(__CLASS__.'_values');
        if ($data === false) {
            $elements = self::model()->findAll();
            $data   = array();
            if ($elements) {
                foreach ($elements as $elem) {
                    $data[$elem->getprimaryKey()] = $elem->name;
                }
            }
            $cache->add(__CLASS__.'_values', $data, 3000);
        }
        return $data;
    }
}