<?php
/**
 * Форма редактирования договора.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var ContractController  $this
 * @var Contract            $model
 * @var Organization       $organization
 */
?>

<?php
    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/jquery.json-2.4.min.js');
    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/contract/form.js');

    echo '<h2>'.($model->primaryKey ? 'Редактирование' : 'Создание').' договора</h2>';

    /**
     * @var MTbActiveForm $form
     */
    $form = $this->beginWidget('bootstrap.widgets.MTbActiveForm', array(
        'id' => 'form-contract',
        'type' => 'horizontal',
        'enableAjaxValidation' => true,
        'enableClientValidation'=>true,
        'clientOptions' => array(
            'validateOnSubmit' => true,
            'validateOnChange' => true,
        ),
    ));

    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=> 'submit',
        'type'      => 'primary',
        'label'     => 'Сохранить'
    ));
    echo '&nbsp;';
    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'link',
        'label'      => 'Отмена',
        'url' => $model->primaryKey
            ? $this->createUrl('view', array('id' => $model->primaryKey))
            : $this->createUrl('list', array('org_id' => $organization->primaryKey)))
    );

    if ($model->hasErrors()) {
        echo '<br/><br/>'. $form->errorSummary($model);
    }
?>

<fieldset>
<?php
    // Опции для JUI селектора даты
//    $jui_date_options = array(
//        'language' => 'ru',
//        'options'=>array(
//            'showAnim' => 'fold',
//            'dateFormat' => 'yy-mm-dd',
//            'changeMonth' => true,
//            'changeYear' => true,
//            'showOn' => 'button',
//            'constrainInput' => 'true',
//        ),
//        'htmlOptions'=>array(
//            'style' => 'height:20px;'
//        )
//    );
//
//    echo $form->dropDownListRow($model, 'contract_type_id', Contract::getTypes());
//    echo $form->dropDownListRow($model, 'contractor_id', Contractor::model()->getListNames());
//    echo $form->textFieldRow($model, 'name');
//    echo $form->textFieldRow($model, 'number');
//?>
<!--    <div class="control-group">-->
<!--        --><?//= $form->labelEx($model, 'date', array('class' => 'control-label')); ?>
<!--        <div class="controls">-->
<!--            --><?php
//                $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
//                    array(
//                        'model' => $model,
//                        'attribute' => 'date'
//                    ), $jui_date_options
//                ));
//                echo $form->error($model, 'date');
//            ?>
<!--        </div>-->
<!--    </div>-->
<!---->
<!--    <div class="control-group">-->
<!--        --><?//= $form->labelEx($model, 'date_expire', array('class' => 'control-label')); ?>
<!--        <div class="controls">-->
<!--        --><?php
//            $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
//                array(
//                    'model'     => $model,
//                    'attribute' => 'date_expire'
//                ), $jui_date_options
//            ));
//            echo $form->error($model, 'date_expire');
//        ?>
<!--        </div>-->
<!--    </div>-->
<!---->
<?php
//    echo $form->dropDownListRow($model, 'place_contract_id', ContractPlace::model()->listNames($this->getForceCached()));
//    echo $form->dropDownListRow($model, 'prolongation_type', Contract::getProlongationTypes());
//    echo $form->dropDownListRow($model, 'currency', Currency::model()->listNames($this->getForceCached()));
//    echo $form->textFieldRow($model, 'sum');
//    echo $form->textFieldRow($model, 'sum_month');
//    echo $form->dropDownListRow($model, 'responsible', Individual::model()->listNames($this->getForceCached()));
//
//    $contractors = Contractor::model()->getListNames();
//    $contractor = (isset($contractors[$model->contractor_id]) ? $contractors[$model->contractor_id] : '---');
//?>
<!--    <div class="control-group">-->
<!--        --><?//= CHtml::label('Роль '.CHtml::encode($organization->name).' <span class="required">*</span>', get_class($model).'[role_ur_face]', array('class' => 'control-label')); ?>
<!--        <div class="controls">-->
<!--        --><?php
//            echo CHtml::activeDropDownList($model, 'role', Contract::getRoles());
//        ?>
<!--        </div>-->
<!--    </div>-->
<!---->
<?php
//    /**
//     * @var Individual $persons
//     */
//    $persons = Individual::model()->listNames($this->getForceCached());
//    /**
//     * Генерируем таблицу для отображения подписантов организации
//     */
//    $data = array();
//    $class_button = 'add-signatory ' . ((count($model->organization_signatories) >= 2) ? 'hide' : '');
//    foreach ($model->organization_signatories as $id){
//        $data[] = array(
//            'id' => $id,
//            'name' => (isset($persons[$id])
//                ? CHtml::link($persons[$id], $this->createUrl('individual/view', array('id' => $id)))
//                : '---'
//            ),
//            'delete' => $this->widget('bootstrap.widgets.TbButton', array(
//                'buttonType' => 'button',
//                'type' => 'primary',
//                'label' => 'Удалить',
//                'htmlOptions' => array(
//                    'class' => 'del-signatory',
//                    'data-id' => $id,
//                    'data-type' => 'organization_signatories'
//                )
//            ), true)
//        );
//    }
//    $div_signatory = $this->widget('bootstrap.widgets.TbGridView',
//        array(
//            'id' => get_class($model).'_signatory',
//            'type' => 'striped bordered condensed',
//            'dataProvider' => new CArrayDataProvider($data),
//            'template' => "{items}",
//            'columns' => array(
//                array(
//                    'name' => 'name',
//                    'header' => 'Подписант',
//                    'type' => 'raw',
//                    'htmlOptions' => array(
//                        'style' => 'width: 90%',
//                    )
//                ),
//                array(
//                    'name' => 'delete',
//                    'header' => '',
//                    'type' => 'raw'
//                ),
//            )
//        ),
//        true
//    );
//    $div_signatory .= $this->widget('bootstrap.widgets.TbButton', array(
//        'buttonType'=> 'button',
//        'type' => 'primary',
//        'label' => 'Добавить',
//        'htmlOptions' => array(
//            'class' => $class_button,
//            'data-type' => 'organization_signatories',
//        )
//    ), true);
//
//    /**
//     * Генерируем таблицу для отображения подписантов контрагента
//     */
//    $data = array();
//    $class_button = 'add-signatory-contractor ' . ((count($model->contractor_signatories) >= 2) ? 'hide' : '');
//    foreach ($model->contractor_signatories as $id){
//        $data[] = array(
//            'id' => $id,
//            'name' => (isset($persons[$id])
//                ? CHtml::link($persons[$id], $this->createUrl('Individual/view', array('id' => $id)))
//                : '---'
//            ),
//            'delete' => $this->widget('bootstrap.widgets.TbButton', array(
//                'buttonType' => 'button',
//                'type' => 'primary',
//                'label' => 'Удалить',
//                'htmlOptions' => array(
//                    'class' => 'del-signatory',
//                    'data-id' => $id,
//                    'data-type' => 'signatory_contractor'
//                )
//            ), true)
//        );
//    }
//    $div_signatory_contractor = $this->widget('bootstrap.widgets.TbGridView',
//        array(
//            'id' => get_class($model).'_signatory_contr',
//            'type' => 'striped bordered condensed',
//            'dataProvider' => new CArrayDataProvider($data),
//            'template' => "{items}",
//            'columns' => array(
//                array(
//                    'name' => 'name',
//                    'header' => 'Подписант',
//                    'type' => 'raw',
//                    'htmlOptions' => array(
//                        'style' => 'width: 90%',
//                    )
//                ),
//                array(
//                    'name' => 'delete',
//                    'header' => '',
//                    'type' => 'raw'
//                ),
//            )
//        ),
//        true
//    );
//    $div_signatory_contractor .= $this->widget('bootstrap.widgets.TbButton', array(
//        'buttonType'=> 'button',
//        'type' => 'primary',
//        'label' => 'Добавить',
//        'htmlOptions' => array(
//            'class' => $class_button,
//            'data-type' => 'signatory_contractor',
//        )
//    ), true);
//
//    echo $form->hiddenField($model, 'json_organization_signatories');
//    echo $form->hiddenField($model, 'json_contractor_signatories');
//?>
<!--    <div class="control-group">-->
<!--        --><?//= CHtml::label('Подписанты ('.CHtml::encode($organization->name).') <span class="required">*</span>', get_class($model).'_signatory', array('class' => 'control-label')); ?>
<!--        <div class="controls">-->
<!--        --><?php
//            echo $div_signatory;
//            echo CHtml::tag('div', array(), $form->error($model, 'organization_signatories'));
//        ?>
<!--        </div>-->
<!--    </div>-->
<!--    <div class="control-group">-->
<!--        --><?//= CHtml::label('Подписанты ('.CHtml::encode($contractor).') <span class="required">*</span>', get_class($model).'_signatory_contr', array('class' => 'control-label')); ?>
<!--        <div class="controls">-->
<!--        --><?php
//            echo $div_signatory_contractor;
//            echo CHtml::tag('div', array(), $form->error($model, 'contractor_signatories'));
//        ?>
<!--        </div>-->
<!--    </div>-->
<!---->
<?php
//    echo $form->dropDownListRow($model, 'place_court_id', CourtLocation::model()->listNames($this->getForceCached()));
//    echo $form->textAreaRow($model, 'comment');
//
//    $data_scans = array();
//    $data_doc = array();
//?>
<!--    <div class="control-group">-->
<!--        --><?//= $form->labelEx($model, 'scan', array('class' => 'control-label')); ?>
<!--        <div class="controls">-->
<!--        --><?php
//            $this->widget('bootstrap.widgets.TbGridView',
//                array(
//                    'type' => 'striped bordered condensed',
//                    'dataProvider' => new CArrayDataProvider($data_scans),
//                    'template' => "{items}",
//                    'columns' => array(
//                        array(
//                            'name' => 'name',
//                            'header' => 'Название файла',
//                            'type' => 'raw',
//                            'htmlOptions' => array(
//                                'style' => 'width: 90%',
//                            )
//                        ),
//                        array(
//                            'name' => 'delete',
//                            'header' => '',
//                            'type' => 'raw'
//                        ),
//                    )
//                )
//            );
//        ?>
<!--        </div>-->
<!--    </div>-->
<!--    <div class="control-group">-->
<!--        --><?//= $form->labelEx($model, 'orig_doc', array('class' => 'control-label')); ?>
<!--        <div class="controls">-->
<!--        --><?php
//            $this->widget('bootstrap.widgets.TbGridView',
//                array(
//                    'type' => 'striped bordered condensed',
//                    'dataProvider' => new CArrayDataProvider($data_doc),
//                    'template' => "{items}",
//                    'columns' => array(
//                        array(
//                            'name' => 'name',
//                            'header' => 'Название файла',
//                            'type' => 'raw',
//                            'htmlOptions' => array(
//                                'style' => 'width: 90%',
//                            )
//                        ),
//                        array(
//                            'name' => 'delete',
//                            'header' => '',
//                            'type' => 'raw'
//                        ),
//                    )
//                )
//            );
//        ?>
<!--        </div>-->
<!--    </div>-->
<!--</fieldset>-->
<!---->
<?php //$this->endWidget(); ?>
<!---->
<?php
//    /**
//     * Модальное окошко для подписанта
//     */
//    $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'dataModalSignatory'));
//?>
<!--    <div class="modal-header">-->
<!--        <a class="close" data-dismiss="modal">×</a>-->
<!--        <h4>--><?//=Yii::t("menu", "Выберите подписанта")?><!--</h4>-->
<!--    </div>-->
<!--    <div class="modal-body"></div>-->
<!--    <div class="modal-footer">-->
<!--        --><?php
//        $this->widget('bootstrap.widgets.TbButton', array(
//            'label' => Yii::t("menu", "Сохранить"),
//            'url'   => '#',
//            'htmlOptions' => array('class'=>'button_save', 'data-dismiss'=>'modal'),
//        ));
//
//        $this->widget('bootstrap.widgets.TbButton', array(
//            'label' => Yii::t("menu", "Отмена"),
//            'url'   => '#',
//            'htmlOptions' => array('data-dismiss'=>'modal'),
//        ));
//        ?>
<!--    </div>-->
<?php $this->endWidget(); ?>