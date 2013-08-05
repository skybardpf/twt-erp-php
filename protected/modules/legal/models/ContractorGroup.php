<?php
/**
 * Управление группами контрагентов.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @property string     $id
 * @property string     $name
 * @property integer    $level
 * @property integer    $group_id       // $parent_id
 */
class ContractorGroup extends SOAPModel
{
    const GROUP_ID_UNCATEGORIZED = '--uncategorized--';
    const GROUP_DEFAULT = '-- DEFAULT --';

    const PREFIX_CACHE_ID_LIST_DATA = '_list_data_';
    const PREFIX_CACHE_ID_LIST_DROPDOWN_DATA = '_list_dropdown_data_';
    const PREFIX_CACHE_ID_LIST_ROOT_DATA = '_list_root_data_';
    const PREFIX_CACHE_ID_LIST_DATA_INHERITED_GROUP_ID = '_list_data_inherited_group_id_';

    /**
     * @var ContractorGroup[] $children
     */
    public $children = array();

    /**
     * @static
     * @param string $className
     * @return ContractorGroup
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * Список групп контрагентов.
     * @return ContractorGroup[]
     */
    public function findAll()
    {
        $filters = SoapComponent::getStructureElement($this->where);
        if (!$filters) {
            $filters = array(array());
        }
        $request = array('filters' => $filters, 'sort' => array($this->order));
        $ret = $this->SOAP->listContractorGroups($request);
        $ret = SoapComponent::parseReturn($ret);
        return $this->publish_list($ret, __CLASS__);
    }

    /**
     * @param string $id
     * @return ContractorGroup Возвращает группу контрагента.
     */
    public function findByPk($id)
    {
        $ret = $this->SOAP->getContractorGroup(array('id' => $id));
        $ret = SoapComponent::parseReturn($ret);
        return $this->publish_elem(current($ret), __CLASS__);
    }

    /**
     * @return bool
     */
    public function delete()
    {
        if ($pk = $this->getprimaryKey()) {
            $ret = $this->SOAP->deleteContractorGroup(array('id' => $pk));
            $ret = SoapComponent::parseReturn($ret, false);
            if ($ret){
                $this->clearCache();
            }
            return $ret;
        }
        return false;
    }

    /**
     * Сбрасываем кеш.
     */
    public function clearCache()
    {
        $class = get_class($this);
        if ($this->primaryKey){
            // TODO Надо как-то сбрасывать все кеши связанные с этой группой.
            Yii::app()->cache->delete($class.self::PREFIX_CACHE_ID_LIST_DATA_INHERITED_GROUP_ID.$this->primaryKey);
        }
        Yii::app()->cache->delete($class.self::PREFIX_CACHE_ID_LIST_ROOT_DATA);
        Yii::app()->cache->delete($class.self::PREFIX_CACHE_ID_LIST_DROPDOWN_DATA);
        Yii::app()->cache->delete($class.self::PREFIX_CACHE_ID_LIST_DATA);
//        Yii::app()->cache->delete($class.self::PREFIX_CACHE_ID_LIST_DATA_INHERITED_GROUP_ID);
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
            'parent_id' => 'Родительская группа',
            'level' => '',
            'country' => ''
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
     * Возращает список всех групп контрагентов, в порядке их наследования.
     * @param string $group_id
     * @param bool $force_cache Если TRUE, кеш сбрасывается принудительно.
     * @return array Формат [id => name].
     */
    public function getInheritedGroupsData($group_id, $force_cache = false)
    {
        $cache_id = get_class($this) . self::PREFIX_CACHE_ID_LIST_DATA_INHERITED_GROUP_ID . $group_id;
        $data = Yii::app()->cache->get($cache_id);
        if ($force_cache || $data === false) {
            $groups = $this->getData($force_cache);
            $gg = $this->_getParentGroups($groups, $group_id);
            $data = array();
            foreach($gg as $g){
                $data[$g->id] = $g->name;
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * @param $groups
     * @param $child_id
     * @return array
     */
    private function _getParentGroups($groups, $child_id){
        $ret = array();
        if (isset($groups[$child_id])){
            $ret[] = $groups[$child_id];
            if (!empty($groups[$child_id]->parent_id)){
                $ret = array_merge($ret, $this->_getParentGroups($groups, $groups[$child_id]->parent_id));
            }

        }
        return $ret;
    }

    /**
     * Возращает список всех групп контрагентов.
     * @param bool $force_cache Если TRUE, кеш сбрасывается принудительно.
     * @return ContractorGroup[] Формат [id => {ContractorGroup}].
     */
    public function getData($force_cache = false)
    {
        $cache_id = get_class($this) . self::PREFIX_CACHE_ID_LIST_DATA;
        $data = Yii::app()->cache->get($cache_id);
        if ($force_cache || $data === false) {
            $elements = $this->where('deleted', false)->findAll();
            $data = array();
            if ($elements) {
                foreach ($elements as $elem) {
                    $data[$elem->primaryKey] = $elem;
                }
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * Возращает список всех групп контрагентов для вывода в выпадающем списке.
     * @param bool $force_cache Если TRUE, кеш сбрасывается принудительно.
     * @return array Формат [id => name].
     */
    public function getDropDownData($force_cache = false)
    {
        $cache_id = get_class($this) . self::PREFIX_CACHE_ID_LIST_DROPDOWN_DATA;
        $data = Yii::app()->cache->get($cache_id);
        if ($force_cache || $data === false) {
            $data = array();
            $groups = $this->_getRootData($force_cache);
            $data[self::GROUP_DEFAULT] = '--- Выберите группу ---';
            foreach($groups as $parent) {
                $data[$parent->id] = $parent->name;
                $data = array_merge($this->_subDropDown($data, $parent));
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * @param array $data
     * @param ContractorGroup $group
     * @param string $space
     * @return array
     */
    private function _subDropDown($data, $group, $space = '--- ')
    {
        foreach($group->children as $child)
        {
            $data[$child->id] = $space.$child->name;
            $data = array_merge($this->_subDropDown($data, $child, $space.'--- '));
        }
        return $data;
    }

    /**
     * Список групп контрагентов. Сгрупировано по корневым группам.
     * @param bool $force_cache
     * @return ContractorGroup[] Формат [id => {ContractorGroup}].
     */
    private function _getRootData($force_cache = false)
    {
        $cache_id = get_class($this) . self::PREFIX_CACHE_ID_LIST_ROOT_DATA;
        $data = Yii::app()->cache->get($cache_id);
        if ($force_cache || $data === false) {
            $elements = $this->getData($force_cache);

            $res = array();
            $tmp = array();
            foreach ($elements as $elem) {
                $res[$elem->primaryKey] = $elem;
                if (!empty($elem->parent_id)){
                    $group_id = $elem->parent_id;
                } else {
                    if ($elem->level == 0){
                        if (!isset($tmp[$elem->id])){
                            $tmp[$elem->id] = array();
                        }
                        continue;
                    }
                    $group_id = self::GROUP_ID_UNCATEGORIZED;
                }
                if(isset($tmp[$group_id])){
                    $tmp[$group_id][] = $elem->primaryKey;
                } else {
                    $tmp[$group_id] = array($elem->primaryKey);
                }
            }
            $data = array();
            foreach ($tmp as $k=>$v) {
                if ($k == self::GROUP_ID_UNCATEGORIZED){
                    $group = new ContractorGroup();
                    $group->id = self::GROUP_ID_UNCATEGORIZED;
                    $group->name = 'Без категории';
                    $group->level = 0;
                    $res[$k] = $group;
                }
                foreach($v as $c){
                    $res[$k]->children[] = $res[$c];
                }
                $data[$k] = $res[$k];
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * @param array $data
     * @param bool $force_cache
     * @return array
     */
    public function getTreeData(array $data, $force_cache = false)
    {
        $groups = ContractorGroup::model()->_getRootData($force_cache);
        $ret = array();
        foreach($groups as $group){
            $ret[] = array(
                'text' => $group->name,
                'children' => $this->_getChildren($group, $data),
                'leaf' => (empty($group->children))
            );
        }
        return $ret;
    }

    /**
     * @param bool $force_cache
     * @return array
     */
    public function getTreeOnlyGroup($force_cache = false)
    {
        $groups = ContractorGroup::model()->_getRootData($force_cache);
        $ret = array();
        foreach($groups as $group){
            $ret[] = array(
                'id' => $group->id,
                'text' => $group->name,
                'children' => $this->_getChildren($group, $data=array()),
                'leaf' => (empty($group->children))
            );
        }
        return $ret;
    }

    /**
     * @param ContractorGroup $group
     * @param array $contractors
     * @return array
     */
    private function _getChildren($group, array $contractors)
    {
        $ret = array();
        foreach($group->children as $child){
            if (isset($contractors[$child->primaryKey])){
                $ret[] = array(
                    'text' => $child->name,
                    'children' => array(
                        'text' => 'Список контрагентов' .
                        Yii::app()->controller->widget(
                            'bootstrap.widgets.TbGridView',
                            array(
                                'type' => 'striped bordered condensed',
                                'dataProvider' => new CArrayDataProvider(
                                    $contractors[$child->primaryKey],
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
                    ),
                    'leaf' => false
                );
            }

            $ret[] = array(
                'id' => $child->id,
                'text' => $child->name,
                'children' => $this->_getChildren($child, $contractors),
                'leaf' => (empty($child->children))
            );
        }
        return $ret;
    }
}