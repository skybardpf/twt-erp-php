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
    const PREFIX_CACHE_LIST_FULL_DATA_GROUP_BY = '_list_full_data';

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
    protected function listModels($force_cache = false)
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

    /**
     * @param bool $force_cache
     * @return array
     */
    public function getDataGroupBy($force_cache = false)
    {
        $cache_id = __CLASS__ . self::PREFIX_CACHE_LIST_FULL_DATA_GROUP_BY;
        if ($force_cache || ($groups = Yii::app()->cache->get($cache_id)) === false) {
            $data = $this->listModels($force_cache);
            $groups = array();
            foreach($data as $v){
                if (!empty($v->group_id)){
                    if (isset($groups[$v->group_id])){
                        $groups[$v->group_id][] = $v;
                    } else {
                        $groups[$v->group_id] = array($v);
                    }
                } else {
                    $groups[ContractorGroup::GROUP_ID_UNCATEGORIZED][] = $v;
                }
            }
            Yii::app()->cache->set($cache_id, $groups);
        }
        return $groups;
    }
}