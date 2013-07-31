<?php
/**
 * Управление группами контрагентов.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class ContractorGroup extends SOAPModel {
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
            'groups' => '',
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
            $menu = array();
            if ($elements) {
                foreach ($elements as $elem) {
                    $res[$elem->primaryKey] = $elem;

                    if (!empty($elem->groups)){
                        if(isset($menu[$elem->groups])){
                            $menu[$elem->groups][] = $elem->id;
                        } else {
                            $menu[$elem->groups] = array($elem->id);
                        }
                    }
                }
            }
            $data = array();
            foreach ($menu as $k=>$v) {
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
     *
     */
    private function _getChildren($data){

    }
}