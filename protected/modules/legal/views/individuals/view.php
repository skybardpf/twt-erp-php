<?php
/**
 * User: Forgon
 * Date: 10.06.2013 от Рождества Христова
 *
 * @var IndividualsController   $this
 * @var Individuals             $model
 */
?>
    <h2><?=$model->family?> <?=$model->name?> <?=$model->parent_name?></h2>
<?php
    $this->widget(
        'bootstrap.widgets.TbButton',
        array(
            'buttonType'=>'link',
            'type'=>'success',
            'label'=>'Редактировать',
            'url' => Yii::app()->getController()->createUrl("edit", array('id' => $model->id))
        )
    );
    echo "&nbsp;";
    if (!$model->deleted) {
        Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/delete_item.js');
        $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'buttonType'    => 'submit',
                'type'          => 'danger',
                'label'         => 'Удалить',
                'htmlOptions'   => array(
                    'data-question'     => 'Вы уверены, что хотите удалить данное лицо?',
                    'data-title'        => 'Удаление лица',
                    'data-url'          => $this->createUrl('delete', array('id' => $model->id)),
                    'data-redirect_url' => $this->createUrl('index', array()),
                    'data-delete_item_element' => '1'
                )
            )
        );
    }
?>
<br/><br/>
<div>
<?php
    $this->widget('bootstrap.widgets.TbDetailView', array(
        'data' => $model,
        'attributes'=>array(
            array(
                'label' => 'Гражданство',
                'type'  => 'raw',
                'value' => $model->citizenship ? $model->citizenship : '&mdash;',
            ),
            array('name' => 'birth_date',       'label' => 'Дата рождения'),
            array(
                'label' => 'Место рождения',
                'type'  => 'raw',
                'value' => $model->birth_place ? $model->birth_place : '&mdash;',
            ),

            array('name' => 'phone',            'label' => 'Контактные данные'),
            array('name' => 'email',            'label' => 'E-mail'),

            array('name' => 'adres',            'label' => 'Адрес прописки'),

            array('name' => 'ser_nom_pass',     'label' => 'Серия и номер удостоверения'),
            array('name' => 'date_pass',        'label' => 'Дата выдачи удостоверения'),
            array('name' => 'organ_pass',       'label' => 'Орган, выдавший удостоверение'),
            array('name' => 'date_exp_pass',    'label' => 'Срок действия удостоверения'),
        )
    ));
?>
</div>
