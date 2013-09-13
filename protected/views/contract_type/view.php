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
    $this->widget('bootstrap.widgets.TbDetailView', array(
        'data' => $model,
        'attributes'=>array(
            'name',
            'contractor', // string
            'title', // string
            'number', // string
            'date', // string
            'date_expire', // string
            'contract_status', // string
            'place_of_contract', // string
            'type_of_prolongation', // string
            'notice_end_of_contract', // string
            'currency', // string
            'sum_contract', // string
            'sum_month', // string
            'responsible_contract', // string
            'role', // string
            'organization_signatories', // string
            'contractor_signatories', // string
            'third_parties_signatories', // string
            'place_of_court', // string
            'comment', // string
            'scans', // string
            'original_documents', // string
        )
    ));
?>
</div>
