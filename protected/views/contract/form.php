<?php
/**
 * Форма редактирования договора.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var ContractController $this
 * @var Contract $model
 * @var Organization $organization
 */
?>

<?php
Yii::app()->clientScript->registerScriptFile($this->asset_static . '/js/jquery.json-2.4.min.js');
Yii::app()->clientScript->registerScriptFile($this->asset_static . '/js/contract/form.js');

echo '<h2>' . ($model->primaryKey ? 'Редактирование' : 'Создание') . ' договора</h2>';

/**
 * @var MTbActiveForm $form
 */
$form = $this->beginWidget('bootstrap.widgets.MTbActiveForm', array(
    'id' => 'form-contract',
    'type' => 'horizontal',
    'enableAjaxValidation' => true,
    'clientOptions' => array(
        'validateOnChange' => true,
    ),
    'htmlOptions' => array(
        'enctype' => 'multipart/form-data'
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
            : $this->createUrl('list', array('org_id' => $organization->primaryKey)))
);

if ($model->hasErrors()) {
    echo '<br/><br/>' . $form->errorSummary($model);
}
?>

    <fieldset>
    <?php
    // Опции для JUI селектора даты
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

    $individuals = Individual::model()->listNames($this->getForceCached());
    $contractors = Contractor::model()->getListNames($this->getForceCached());
    $organizations = Organization::model()->getListNames($this->getForceCached());
    $currencies = Currency::model()->listNames($this->getForceCached());
    $countries = Country::model()->listNames($this->getForceCached());
    $projects = Project::model()->listNames($this->getForceCached());
    $incoterms = Incoterm::model()->listNames($this->getForceCached());
    $settlementAccountNames = SettlementAccount::model()->listNames($this->getForceCached());
    $contractTypes = ContractType::model()->listNames($this->getForceCached());

    $data_scans = array();
    $data_documents = array();

    echo $form->dropDownListRow($model, 'additional_type_contract', $contractTypes);

    echo $form->dropDownListRow($model, 'account_counterparty', $settlementAccountNames);
    echo $form->dropDownListRow($model, 'account_payment_contract', $settlementAccountNames);
    echo $form->dropDownListRow($model, 'additional_charge_contract', $individuals);
    echo $form->dropDownListRow($model, 'additional_project', $projects);
    echo $form->dropDownListRow($model, 'additional_third_party', $contractors);
    echo $form->textFieldRow($model, 'address_object');
    echo $form->textFieldRow($model, 'address_warehouse');
    echo $form->textFieldRow($model, 'allowable_amount_of_debt');
    echo $form->textFieldRow($model, 'allowable_number_of_days');
    echo $form->textFieldRow($model, 'amount_charges');
    echo $form->textFieldRow($model, 'amount_contract');
    echo $form->textFieldRow($model, 'amount_insurance');
    echo $form->textFieldRow($model, 'amount_liability');
    echo $form->textFieldRow($model, 'amount_marketing_support');
    echo $form->textFieldRow($model, 'amount_other_services');
    echo $form->textFieldRow($model, 'amount_property_insurance');
    echo $form->textFieldRow($model, 'amount_security_deposit');
    echo $form->textFieldRow($model, 'amount_transportation');
    echo $form->textFieldRow($model, 'calculated_third');
    echo $form->textFieldRow($model, 'comment');
    echo $form->textFieldRow($model, 'commission');
    echo $form->dropDownListRow($model, 'contractor_id', $organizations);

    /**
     * Генерируем таблицу для отображения подписантов организации
     */
    $data = array();
    $class_button = 'add-signatory ' . ((count($model->organization_signatories) >= 2) ? 'hide' : '');
    foreach ($model->organization_signatories as $id) {
        $data[] = array(
            'id' => $id,
            'name' => (isset($individuals[$id])
                ? CHtml::link($individuals[$id], $this->createUrl('individual/view', array('id' => $id)))
                : '---'
            ),
            'delete' => $this->widget('bootstrap.widgets.TbButton', array(
                'buttonType' => 'button',
                'type' => 'primary',
                'label' => 'Удалить',
                'htmlOptions' => array(
                    'class' => 'del-signatory',
                    'data-id' => $id,
                    'data-type' => 'organization_signatories'
                )
            ), true)
        );
    }
    $div_signatory = $this->widget('bootstrap.widgets.TbGridView',
        array(
            'id' => get_class($model) . '_signatory',
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
        'buttonType' => 'button',
        'type' => 'primary',
        'label' => 'Добавить',
        'htmlOptions' => array(
            'class' => $class_button,
            'data-type' => 'organization_signatories',
        )
    ), true);

    ?>
    <div class="control-group">
        <?= CHtml::activeLabelEx($model, 'organization_signatories', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo $div_signatory;
            echo CHtml::tag('div', array(), $form->error($model, 'organization_signatories'));
            ?>
        </div>
    </div>
    <?php

    /**
     * Генерируем таблицу для отображения подписантов контрагента
     */
    $data = array();
    $class_button = 'add-signatory-contractor ' . ((count($model->contractor_signatories) >= 2) ? 'hide' : '');
    foreach ($model->contractor_signatories as $id) {
        $data[] = array(
            'id' => $id,
            'name' => (isset($individuals[$id])
                ? CHtml::link($individuals[$id], $this->createUrl('individual/view', array('id' => $id)))
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
            'id' => get_class($model) . '_signatory_contr',
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
        'buttonType' => 'button',
        'type' => 'primary',
        'label' => 'Добавить',
        'htmlOptions' => array(
            'class' => $class_button,
            'data-type' => 'contractor_signatories',
        )
    ), true);

    ?>
    <div class="control-group">
        <?= CHtml::activeLabelEx($model, 'contractor_signatories', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo $div_signatory_contractor;
            echo CHtml::tag('div', array(), $form->error($model, 'contractor_signatories'));
            ?>
        </div>
    </div>
    <?php
    /**
     * Hidden поля для управления подписантами
     */
    echo $form->hiddenField($model, 'json_organization_signatories');
    echo $form->hiddenField($model, 'json_contractor_signatories');

    echo $form->checkBoxRow($model, 'control_amount_debt');
    echo $form->checkBoxRow($model, 'control_number_days');
    echo $form->dropDownListRow($model, 'country_applicable_law', $countries);
    echo $form->dropDownListRow($model, 'country_exportation', $countries);
    echo $form->dropDownListRow($model, 'country_imports', $countries);
    echo $form->dropDownListRow($model, 'country_service_product', $countries);
    echo $form->dropDownListRow($model, 'currency_id', $currencies);
    echo $form->dropDownListRow($model, 'currency_payment_contract', $currencies);

    ?>
    <div class="control-group">
        <?= $form->labelEx($model, 'date', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array_merge(
                array(
                    'model' => $model,
                    'attribute' => 'date'
                ), $jui_date_options
            ));
            echo $form->error($model, 'date');
            ?>
        </div>
    </div>
    <?php

    echo $form->textFieldRow($model, 'description_goods');
    echo $form->textFieldRow($model, 'description_leased');
    echo $form->textFieldRow($model, 'description_work');
    echo $form->textFieldRow($model, 'destination');

    ?>
    <div class="control-group">
        <?= $form->labelEx($model, 'guarantee_period', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::activeNumberField($model, 'guarantee_period');
            echo $form->error($model, 'guarantee_period');
            ?>
        </div>
    </div>

    <div class="control-group">
        <?= $form->labelEx($model, 'notice_period_contract', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::activeNumberField($model, 'notice_period_contract');
            echo $form->error($model, 'notice_period_contract');
            ?>
        </div>
    </div>
    <?php

    echo $form->dropDownListRow($model, 'incoterm', $incoterms);
    echo $form->textFieldRow($model, 'interest_book_value');
    echo $form->textFieldRow($model, 'interest_guarantee');
    echo $form->textFieldRow($model, 'interest_loan');
    echo $form->checkBoxRow($model, 'invalid');
    echo $form->checkBoxRow($model, 'keep_reserve_without_paying');
    echo $form->dropDownListRow($model, 'kind_of_contract', Contract::getKindsOfContract());
    echo $form->textFieldRow($model, 'location_court');
    echo $form->dropDownListRow($model, 'maintaining_mutual', Contract::getMaintainingMutual());
    ?>
    <div class="control-group">
        <?= $form->labelEx($model, 'maturity_date_loan', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array_merge(
                array(
                    'model' => $model,
                    'attribute' => 'maturity_date_loan'
                ), $jui_date_options
            ));
            echo $form->error($model, 'maturity_date_loan');
            ?>
        </div>
    </div>
    <?php

    echo $form->textFieldRow($model, 'method_providing');
    echo $form->textFieldRow($model, 'name_title_deed');
    echo $form->textFieldRow($model, 'number');
    echo $form->textFieldRow($model, 'number_days_without_payment');
    echo $form->textFieldRow($model, 'number_hours_services');
    echo $form->textFieldRow($model, 'number_locations');
    echo $form->textFieldRow($model, 'number_of_months');
    echo $form->textFieldRow($model, 'number_right_property');
    echo $form->textFieldRow($model, 'object_address_leased');

    ?>
    <div class="control-group">
        <?= $form->labelEx($model, 'number_specialists', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::activeNumberField($model, 'number_specialists');
            echo $form->error($model, 'number_specialists');
            ?>
        </div>
    </div>

    <div class="control-group">
        <?= $form->labelEx($model, 'one_number_shares', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::activeNumberField($model, 'one_number_shares');
            echo $form->error($model, 'one_number_shares');
            ?>
        </div>
    </div>

    <div class="control-group">
        <?= $form->labelEx($model, 'pay_day', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array_merge(
                array(
                    'model' => $model,
                    'attribute' => 'pay_day'
                ), $jui_date_options
            ));
            echo $form->error($model, 'pay_day');
            ?>
        </div>
    </div>

    <div class="control-group">
        <?= $form->labelEx($model, 'paying_storage_month', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::activeNumberField($model, 'paying_storage_month');
            echo $form->error($model, 'paying_storage_month');
            ?>
        </div>
    </div>

    <div class="control-group">
        <?= $form->labelEx($model, 'payment_loading', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::activeNumberField($model, 'payment_loading');
            echo $form->error($model, 'payment_loading');
            ?>
        </div>
    </div>

    <div class="control-group">
        <?= $form->labelEx($model, 'percentage_liability', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::activeNumberField($model, 'percentage_liability');
            echo $form->error($model, 'percentage_liability');
            ?>
        </div>
    </div>

    <div class="control-group">
        <?= $form->labelEx($model, 'percentage_turnover', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::activeNumberField($model, 'percentage_turnover');
            echo $form->error($model, 'percentage_turnover');
            ?>
        </div>
    </div>

    <div class="control-group">
        <?= $form->labelEx($model, 'prolongation_a_treaty', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::activeNumberField($model, 'prolongation_a_treaty');
            echo $form->error($model, 'prolongation_a_treaty');
            ?>
        </div>
    </div>

    <div class="control-group">
        <?= $form->labelEx($model, 'period_of_notice', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array_merge(
                array(
                    'model' => $model,
                    'attribute' => 'period_of_notice'
                ), $jui_date_options
            ));
            echo $form->error($model, 'period_of_notice');
            ?>
        </div>
    </div>
    <?php

    echo $form->dropDownListRow($model, 'place_of_contract', ContractPlace::model()->listNames($this->getForceCached()));
    echo $form->textFieldRow($model, 'point_departure');
    echo $form->textFieldRow($model, 'purpose_use');
    echo $form->textFieldRow($model, 'registration_number_mortgage');

    echo $form->checkBoxRow($model, 'separat_records_goods');

    echo $form->dropDownListRow($model, 'signatory_contractor', $individuals);

    ?>
    <div class="control-group">
        <?= $form->labelEx($model, 'sum_payments_per_month', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::activeNumberField($model, 'sum_payments_per_month');
            echo $form->error($model, 'sum_payments_per_month');
            ?>
        </div>
    </div>

    <div class="control-group">
        <?= $form->labelEx($model, 'two_number_of_shares', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            echo CHtml::activeNumberField($model, 'two_number_of_shares');
            echo $form->error($model, 'two_number_of_shares');
            ?>
        </div>
    </div>
    <?php

    echo $form->dropDownListRow($model, 'type_extension', Contract::getProlongationTypes());
    echo $form->dropDownListRow($model, 'type_contract', Contract::getTypesAgreementsAccounts());
    echo $form->textFieldRow($model, 'unit_storage');
    echo $form->textFieldRow($model, 'usage_purpose');
    echo $form->textFieldRow($model, 'view_buyer');
    echo $form->textFieldRow($model, 'view_one_shares');
    echo $form->textFieldRow($model, 'view_two_shares');

    //echo $form->textFieldRow($model, 'list_documents');   // TODO !!!
    //echo $form->textFieldRow($model, 'list_scans');       // TODO !!!
    //echo $form->textFieldRow($model, 'list_templates');   // TODO !!!

    ?>
    <div class="control-group">
        <?= $form->labelEx($model, 'validity', array('class' => 'control-label')); ?>
        <div class="controls">
            <?php
            $this->widget('zii.widgets.jui.CJuiDatePicker', array_merge(
                array(
                    'model' => $model,
                    'attribute' => 'validity'
                ), $jui_date_options
            ));
            echo $form->error($model, 'validity');
            ?>
        </div>
    </div>

    <?php
    /**
     * Вывод файлов и сканов
     */
    $data_files = array();
    $data_scans = array();
    if ($model->primaryKey) {
        $path = Yii::app()->user->getId() . DIRECTORY_SEPARATOR . __CLASS__ . DIRECTORY_SEPARATOR . $model->primaryKey;
        $path_scans = $path . DIRECTORY_SEPARATOR . MDocumentCategory::SCAN;
        $path_files = $path . DIRECTORY_SEPARATOR . MDocumentCategory::FILE;

        foreach ($model->list_documents as $f) {
            $data_files[] = array(
                'id' => $f . '_id',
                'filename' => CHtml::link($f, '#', array(
                    'class' => 'download_file',
                    'data-type' => MDocumentCategory::FILE
                )),
                'delete' => $this->widget('bootstrap.widgets.TbButton', array(
                    'buttonType' => 'button',
                    'type' => 'primary',
                    'label' => 'Удалить',
                    'htmlOptions' => array(
                        'class' => 'delete_file',
                        'data-type' => MDocumentCategory::FILE,
                        'data-filename' => $f
                    )
                ), true)
            );
        }
        foreach ($model->list_scans as $f) {
            $data_scans[] = array(
                'id' => $f . '_id',
                'filename' => CHtml::link($f, '#', array(
                    'class' => 'download_file',
                    'data-type' => MDocumentCategory::SCAN
                )),
                'delete' => $this->widget('bootstrap.widgets.TbButton', array(
                    'buttonType' => 'button',
                    'type' => 'primary',
                    'label' => 'Удалить',
                    'htmlOptions' => array(
                        'class' => 'delete_file',
                        'data-type' => MDocumentCategory::SCAN,
                        'data-filename' => $f
                    )
                ), true)
            );
        }
    }

    echo CHtml::tag('div', array(
        'class' => 'model-info',
        'data-id' => $model->primaryKey,
        'data-class-name' => get_class($model)
    ));
    echo $form->hiddenField($model, 'json_exists_documents');
    echo $form->hiddenField($model, 'json_exists_scans');

    echo $this->renderPartial('/_files/grid_files',
        array(
            'data' => $data_files,
            'model' => $model,
            'attribute' => 'list_documents',
            'attribute_files' => 'upload_documents',
            'grid_id' => 'grid-files',
            'accept_ext' => '',
        ),
        true
    );
    echo $this->renderPartial('/_files/grid_files',
        array(
            'data' => $data_scans,
            'model' => $model,
            'attribute' => 'list_scans',
            'grid_id' => 'grid-scans',
            'attribute_files' => 'upload_scans',
            'accept_ext' => '',
        ),
        true
    );
    ?>


    </fieldset>
<?php
$this->endWidget();
echo $this->renderPartial('/_files/download_hint', array(), true);

/**
 * Модальное окошко для подписанта
 */
$this->beginWidget('bootstrap.widgets.TbModal', array('id' => 'dataModalSignatory'));

?>
    <div class="modal-header">
        <a class="close" data-dismiss="modal">×</a>
        <h4><?= Yii::t("menu", "Выберите подписанта") ?></h4>
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