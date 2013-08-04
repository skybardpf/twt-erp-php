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
        $ret = $this->SOAP->listGroupEntities($request);
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
            'group_id' => '', // parent_id
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
     * Возращает список всех групп контрагентов.
     * @param bool $force_cache Если TRUE, кеш сбрасывается принудительно.
     * @return ContractorGroup[] Формат [id => {ContractorGroup}].
     */
    public function getData($force_cache = false)
    {
        $cache_id = get_class($this) . self::PREFIX_CACHE_ID_LIST_DATA;
//        Yii::app()->cache->delete($cache_id);
        $data = Yii::app()->cache->get($cache_id);
        if ($force_cache || $data === false) {
//            $elements = $this->where('deleted', false)->findAll();
            $elements = array(
                array('id' => '001', 'name' => 'Name 001', 'parent_id' => ''),
                array('id' => '002', 'name' => 'Name 002', 'parent_id' => ''),
                array('id' => '003', 'name' => 'Name 003', 'parent_id' => ''),
                array('id' => '004', 'name' => 'Name 004', 'parent_id' => '001'),
                array('id' => '005', 'name' => 'Name 005', 'parent_id' => '001'),
                array('id' => '006', 'name' => 'Name 006', 'parent_id' => '001'),
                array('id' => '007', 'name' => 'Name 007', 'parent_id' => '002'),
                array('id' => '008', 'name' => 'Name 008', 'parent_id' => '002'),
                array('id' => '009', 'name' => 'Name 009', 'parent_id' => '002'),
            );
            $data = array();
            if ($elements) {
                foreach ($elements as $elem) {
                    $g = new ContractorGroup();
                    $g->id = $elem['id'];
                    $g->name = $elem['name'];
                    $g->group_id = $elem['parent_id'];
                    $data[$g->id] = $g;
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
            $groups = $this->_getRootData($force_cache);
            $data[self::GROUP_DEFAULT] = 'Выберите группу';
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
     * @return ContractorGroup[] Формат [id => {ContractorGroup}].
     */
    private function _getRootData()
    {
        $cache_id = get_class($this) . self::PREFIX_CACHE_ID_LIST_ROOT_DATA;
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false) {
            $elements = $this->getData();

            $res = array();
            $tmp = array();
            foreach ($elements as $elem) {
                $res[$elem->primaryKey] = $elem;
                if (!empty($elem->group_id)){
                    $group_id = $elem->group_id;
                } else {
                    if ($elem->level == 0){
                        if (!isset($tmp[$elem->id])){
                            $tmp[$elem->id] = array();
                        }
                        continue;
                    }
                    $group_id = self::GROUP_ID_UNCATEGORIZED;
                }
//                    $group_id = (empty($elem->group_id) && $elem->level > 0) ? self::GROUP_ID_UNCATEGORIZED : $elem->group_id;
                if(isset($tmp[$group_id])){
                    $tmp[$group_id][] = $elem->primaryKey;
                } else {
                    $tmp[$group_id] = array($elem->primaryKey);
                }
            }
//            var_dump($tmp);die;
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
     * @return array
     */
    public function getTreeData(array $data)
    {
        $groups = ContractorGroup::model()->_getRootData();
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
     * @param bool $dropdown
     * @return array
     */
    public function getTreeOnlyGroup($dropdown = false)
    {
        $groups = ContractorGroup::model()->_getRootData();
        $ret = array();
        $label = ($dropdown) ? 'label' : 'text';
//        var_dump($dropdown);
//        var_dump($label);
        foreach($groups as $group){
            $ret[] = array(
                $label => $group->name,
                'children' => $this->_getChildren($group, $data=array(), $dropdown),
                'leaf' => (empty($group->children))
            );
        }
        return $ret;
    }

    /**
     * @param ContractorGroup $group
     * @param array $contractors
     * @param bool $dropdown
     * @return array
     */
    private function _getChildren($group, array $contractors, $dropdown=false)
    {
        $ret = array();
        $label = ($dropdown) ? 'label' : 'text';
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
                $label => $child->name,
                'children' => $this->_getChildren($child, $contractors, $dropdown),
                'leaf' => (empty($child->children))
            );
        }
        return $ret;
    }
}