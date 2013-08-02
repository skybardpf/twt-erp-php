<?php
/**
 * Управление группами контрагентов.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @property string     $id
 * @property string     $name
 * @property integer    $level
 */
class ContractorGroup extends SOAPModel {
    const GROUP_ID_UNCATEGORIZED = '--uncategorized--';

    public $children = array();

    /**
     * @static
     * @param string $className
     * @return ContractorGroup
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Список групп контрагентов.
     * @return ContractorGroup[]
     */
    public function findAll() {
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
    public function attributeLabels() {
        return array(
            'id' => '#',
            'name' => 'Название',
            'group_id' => '',
            'level' => '',
            'country' => ''
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('name', 'required'),
        );
    }

    /**
     * Список групп контрагентов. Формат [id => {ContractorGroup}].
     * Результат сохранеятся в кеш.
     * @return array
     */
    public function getData() {
        $cache_id = get_class($this). '_list_data';
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false) {
            $elements = $this->where('deleted', false)->findAll();

            $res = array();
            $tmp = array();
            if ($elements) {
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
        $groups = ContractorGroup::model()->getData();
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
        $groups = ContractorGroup::model()->getData();
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

//    public function getListed() {
//        $subitems = array();
//        if($this->childs) foreach($this->childs as $child) {
//            $subitems[] = $child->getListed();
//        }
//        $returnarray = array('label' => $this->title, 'url' => array('Hierarchy/view', 'id' => $this->id));
//        if($subitems != array())
//            $returnarray = array_merge($returnarray, array('items' => $subitems));
//        return $returnarray;
//    }

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