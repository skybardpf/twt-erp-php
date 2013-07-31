<?php
/**
 * Вывод списка контрагентов.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var ContractorController    $this
 * @var Contractor[]            $data
 * @var ContractorGroup[]       $groups
 */
?>
<div class="pull-right" style="margin-top: 15px;">
<?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => 'Группы',
        'type'  => 'success',
        'size'  => 'normal',
        'url'   => $this->createUrl("contractor_group/")
    ));
    echo '&nbsp;';
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => 'Новый контрагент',
        'type'  => 'success',
        'size'  => 'normal',
        'url'   => $this->createUrl("add")
    ));
?>
</div>
<h2>Контрагенты</h2>

<?php
    $countries = Countries::getValues();

    /**
     * @var $provider CArrayDataProvider
     */
    $provider = new CArrayDataProvider($data);
    foreach($provider->rawData as $k=>$m){
        $provider->rawData[$k]['country'] = (isset($countries[$m['country']]) ? $countries[$m['country']] : '');
//        $provider->rawData[$k]['parent'] = (isset($persons[$m['parent']]) ? $persons[$m['parent']] : '');
    }

    $tree_children = array();
    foreach($groups as $g){
        $tree_children[] = array(
            'text' => $g->name,
            'expanded' => false,
            'children' => getChildren($g)
        );

//        $tree_children
//        $tree_children = array(
//            'name' => $g->name,
//
//        );
    }

    function getChildren(ContractorGroup $elem){
        $children = array();
        foreach($elem->children as $v){
            $children[] = array(
                'text' => $v->name,
                'expanded' => false,
                'children' => getChildren($v),
            );
        }
        return $children;
    }

    $tree_data = array(
        array(
            'text'     => 'Все контаргенты',
            'expanded' => false,
            'children' => $tree_children
        ),
    );


    $this->widget('CTreeView', array('data' => $tree_data));

//    $this->widget('bootstrap.widgets.TbGridView', array(
//        'type' => 'striped bordered condensed',
//        'dataProvider' => $provider,
//        'template' => "{items} {pager}",
//        'columns' => array(
//            array(
//                'name' => 'name',
//                'header' => 'Название',
//                'type' => 'raw',
//                'value' => 'CHtml::link($data["name"], Yii::app()->getController()->createUrl("contractor/view", array("id" => $data["id"])))'
//            ),
//            array(
//                'name' => 'country',
//                'header' => 'Страна юрисдикции',
//            ),
//            array(
//                'name' => 'creation_date',
//                'header' => 'Дата добавления',
//            ),
//            array(
//                'name' => 'creator',
//                'header' => 'Пользователь, добавивший в систему',
//            ),
//        ),
//    ));
?>