<?php
/**
 * Модель: Виды деятельности контрагентов.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $id
 * @property string $name
 */
class ContractorTypesActivities extends SOAPModel
{
    const PREFIX_CACHE_LIST_NAMES = '_list_names';

    /**
     * @static
     * @param string $className
     * @return ContractorTypesActivities
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Список видов деятельности контрагентов.
     * @return ContractorTypesActivities[]
     */
    protected function findAll()
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
    public function attributeLabels()
    {
        return array(
            "id" => '#',
            "name" => 'Наименование',
        );
    }

    /**
     * @return array
     */
    public function attributeNames()
    {
        return array(
            'id',            // string
            'name',          // string
        );
    }

    /**
     * Список доступных видов деятельности. С сохранением в кеше.
     * @param bool $force_cache
     * @return array
     */
    public function listNames($force_cache = false)
    {
        $cache_id = __CLASS__. self::PREFIX_CACHE_LIST_NAMES;
        if ($force_cache ||($data = Yii::app()->cache->get($cache_id)) === false) {
            $elements = $this->findAll();
            $data = array();
            foreach ($elements as $elem) {
                $data[$elem->primaryKey] = $elem->name;
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }
}