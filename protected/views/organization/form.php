<?php
/**
 *  Добавление новой организации и редактирование старой
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var OrganizationController $this
 * @var Organization $model
 * @var MTbActiveForm $form
 */
?>

<?php
Yii::app()->clientScript->registerScriptFile($this->asset_static . '/js/jquery.json-2.4.min.js');
Yii::app()->clientScript->registerScriptFile($this->asset_static . '/js/organization/form.js');

echo '<h2>' . ($model->primaryKey ? 'Редактирование ' : 'Создание ') . 'организации</h2>';

$form = $this->beginWidget('bootstrap.widgets.MTbActiveForm', array(
    'id' => 'form-organization',
    'type' => 'horizontal',
    'enableAjaxValidation' => true,
    'clientOptions' => array(
        'validateOnChange' => true,
    ),
));

$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'submit',
    'type' => 'primary',
    'label' => 'Сохранить'
));
echo '&nbsp;';
$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'link',
    'label' => 'Отмена',
    'url' => $model->primaryKey
        ? $this->createUrl('view', array('id' => $model->primaryKey))
        : $this->createUrl('index')
));
?>

<?php
if ($model->hasErrors()) {
    echo '<br/><br/>' . $form->errorSummary($model);
}
?>

<fieldset>
    <?php
    $jui_date_options = array(
        'language' => 'ru',
        'options' => array(
            'showAnim' => 'fold',
            'dateFormat' => 'yy-mm-dd',
            'changeMonth' => true,
            'changeYear' => true,
            'showOn' => 'button',
            'constrainInput' => 'true',
        ),
        'htmlOptions' => array(
            'style' => 'height:20px;'
        )
    );

    echo $form->dropDownListRow($model, 'country', Country::model()->listNames($model->getForceCached()), array('class' => 'list-countries'));
    echo $form->dropDownListRow($model, 'okopf', CodesOKOPF::model()->listNames($model->getForceCached()));
    echo $form->textFieldRow($model, 'name');
    echo $form->textFieldRow($model, 'full_name');
    ?>
    <div class="control-group">
        <?= $form->labelEx($model, 'sert_date', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array_merge(
                array(
                    'model' => $model,
                    'attribute' => 'sert_date'
                ), $jui_date_options
            ));
            echo $form->error($model, 'sert_date');
            ?>
        </div>
    </div>

    <!-- НАЧАЛО поля для российских фирм -->
    <div id="rus_fields">
        <?php
        echo $form->textFieldRow($model, 'inn');
        echo $form->textFieldRow($model, 'kpp');
        echo $form->textFieldRow($model, 'ogrn');
        ?>
    </div>
    <!-- КОНЕЦ поля для российских фирм -->

    <!-- НАЧАЛО поля для иностранных фирм -->
    <div id="foreign_fields">
        <?php
        echo $form->textFieldRow($model, 'vat_nom');
        echo $form->textFieldRow($model, 'reg_nom');
        echo $form->textFieldRow($model, 'sert_nom');
        ?>
    </div>
    <!-- КОНЕЦ поля для иностранных фирм -->

    <?php
    echo $form->textAreaRow($model, 'info');
    echo $form->dropDownListRow($model, 'profile', ContractorTypesActivities::model()->listNames($model->getForceCached()));

//    ContactPersonForOrganization::model()->listNames($model->getForceCached())

    echo $form->textFieldRow($model, 'yur_address');
    echo $form->textFieldRow($model, 'fact_address');
    echo $form->dropDownListRow($model, 'gendirector_id', Individual::model()->listNames($model->getForceCached()), array('empty' => '--- Не выбран ---'));
    echo $form->textFieldRow($model, 'email');
    echo $form->textFieldRow($model, 'phone');
    echo $form->textFieldRow($model, 'fax');

    /**
     * Только при редактировании.
     */
    if ($model->primaryKey) {
        $persons = Individual::model()->listNames($model->getForceCached());
        $docs = PowerAttorneyForOrganization::model()->listNames($model->primaryKey, $model->getForceCached());

        $data_signatories = array();
        foreach ($model->signatories as $v) {
            $data_signatories[] = array(
                'id' => $v['id'] . '_' . $v['doc_id'],
                'fio' => (isset($persons[$v['id']])
                    ? CHtml::link($persons[$v['id']], $this->createUrl('Individual/view', array('id' => $v['id'])))
                    : '---'
                ),
                'doc' => (isset($docs[$v['doc_id']]) ? $docs[$v['doc_id']] : '---'),
            );
        }
        echo $form->hiddenField($model, 'json_signatories');
        ?>
        <div class="control-group">
            <?= $form->labelEx($model, 'signatories', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                $this->widget('bootstrap.widgets.TbGridView',
                    array(
                        'id' => 'grid-signatories',
                        'type' => 'striped bordered condensed',
                        'dataProvider' => new CArrayDataProvider($data_signatories),
                        'template' => "{items}",
                        'htmlOptions' => array(
                            'data-type' => 'organization',
                            'data-id' => ($model->primaryKey) ? $model->primaryKey : ''
                        ),
                        'columns' => array(
                            array(
                                'name' => 'fio',
                                'header' => 'ФИО',
                                'type' => 'raw',
                                'htmlOptions' => array(
                                    'style' => 'width: 45%',
                                )
                            ),
                            array(
                                'name' => 'doc',
                                'header' => 'Тип',
                                'type' => 'raw',
                                'htmlOptions' => array(
                                    'style' => 'width: 45%',
                                )
                            ),
                        )
                    )
                );
                ?>
            </div>
        </div>
    <?php
    } // ENDIF if ($model->primaryKey)
    ?>

    <?= $form->textAreaRow($model, 'comment'); ?>

</fieldset>

<?php $this->endWidget(); ?>

<?php
/**
 * Модальное окошко для подписанта
 */
$this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'dataModalSignatory'));
?>
<div class="modal-header">
    <a class="close" data-dismiss="modal">×</a>
    <h4><?= Yii::t("menu", "Выберите довереность") ?></h4>
</div>
<div class="modal-body"></div>
<div class="modal-footer">
    <?php
    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => Yii::t("menu", "Сохранить"),
        'url' => '#',
        'htmlOptions' => array('class' => 'button_save', 'data-dismiss' => 'modal'),
    ));

    $this->widget('bootstrap.widgets.TbButton', array(
        'label' => Yii::t("menu", "Отмена"),
        'url' => '#',
        'htmlOptions' => array('data-dismiss' => 'modal'),
    ));
    ?>
</div>
<?php $this->endWidget(); ?>
