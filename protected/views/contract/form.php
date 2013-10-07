<?php
/**
 * Форма редактирования договора.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var ContractController $this
 * @var Contract $model
 * @var ContractType $contractType
 * @var Organization $organization
 * @var string $action
 */
?>

<script>
    window.contractAction = '<?= $action; ?>';
    window.contractId = '<?= $model->primaryKey; ?>';
    window.organizationId = '<?= $model->contractor_id; ?>';
</script>

<?php
Yii::app()->clientScript->registerScriptFile($this->asset_static . '/js/jquery.json-2.4.min.js');
Yii::app()->clientScript->registerScriptFile($this->asset_static . '/js/contract/form.js');
Yii::app()->clientScript->registerScriptFile($this->asset_static . '/js/jquery.fileDownload.js');
Yii::app()->clientScript->registerScriptFile($this->asset_static . '/js/legal/manage_files.js');


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
    $countries[''] = '--- Выбрать ---';
    $projects = Project::model()->listNames($this->getForceCached());
    $incoterms = Incoterm::model()->listNames($this->getForceCached());
    $settlementAccountNames = SettlementAccount::model()->listNames($this->getForceCached());
    $settlementAccountNames[''] = '--- Выбрать ---';
    $contractTypes = ContractType::model()->listNames($this->getForceCached());

    $data_scans = array();
    $data_documents = array();

    echo $form->textFieldRow($model, 'name');
    echo $form->dropDownListRow($model, 'le_id', $contractors);
    echo $form->dropDownListRow($model, 'additional_type_contract', $contractTypes);

    if ($contractType->isShowAttribute('account_counterparty'))
        echo $form->dropDownListRow($model, 'account_counterparty', $settlementAccountNames);
    if ($contractType->isShowAttribute('account_payment_contract'))
        echo $form->dropDownListRow($model, 'account_payment_contract', $settlementAccountNames);
    if ($contractType->isShowAttribute('calculated_third'))
        echo $form->dropDownListRow($model, 'calculated_third', $settlementAccountNames);

    if ($contractType->isShowAttribute('additional_charge_contract'))
        echo $form->dropDownListRow($model, 'additional_charge_contract', $individuals);
    if ($contractType->isShowAttribute('additional_project'))
        echo $form->dropDownListRow($model, 'additional_project', $projects);
    if ($contractType->isShowAttribute('additional_third_party')){
        $third_contractors = $contractors;
        $third_contractors[''] = '--- Не указан ---';
        echo $form->dropDownListRow($model, 'additional_third_party', $third_contractors);
    }

    if ($contractType->isShowAttribute('address_object'))
        echo $form->textFieldRow($model, 'address_object');
    if ($contractType->isShowAttribute('address_warehouse'))
        echo $form->textFieldRow($model, 'address_warehouse');
    if ($contractType->isShowAttribute('allowable_amount_of_debt'))
        echo $form->textFieldRow($model, 'allowable_amount_of_debt');
    if ($contractType->isShowAttribute('allowable_number_of_days'))
        echo $form->textFieldRow($model, 'allowable_number_of_days');
    if ($contractType->isShowAttribute('amount_charges'))
        echo $form->textFieldRow($model, 'amount_charges');
    if ($contractType->isShowAttribute('amount_contract'))
        echo $form->textFieldRow($model, 'amount_contract');
    if ($contractType->isShowAttribute('amount_insurance'))
        echo $form->textFieldRow($model, 'amount_insurance');
    if ($contractType->isShowAttribute('amount_liability'))
        echo $form->textFieldRow($model, 'amount_liability');
    if ($contractType->isShowAttribute('amount_marketing_support'))
        echo $form->textFieldRow($model, 'amount_marketing_support');
    if ($contractType->isShowAttribute('amount_other_services'))
        echo $form->textFieldRow($model, 'amount_other_services');
    if ($contractType->isShowAttribute('amount_property_insurance'))
        echo $form->textFieldRow($model, 'amount_property_insurance');
    if ($contractType->isShowAttribute('amount_security_deposit'))
        echo $form->textFieldRow($model, 'amount_security_deposit');
    if ($contractType->isShowAttribute('amount_transportation'))
        echo $form->textFieldRow($model, 'amount_transportation');

    if ($contractType->isShowAttribute('comment'))
        echo $form->textFieldRow($model, 'comment');
    if ($contractType->isShowAttribute('commission'))
        echo $form->textFieldRow($model, 'commission');
    if ($contractType->isShowAttribute('contractor_id'))
        echo $form->dropDownListRow($model, 'contractor_id', $organizations);

    /**
     * Генерируем таблицу для отображения подписантов организации
     */
    if ($contractType->isShowAttribute('organization_signatories')) {
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
    }

    /**
     * Генерируем таблицу для отображения подписантов контрагента
     */
    if ($contractType->isShowAttribute('contractor_signatories')) {
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
    }
    /**
     * Hidden поля для управления подписантами
     */
    echo $form->hiddenField($model, 'json_organization_signatories');
    echo $form->hiddenField($model, 'json_contractor_signatories');

    if ($contractType->isShowAttribute('control_amount_debt'))
        echo $form->checkBoxRow($model, 'control_amount_debt');
    if ($contractType->isShowAttribute('control_number_days'))
        echo $form->checkBoxRow($model, 'control_number_days');
    if ($contractType->isShowAttribute('country_applicable_law'))
        echo $form->dropDownListRow($model, 'country_applicable_law', $countries);
    if ($contractType->isShowAttribute('country_exportation'))
        echo $form->dropDownListRow($model, 'country_exportation', $countries);
    if ($contractType->isShowAttribute('country_imports'))
        echo $form->dropDownListRow($model, 'country_imports', $countries);
    if ($contractType->isShowAttribute('country_service_product'))
        echo $form->dropDownListRow($model, 'country_service_product', $countries);
    if ($contractType->isShowAttribute('currency_id'))
        echo $form->dropDownListRow($model, 'currency_id', $currencies);
    if ($contractType->isShowAttribute('currency_payment_contract'))
        echo $form->dropDownListRow($model, 'currency_payment_contract', $currencies);

    if ($contractType->isShowAttribute('date')){
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
    }

    if ($contractType->isShowAttribute('description_goods'))
        echo $form->textFieldRow($model, 'description_goods');
    if ($contractType->isShowAttribute('description_leased'))
        echo $form->textFieldRow($model, 'description_leased');
    if ($contractType->isShowAttribute('description_work'))
        echo $form->textFieldRow($model, 'description_work');
    if ($contractType->isShowAttribute('destination'))
        echo $form->textFieldRow($model, 'destination');

    if ($contractType->isShowAttribute('guarantee_period')){
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
        <?php
    }

    if ($contractType->isShowAttribute('notice_period_contract')){
        ?>
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
    }
    if ($contractType->isShowAttribute('period_of_notice')){
        ?>
        <div class="control-group">
            <?= $form->labelEx($model, 'period_of_notice', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::activeNumberField($model, 'period_of_notice');
                echo $form->error($model, 'period_of_notice');
                ?>
            </div>
        </div>
    <?php
    }

    if ($contractType->isShowAttribute('incoterm'))
        echo $form->dropDownListRow($model, 'incoterm', $incoterms);
    if ($contractType->isShowAttribute('interest_book_value'))
        echo $form->textFieldRow($model, 'interest_book_value');
    if ($contractType->isShowAttribute('interest_guarantee'))
        echo $form->textFieldRow($model, 'interest_guarantee');
    if ($contractType->isShowAttribute('interest_loan'))
        echo $form->textFieldRow($model, 'interest_loan');
    if ($contractType->isShowAttribute('invalid'))
        echo $form->checkBoxRow($model, 'invalid');
    if ($contractType->isShowAttribute('keep_reserve_without_paying'))
        echo $form->checkBoxRow($model, 'keep_reserve_without_paying');
    if ($contractType->isShowAttribute('kind_of_contract'))
        echo $form->dropDownListRow($model, 'kind_of_contract', Contract::getKindsOfContract());
    if ($contractType->isShowAttribute('location_court'))
        echo $form->textFieldRow($model, 'location_court');
    if ($contractType->isShowAttribute('maintaining_mutual'))
        echo $form->dropDownListRow($model, 'maintaining_mutual', Contract::getMaintainingMutual());

    if ($contractType->isShowAttribute('maturity_date_loan')){
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
    }

    if ($contractType->isShowAttribute('method_providing'))
        echo $form->textFieldRow($model, 'method_providing');
    if ($contractType->isShowAttribute('name_title_deed'))
        echo $form->textFieldRow($model, 'name_title_deed');
    if ($contractType->isShowAttribute('number'))
        echo $form->textFieldRow($model, 'number');
    if ($contractType->isShowAttribute('number_days_without_payment'))
        echo $form->textFieldRow($model, 'number_days_without_payment');
    if ($contractType->isShowAttribute('number_hours_services'))
        echo $form->textFieldRow($model, 'number_hours_services');
    if ($contractType->isShowAttribute('number_locations'))
        echo $form->textFieldRow($model, 'number_locations');
    if ($contractType->isShowAttribute('number_of_months'))
        echo $form->textFieldRow($model, 'number_of_months');
    if ($contractType->isShowAttribute('number_right_property'))
        echo $form->textFieldRow($model, 'number_right_property');
    if ($contractType->isShowAttribute('object_address_leased'))
        echo $form->textFieldRow($model, 'object_address_leased');

    if ($contractType->isShowAttribute('number_specialists')){
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
        <?php
    }

    if ($contractType->isShowAttribute('one_number_shares')){
        ?>
        <div class="control-group">
            <?= $form->labelEx($model, 'one_number_shares', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::activeNumberField($model, 'one_number_shares');
                echo $form->error($model, 'one_number_shares');
                ?>
            </div>
        </div>
        <?php
    }

    if ($contractType->isShowAttribute('pay_day')){
        ?>
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
        <?php
    }

    if ($contractType->isShowAttribute('paying_storage_month')){
        ?>
        <div class="control-group">
            <?= $form->labelEx($model, 'paying_storage_month', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::activeNumberField($model, 'paying_storage_month');
                echo $form->error($model, 'paying_storage_month');
                ?>
            </div>
        </div>
        <?php
    }

    if ($contractType->isShowAttribute('payment_loading')){
        ?>
        <div class="control-group">
            <?= $form->labelEx($model, 'payment_loading', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::activeNumberField($model, 'payment_loading');
                echo $form->error($model, 'payment_loading');
                ?>
            </div>
        </div>
        <?php
    }

    if ($contractType->isShowAttribute('percentage_liability')){
        ?>
        <div class="control-group">
            <?= $form->labelEx($model, 'percentage_liability', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::activeNumberField($model, 'percentage_liability');
                echo $form->error($model, 'percentage_liability');
                ?>
            </div>
        </div>
        <?php
    }

    if ($contractType->isShowAttribute('percentage_turnover')){
        ?>
        <div class="control-group">
            <?= $form->labelEx($model, 'percentage_turnover', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::activeNumberField($model, 'percentage_turnover');
                echo $form->error($model, 'percentage_turnover');
                ?>
            </div>
        </div>
        <?php
    }

    if ($contractType->isShowAttribute('prolongation_a_treaty')){
        ?>
        <div class="control-group">
            <?= $form->labelEx($model, 'prolongation_a_treaty', array('class' => 'control-label')); ?>
            <div class="controls">
                <?php
                echo CHtml::activeNumberField($model, 'prolongation_a_treaty');
                echo $form->error($model, 'prolongation_a_treaty');
                ?>
            </div>
        </div>
        <?php
    }

    if ($contractType->isShowAttribute('place_contract'))
        echo $form->dropDownListRow($model, 'place_contract', ContractPlace::model()->listNames($this->getForceCached()));
    if ($contractType->isShowAttribute('point_departure'))
        echo $form->textFieldRow($model, 'point_departure');
    if ($contractType->isShowAttribute('purpose_use'))
        echo $form->textFieldRow($model, 'purpose_use');
    if ($contractType->isShowAttribute('registration_number_mortgage'))
        echo $form->textFieldRow($model, 'registration_number_mortgage');
    if ($contractType->isShowAttribute('separat_records_goods'))
        echo $form->checkBoxRow($model, 'separat_records_goods');
    if ($contractType->isShowAttribute('signatory_contractor'))
        echo $form->dropDownListRow($model, 'signatory_contractor', $individuals);

    if ($contractType->isShowAttribute('sum_payments_per_month')){
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
        <?php
    }

    if ($contractType->isShowAttribute('two_number_of_shares')){
        ?>
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
    }

    if ($contractType->isShowAttribute('type_extension'))
        echo $form->dropDownListRow($model, 'type_extension', Contract::getProlongationTypes());
    if ($contractType->isShowAttribute('type_contract'))
        echo $form->dropDownListRow($model, 'type_contract', Contract::getTypesAgreementsAccounts());
    if ($contractType->isShowAttribute('unit_storage'))
        echo $form->textFieldRow($model, 'unit_storage');
    if ($contractType->isShowAttribute('usage_purpose'))
        echo $form->textFieldRow($model, 'usage_purpose');
    if ($contractType->isShowAttribute('view_buyer'))
        echo $form->textFieldRow($model, 'view_buyer');
    if ($contractType->isShowAttribute('view_one_shares'))
        echo $form->textFieldRow($model, 'view_one_shares');
    if ($contractType->isShowAttribute('view_two_shares'))
        echo $form->textFieldRow($model, 'view_two_shares');

    if ($contractType->isShowAttribute('validity')){
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
    }

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

    if ($contractType->isShowAttribute('list_documents')){
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
    }
    if ($contractType->isShowAttribute('list_scans')){
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
    }
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