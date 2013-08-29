<?php
/**
 * Модель: Библиотека шаблонов.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $id
 * @property string $group_id
 * @property string $name
 * @property string $path
 * @property bool   $deleted
*/
class TemplateLibrary extends SOAPModel
{
    const PREFIX_CACHE_LIST_MODELS = '_list_models';

	/**
	 * @static
	 * @param string $className
	 * @return TemplateLibrary
	 */
	public static function model($className = __CLASS__)
    {
		return parent::model($className);
	}

	/**
	 * Список шаблонов
	 * @return TemplateLibrary[]
	 */
	protected function findAll()
    {
        $request = array('filters' => array(array()), 'sort' => array($this->order));
		$ret = $this->SOAP->listLibraryTemplates($request);
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
            'group_id',      // string
            'path',          // string
            'deleted',       // bool
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
			'name' => 'Название',
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
     * Список шаблонов. Результат сохраняем в кеш.
     * @param bool $force_cache
     * @return array
     */
    public function listModels($force_cache = false)
    {
        $cache_id = __CLASS__ . self::PREFIX_CACHE_LIST_MODELS;
        if ($force_cache || ($data = Yii::app()->cache->get($cache_id)) === false) {
//            $data = array();
            $data = $this->where('deleted', false)->findAll();
//            foreach ($elements as $elem) {
//                $data[] = $elem->name;
//            }
//            asort($data);
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }
}