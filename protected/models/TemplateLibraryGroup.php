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

    public $children = array();

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
            $data = $this->where('deleted', false)->findAll();
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
            foreach($data[ContractorGroup::GROUP_ID_UNCATEGORIZED] as $template){
                $ret[] = array(
                    'text' => CHtml::link(
                        CHtml::encode($template->name),
                        Yii::app()->createUrl('site/download', array(
                                'path' => $this->decodePath($template->path))
                        ),
                        array(
                            'target' => '_blank'
                        )
                    ),
                    'expanded' => false,
                    'leaf' => true
                );
            }
        }
        return $ret;
    }

    /**
     * @param TemplateLibraryGroup $group
     * @param array $templates
     * @return array
     */
    private function _getChildren($group, array $templates)
    {
        $ret = array();
        if (isset($templates[$group->primaryKey])){
            foreach($templates[$group->primaryKey] as $template){
                $ret[] = array(
                    'text' => CHtml::link(
                        CHtml::encode($template->name),
                        Yii::app()->createUrl('site/download', array(
                                'path' => $this->decodePath($template->path))
                        ),
                        array(
                            'target' => '_blank'
                        )
                    ),
                    'expanded' => false,
                    'leaf' => true
                );
            }
        }
        foreach($group->children as $child){
            $ret[] = array(
                'id' => $child->id,
                'text' => $child->name,
                'children' => $this->_getChildren($child, $templates),
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
            for($i=0,$l=count($tmp)-1; $i<=$l; $i++){
                foreach($tmp[$i] as $k=>$name){
                    if (isset($tmp[$i+1]))
                        $tmp_index[$k]->children = $this->_getChildrenByLevel($tmp_index, $k, $tmp[$i+1]);
                    else
                        $tmp_index[$k]->children = array();
                    if ($i == 0)
                        $data[$k] = $tmp_index[$k];
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

    /**
     * Кодируем переданный путь по хитрому алгоритму. Для передачи в site/download.
     * @param string $path
     * @return string
     */
    private function decodePath($path)
    {
        if (empty($path))
            return '';
        return strtr(base64_encode(addslashes(gzcompress(serialize($path),9))), '+/=', '-_,');
    }
}