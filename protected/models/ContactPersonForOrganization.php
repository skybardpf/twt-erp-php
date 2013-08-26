<?php
/**
 * Контактное лицо для организации.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $id
 * @property string $name
 */
class ContactPersonForOrganization extends SOAPModel
{
    const PREFIX_CACHE_ID_LIST_NAMES = '_list_names';

    /**
     * @static
     * @param string $className
     * @return ContactPersonForOrganization
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Список контактных лиц для организаций.
     * @return ContactPersonForOrganization[]
     */
    protected function findAll()
    {
        $filters = SoapComponent::getStructureElement($this->where);
        if (!$filters) {
            $filters = array(array());
        }
        $request = array('filters' => $filters, 'sort' => array($this->order));
        $ret = $this->SOAP->listContactPersonsForOrganization($request);

        $ret = SoapComponent::parseReturn($ret);
        return $this->publish_list($ret, __CLASS__);
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
     * Returns the list of attribute names of the model.
     * @return array list of attribute names.
     */
    public function attributeLabels()
    {
        return array(
            'id' => '#',
            'name' => 'ФИО',
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('name', 'required'),
        );
    }

    /**
     * Список контактных лиц для организаций. Формат [key => name].
     * Результат сохраняется в кэш.
     * @param bool $force_cache
     * @return array
     */
    public function listNames($force_cache = false)
    {
        $cache_id = get_class($this).self::PREFIX_CACHE_ID_LIST_NAMES;
        if ($force_cache || ($data = Yii::app()->cache->get($cache_id)) === false) {
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