<?php
/**
 * Форма редактирования договора.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @var ContractController  $this
 * @var Contract            $model
 * @var Organizations       $organization
 */
?>

<?php
    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/contract/form.js');
    Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/jquery.json-2.4.min.js');

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
    $jui_date_options = array(
        'language' => 'ru',
        'options'=>array(
            'showAnim' => 'fold',
            'dateFormat' => 'yy-mm-dd',
            'changeMonth' => true,
            'changeYear' => true,
            'showOn' => 'button',
            'constrainInput' => 'true',


        ),
        'htmlOptions'=>array(
            'style' => 'height:20px;'
        )
    );

    echo $form->dropDownListRow($model, 'typ_doc', Contract::getTypes());
    echo $form->dropDownListRow($model, 'le_id', Contractor::getValues());
    echo $form->textFieldRow($model, 'name');
    echo $form->textFieldRow($model, 'number');
?>
    <div class="control-group">
        <?= $form->labelEx($model, 'date', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
                $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
                    array(
                        'model' => $model,
                        'attribute' => 'date'
                    ), $jui_date_options
                ));
                echo $form->error($model, 'date');
            ?>
        </div>
    </div>

    <div class="control-group">
        <?= $form->labelEx($model, 'expire', array('class' => 'control-label')); ?>
        <div class="controls">
        <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker',array_merge(
                array(
                    'model'     => $model,
                    'attribute' => 'expire'
                ), $jui_date_options
            ));
            echo $form->error($model, 'expire');
        ?>
        </div>
    </div>

<?php
    $model->invalid = $model->invalid ? 1 : 0;
    echo $form->radioButtonListInlineRow($model, 'invalid', array(
        1 => 'Действителен',
        0 => 'Не действителен',
    ));
    echo $form->textFieldRow($model, 'place_contract');
    echo $form->dropDownListRow($model, 'prolongation_type', Contract::getProlongationTypes());
?>
    <div class="control-group">
        <?= $form->labelEx($model, 'date_infomation', array('class' => 'control-label')); ?>
        <div class="controls">
        <?php
            echo $form->textField($model, 'date_infomation', array('style'=> 'width: 70%')).' дней';
        ?>
        </div>
    </div>
<?php

    echo $form->dropDownListRow($model, 'currency', Currencies::getValues());
    echo $form->textFieldRow($model, 'dogovor_summ');
    echo $form->textFieldRow($model, 'everymonth_summ');
    echo $form->dropDownListRow($model, 'responsible', Individuals::getValues());

    $contractors = Contractor::getValues();
    $contractor = (isset($contractors[$model->le_id]) ? $contractors[$model->le_id] : '---');
?>
    <div class="control-group">
        <?= CHtml::label('Роль '.CHtml::encode($organization->name).': <span class="required">*</span>', get_class($model).'[role_ur_face]', array('class' => 'control-label')); ?>
        <div class="controls">
        <?php
            echo CHtml::activeDropDownList($model, 'role_ur_face', Contract::getRoles());
        ?>
        </div>
    </div>

<?php
    /**
     * @var Individuals $persons
     */
    $persons = Individuals::getValues();
    /**
     * Генерируем таблицу для отображения подписантов организации
     */
    $data = array();
    $class_button = 'add-signatory ' . ((count($model->signatory) >= 2) ? 'hide' : '');
    foreach ($model->signatory as $id){
        $data[] = array(
            'id' => $id,
            'name' => (isset($persons[$id])
                ? CHtml::link($persons[$id], $this->createUrl('individuals/view', array('id' => $id)))
                : '---'
            ),
            'delete' => $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'button',
                'type' => 'primary',
                'label' => 'Удалить',
                'htmlOptions' => array(
                    'class' => 'del-signatory',
                    'data-id' => $id,
                    'data-type' => 'signatory'
                )
            ), true)
        );
    }
    $div_signatory = $this->widget('bootstrap.widgets.TbGridView',
        array(
            'id' => 'grid-signatory',
            'type' => 'striped bordered condensed',
            'dataProvider' => new CArrayDataProvider($data),
            'template' => "{items}",
            'columns' => array(
                array(
                    'name' => 'name',
                    'header' => 'Подписант',
                    'type' => 'raw',
                    'htmlOptions' => array(
                        'style' => 'width: 90%',
                    )
                ),
                array(
                    'name' => 'delete',
                    'header' => '',
                    'type' => 'raw'
                ),
            )
        ),
        true
    );
    $div_signatory .= $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=> 'button',
        'type' => 'primary',
        'label' => 'Добавить',
        'htmlOptions' => array(
            'class' => $class_button,
            'data-type' => 'signatory',
        )
    ), true);

    /**
     * Генерируем таблицу для отображения подписантов контрагента
     */
    $data = array();
    $class_button = 'add-signatory-contractor ' . ((count($model->signatory_contr) >= 2) ? 'hide' : '');
    foreach ($model->signatory_contr as $id){
        $data[] = array(
            'id' => $id,
            'name' => (isset($persons[$id])
                ? CHtml::link($persons[$id], $this->createUrl('individuals/view', array('id' => $id)))
                : '---'
            ),
            'delete' => $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'button',
                'type' => 'primary',
                'label' => 'Удалить',
                'htmlOptions' => array(
                    'class' => 'del-signatory',
                    'data-id' => $id,
                    'data-type' => 'signatory_contractor'
                )
            ), true)
        );
    }
    $div_signatory_contractor = $this->widget('bootstrap.widgets.TbGridView',
        array(
            'id' => 'grid-signatory-contractor',
            'type' => 'striped bordered condensed',
            'dataProvider' => new CArrayDataProvider($data),
            'template' => "{items}",
            'columns' => array(
                array(
                    'name' => 'name',
                    'header' => 'Подписант',
                    'type' => 'raw',
                    'htmlOptions' => array(
                        'style' => 'width: 90%',
                    )
                ),
                array(
                    'name' => 'delete',
                    'header' => '',
                    'type' => 'raw'
                ),
            )
        ),
        true
    );
    $div_signatory_contractor .= $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType'=> 'button',
        'type' => 'primary',
        'label' => 'Добавить',
        'htmlOptions' => array(
            'class' => $class_button,
            'data-type' => 'signatory_contractor',
        )
    ), true);

    echo $form->hiddenField($model, 'json_signatory');
    echo $form->hiddenField($model, 'json_signatory_contractor');
?>
    <div class="control-group">
        <?= CHtml::label('Подписанты '.CHtml::encode($organization->name), get_class($model).'[signatory]', array('class' => 'control-label')); ?>
        <div class="controls">
        <?php
            echo $div_signatory;
        ?>
        </div>
    </div>
    <div class="control-group">
        <?= CHtml::label('Подписанты '.CHtml::encode($contractor), get_class($model).'[signatory_contr]', array('class' => 'control-label')); ?>
        <div class="controls">
        <?php
            echo $div_signatory_contractor;
        ?>
        </div>
    </div>

<?php
    echo $form->textFieldRow($model, 'place_court');
    echo $form->textAreaRow($model, 'comment');
?>
    <div class="control-group">
        <?= $form->labelEx($model, 'scan', array('class' => 'control-label')); ?>
        <div class="controls">
        </div>
    </div>
    <div class="control-group">
        <?= $form->labelEx($model, 'orig_doc', array('class' => 'control-label')); ?>
        <div class="controls">
        </div>
    </div>
</fieldset>

<?php $this->endWidget(); ?>

<?php
    // Модальное окошко для подписанта
    $this->beginWidget('bootstrap.widgets.TbModal', array('id'=>'dataModalSignatory'));
?>
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h4><?=Yii::t("menu", "Выберите подписанта")?></h4>
    </div>
    <div class="modal-body"></div>
    <div class="modal-footer">
        <?php
        $this->widget('bootstrap.widgets.TbButton', array(
            'label' => Yii::t("menu", "Сохранить"),
            'url'   => '#',
            'htmlOptions' => array('class'=>'button_save', 'data-dismiss'=>'modal'),
        ));

        $this->widget('bootstrap.widgets.TbButton', array(
            'label' => Yii::t("menu", "Отмена"),
            'url'   => '#',
            'htmlOptions' => array('data-dismiss'=>'modal'),
        ));
        ?>
    </div>
<?php $this->endWidget(); ?>