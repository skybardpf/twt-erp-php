<?php
/**
 * Международные правила в формате словаря, обеспечивающие однозначные толкования наиболее широко используемых
 * торговых терминов в области внешней торговли.
 * @link http://ru.wikipedia.org/wiki/%D0%98%D0%BD%D0%BA%D0%BE%D1%82%D0%B5%D1%80%D0%BC%D1%81
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $id
 * @property string $name
 */
class Incoterm extends SOAPModel
{
    const PREFIX_CACHE_ID_LIST_NAMES = '_list_names';

    /**
     * @static
     * @param string $className
     * @return Incoterm
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Список моделей.
     * @return Incoterm[]
     */
    public function findAll()
    {
        $ret = $this->SOAP->listIncoterms(array(
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