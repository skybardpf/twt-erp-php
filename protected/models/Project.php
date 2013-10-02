<?php
/**
 * Модель проектов.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $id
 * @property string $name
 */
class Project extends SOAPModel
{
    const PREFIX_CACHE_ID_LIST_NAMES = '_list_names';

    /**
     * @static
     * @param string $className
     * @return Project
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Список моделей проектов
     * @return Project[]
     */
    public function findAll()
    {
        $ret = $this->SOAP->listAdditionalProject(array(
            'filters' => array(array()),
            'sort' => array(array())
        ));
        $ret = SoapComponent::parseReturn($ret);
        return $this->publish_list($ret, __CLASS__);
    }

    /**
     * @return array
     */
    public function attributeNames()
    {
        return array(
            'id', // string
            'name', // string

        );
    }

    /**
     * Список наименований. Формат [key => name].
     * Результат сохранеятся в кеш.
     * @param bool $force_cache
     * @return array
     */
    public function listNames($force_cache = false)
    {
        $cache_id = __CLASS__ . self::PREFIX_CACHE_ID_LIST_NAMES;
        if ($force_cache || ($data = Yii::app()->cache->get($cache_id)) === false) {
            $data = array();
            $elements = self::model()->findAll();
            foreach ($elements as $elem) {
                $data[$elem->primaryKey] = $elem->name;
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }
}