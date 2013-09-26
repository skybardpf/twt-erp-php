<?php
/**
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var Contract_typeController $this
 * @var ContractType $model
 */
?>
    <h2><?= CHtml::encode($model->name); ?></h2>
<?php
    if (!$model->is_standart){
        $this->widget(
            'bootstrap.widgets.TbButton',
            array(
                'buttonType'=>'link',
                'type'=>'success',
                'label'=>'Редактировать',
                'url' => $this->createUrl("edit", array('id' => $model->primaryKey))
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
                        'data-question'     => 'Вы уверены, что хотите удалить вид договора?',
                        'data-title'        => 'Удаление вида договора',
                        'data-url'          => $this->createUrl('delete', array('id' => $model->primaryKey)),
                        'data-redirect_url' => $this->createUrl('index', array()),
                        'data-delete_item_element' => '1'
                    )
                )
            );
        }
        echo '<br/><br/>';
    }
?>
<div>
<?php
    $attributes = array_merge(
        array(
            'name',
        ),
        $model->listAttributes()
    );

    $this->widget('bootstrap.widgets.TbDetailView', array(
        'data' => $model,
        'attributes'=> $attributes
    ));
?>
</div>
