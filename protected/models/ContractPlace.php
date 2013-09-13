<?php
/**
 * Список мест заключения контрактов.
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $name
 */
class ContractPlace extends SOAPModel
{
    const CACHE_PREFIX_LIST_NAMES = '_list_names';

    /**
     * @static
     * @param string $className
     * @return ContractPlace
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Список мест заключения контрактов.
     * @return ContractPlace[]
     */
    protected function findAll()
    {
        $filters = SoapComponent::getStructureElement($this->where);
        if (!$filters) {
            $filters = array(array());
        }
        $request = array('filters' => $filters, 'sort' => array($this->order));
        $ret = $this->SOAP->listContractPlaces($request);
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
            'id' => '#',
            'name' => 'Название',
        );
    }

    /**
     * @return array list of attribute names.
     */
    public function attributeNames()
    {
        return array(
            'id',
            'name',
        );
    }

    /**
     * Список мест заключения контрактов. Формат [key => name].
     * Результат сохранеятся в кеш.
     * @return array
     */
    public function listNames($forceCached = false)
    {
        $cache_id = __CLASS__. self::CACHE_PREFIX_LIST_NAMES;
        if ($forceCached || ($data = Yii::app()->cache->get($cache_id)) === false) {
            $elements = self::model()->findAll();
            $data = array();
            foreach ($elements as $elem) {
                $data[$elem->getprimaryKey()] = $elem->name;
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }
}