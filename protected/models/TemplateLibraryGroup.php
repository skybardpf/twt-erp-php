<?php
/**
 * Модель: Группы шаблонов в библиотеке шаблонов
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $id
 * @property string $group_id
 * @property string $name
 * @property int    $level
 * @property bool   $deleted
*/
class TemplateLibraryGroup extends SOAPModel
{
    const PREFIX_CACHE_LIST_MODELS = '_list_models';
    const PREFIX_CACHE_LIST_ROOT_DATA = '_list_root_data';

	/**
	 * @static
	 * @param string $className
	 * @return TemplateLibraryGroup
	 */
	public static function model($className = __CLASS__)
    {
		return parent::model($className);
	}

	/**
	 * Список групп шаблонов
	 * @return TemplateLibraryGroup[]
	 */
	protected function findAll()
    {
        $request = array('filters' => array(array()), 'sort' => array($this->order));
		$ret = $this->SOAP->listTemplateGroups($request);
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
            'level',         // int
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
     * Список групп шаблонов. Результат сохраняем в кеш.
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
     * Возвращаем массив в виде дерева шаблонов, разбитых по группам.
     * @param array $data
     * @param bool $force_cache
     * @return array
     */
    public function getTreeTemplates(array $data, $force_cache = false)
    {
        $groups = $this->_getRootData($force_cache);
        $ret = array();
        foreach($groups as $group){
            $ret[] = array(
                'text' => $group->name,
                'children' => $this->_getChildren($group, $data),
                'leaf' => false
            );
        }
        if (isset($data[ContractorGroup::GROUP_ID_UNCATEGORIZED])){
            $ret[] = array(
                'text' => 'Список контрагентов' .
                Yii::app()->controller->widget(
                    'bootstrap.widgets.TbGridView',
                    array(
                        'type' => 'striped bordered condensed',
                        'dataProvider' => new CArrayDataProvider(
                            $data[ContractorGroup::GROUP_ID_UNCATEGORIZED],
                            array(
                                'pagination' => array(
                                    'pageSize' => 10000,
                                ),
                            )
                        ),
                        'template' => "{items} {pager}",
                        'columns' => array(
                            array(
                                'name' => 'name',
                                'header' => 'Название',
                                'type' => 'raw',
                                'value' => 'CHtml::link($data["name"], Yii::app()->getController()->createUrl("contractor/view", array("id" => $data["id"])))'
                            ),
                            array(
                                'name' => 'country_name',
                                'header' => 'Страна юрисдикции',
                            ),
                            array(
                                'name' => 'creation_date',
                                'header' => 'Дата добавления',
                            ),
                            array(
                                'name' => 'creator',
                                'header' => 'Пользователь, добавивший в систему',
                            ),
                        ),
                    ),
                    true
                ),
                'expanded' => false,
                'leaf' => true
            );
        }
        return $ret;
    }

    /**
     * @param TemplateLibraryGroup $group
     * @param array $contractors
     * @return array
     */
    private function _getChildren($group, array $contractors)
    {
        $ret = array();
        if (isset($contractors[$group->primaryKey])){
            $ret[] = array(
                'text' => 'Список контрагентов' .
                Yii::app()->controller->widget(
                    'bootstrap.widgets.TbGridView',
                    array(
                        'type' => 'striped bordered condensed',
                        'dataProvider' => new CArrayDataProvider(
                            $contractors[$group->primaryKey],
                            array(
                                'pagination' => array(
                                    'pageSize' => 10000,
                                ),
                            )
                        ),
                        'template' => "{items} {pager}",
                        'columns' => array(
                            array(
                                'name' => 'name',
                                'header' => 'Название',
                                'type' => 'raw',
                                'value' => 'CHtml::link($data["name"], Yii::app()->getController()->createUrl("contractor/view", array("id" => $data["id"])))'
                            ),
                            array(
                                'name' => 'country_name',
                                'header' => 'Страна юрисдикции',
                            ),
                            array(
                                'name' => 'creation_date',
                                'header' => 'Дата добавления',
                            ),
                            array(
                                'name' => 'creator',
                                'header' => 'Пользователь, добавивший в систему',
                            ),
                        ),
                    ),
                    true
                ),
                'expanded' => false,
                'leaf' => true
            );
        }

        foreach($group->children as $child){
            $children = $this->_getChildren($child, $contractors);

            $ret[] = array(
                'id' => $child->id,
                'text' => $child->name,
                'children' => $children,
//                'leaf' => (empty($children))
                'leaf' => false
            );
        }
        return $ret;
    }

    /**
     * Список групп шаблонов. Сгрупировано по корневым группам.
     * @param bool $force_cache
     * @return TemplateLibraryGroup[] Формат [id => {TemplateLibraryGroup}].
     */
    private function _getRootData($force_cache = false)
    {
        $cache_id = get_class($this) . self::PREFIX_CACHE_LIST_ROOT_DATA;
        if ($force_cache || ($data = Yii::app()->cache->get($cache_id)) === false) {
            $elements = $this->listModels($force_cache);
            $tmp = array();
            $tmp_index = array();
            foreach ($elements as $elem) {
                $tmp[$elem->level][$elem->id] = $elem->group_id;
                $tmp_index[$elem->id] = $elem;
            }
            // TODO все переписать. Делал на коленке. Skibardin A.A.
            $data = array();
            for($i=0,$l=count($tmp)-1; $i<$l; $i++){
                foreach($tmp[$i] as $k=>$name){
                    $tmp_index[$k]->children = $this->_getChildrenByLevel($tmp_index, $k, $tmp[$i+1]);
                    if ($i == 0){
                        $data[$k] = $tmp_index[$k];
                    }
                }
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * @param array $tmp_index
     * @param string $parent_id
     * @param array $data
     * @return array
     */
    private function _getChildrenByLevel($tmp_index, $parent_id, $data){
        $ret = array();
        foreach($data as $k=>$pid){
            if ($pid == $parent_id){
                $ret[] = $tmp_index[$k];
            }
        }
        return $ret;
    }

}