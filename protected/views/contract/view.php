<?php
/**
 * Просмотр информации о договоре.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var ContractController $this
 * @var Contract $model
 * @var ContractType $contractType
 * @var ContractTemplate $contractTemplates
 * @var Organization $organization
 */
?>

<?php
Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/jquery.fileDownload.js');
Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/legal/manage_files.js');
Yii::app()->clientScript->registerScriptFile($this->asset_static.'/js/contract/download_template.js');

echo '<h2>Договор</h2>';

$this->widget('bootstrap.widgets.TbButton', array(
    'buttonType' => 'link',
    'type' => 'success',
    'label' => 'Редактировать',
    'url' => $this->createUrl("edit", array('id' => $model->primaryKey))
));

if (!$model->deleted) {
    echo "&nbsp;";
    Yii::app()->clientScript->registerScriptFile($this->asset_static . '/js/legal/delete_item.js');

    $this->widget('bootstrap.widgets.TbButton', array(
        'buttonType' => 'submit',
        'type' => 'danger',
        'label' => 'Удалить',
        'htmlOptions' => array(
            'data-question' => 'Вы уверены, что хотите удалить договор?',
            'data-title' => 'Удаление договора',
            'data-url' => $this->createUrl('delete', array('id' => $model->primaryKey)),
            'data-redirect_url' => $this->createUrl('list', array('id' => $model->contractor_id)),
            'data-delete_item_element' => '1'
        )
    ));
}
?>
    <br/><br/>
<?php
$currencies = Currency::model()->listNames($this->getForceCached());
$countries = Country::model()->listNames($this->getForceCached());
$contractors = Contractor::model()->getListNames($this->getForceCached());
$organizations = Organization::model()->getListNames($this->getForceCached());
$individuals = Individual::model()->listNames($this->getForceCached());
$incoterms = Incoterm::model()->listNames($this->getForceCached());
$projects = Project::model()->listNames($this->getForceCached());
$settlementAccountNames = SettlementAccount::model()->listNames($this->getForceCached());

$contractTypes = ContractType::model()->listNames($this->getForceCached());
$kindsOfContract = Contract::getKindsOfContract();
$maintainingMutual = Contract::getMaintainingMutual();
$prolongationTypes = Contract::getProlongationTypes();
$typesAgreementsAccounts = Contract::getTypesAgreementsAccounts();

$arr = array(
    'address_object',
    'address_warehouse',
    'allowable_amount_of_debt',
    'allowable_number_of_days',
    'amount_charges',
    'amount_contract',
    'amount_insurance',
    'amount_liability',
    'amount_marketing_support',
    'amount_other_services',
    'amount_property_insurance',
    'amount_security_deposit',
    'amount_transportation',
    'calculated_third',
    'comment',
    'commission',
    'control_amount_debt',
    'control_number_days',
    'date',
    'description_goods',
    'description_leased',
    'description_work',
    'destination',
    'guarantee_period',
    'interest_book_value',
    'interest_guarantee',
    'interest_loan',
    'invalid',
    'keep_reserve_without_paying',
    'location_court',
    'maturity_date_loan',
    'method_providing',
    'name_title_deed',
    'notice_period_contract',
    'number',
    'number_days_without_payment',
    'number_hours_services',
    'number_locations',
    'number_of_months',
    'number_right_property',
    'number_specialists',
    'object_address_leased',
    'one_number_shares',
    'pay_day',
    'paying_storage_month',
    'payment_loading',
    'percentage_liability',
    'percentage_turnover',
    'period_of_notice',
    'place_contract',
    'point_departure',
    'prolongation_a_treaty',
    'purpose_use',
    'registration_number_mortgage',
    'separat_records_goods',
    'sum_payments_per_month',
    'two_number_of_shares',
    'unit_storage',
    'usage_purpose',
    'validity',
    'view_buyer',
    'view_one_shares',
);
$attributes = array(
    'name',
    $attributes[] = array(
        'name' => 'le_id',
        'label' => 'Владелец',
        'value' => (isset($contractors[$model->le_id])) ? $contractors[$model->le_id] : '---'
    ),
    $attributes[] = array(
        'name' => 'additional_type_contract',
        'label' => 'Вид договора',
        'value' => (isset($contractTypes[$model->additional_type_contract])) ? $contractTypes[$model->additional_type_contract] : '---'
    ),
);
foreach($arr as $a){
    if ($contractType->isShowAttribute($a))
        $attributes[] = $a;
}

if ($contractType->isShowAttribute('account_counterparty')){
    $attributes[] = array(
        'name' => 'account_counterparty',
        'label' => 'Расчетный счет контрагента',
        'value' => (isset($settlementAccountNames[$model->account_counterparty])) ? $settlementAccountNames[$model->account_counterparty] : '---'
    );
}
if ($contractType->isShowAttribute('account_payment_contract')){
    $attributes[] = array(
        'name' => 'account_payment_contract',
        'label' => 'Расчетный счет платежа по договору',
        'value' => (isset($settlementAccountNames[$model->account_payment_contract])) ? $settlementAccountNames[$model->account_payment_contract] : '---'
    );
}
if ($contractType->isShowAttribute('additional_charge_contract')){
    $attributes[] = array(
        'name' => 'additional_charge_contract',
        'label' => 'Ответственный по договору',
        'value' => (isset($individuals[$model->additional_charge_contract])) ? $individuals[$model->additional_charge_contract] : '---'
    );
}
if ($contractType->isShowAttribute('additional_project')){
    $attributes[] = array(
        'name' => 'additional_project',
        'label' => 'Проект',
        'value' => (isset($projects[$model->additional_project])) ? $projects[$model->additional_project] : '---'
    );
}
if ($contractType->isShowAttribute('additional_third_party')){
    $attributes[] = array(
        'name' => 'additional_third_party',
        'label' => 'Третья сторона',
        'value' => (isset($contractors[$model->additional_third_party])) ? $contractors[$model->additional_third_party] : '---'
    );
}
if ($contractType->isShowAttribute('contractor_id')){
    $attributes[] = array(
        'name' => 'contractor_id',
        'label' => 'Контрагент',
        'value' => (isset($organizations[$model->contractor_id])) ? $organizations[$model->contractor_id] : '---'
    );
}

/**
 * Список подписантов контрагента
 */
if ($contractType->isShowAttribute('contractor_signatories')){
    $contractorSignatories = array();
    foreach ($model->contractor_signatories as $id) {
        $pid = 'person_' . $id;
        $contractorSignatories[] = array(
            'id' => $pid,
            'name' => (isset($individuals[$id])
                ? CHtml::link(
                    $individuals[$id],
                    $this->createUrl('individual/view', array('id' => $id))
                )
                : '---'
            )
        );
    }

    $attributes[] = array(
        'name' => 'contractor_signatories',
        'label' => 'Подписанты контрагента',
        'type' => 'raw',
        'value' => $this->widget('bootstrap.widgets.TbGridView',
            array(
                'dataProvider' => new CArrayDataProvider($contractorSignatories),
                'template' => "{items}",
                'columns' => array(
                    array(
                        'name' => 'name',
                        'header' => '',
                        'type' => 'raw',
                    )
                ),
            ),
            true
        )
    );
}

/**
 * Список подписантов организации
 */
if ($contractType->isShowAttribute('organization_signatories')){
    $organizationSignatories = array();
    foreach ($model->organization_signatories as $id) {
        $pid = 'person_' . $id;
        $organizationSignatories[] = array(
            'id' => $pid,
            'name' => (isset($individuals[$id])
                ? CHtml::link(
                    $individuals[$id],
                    $this->createUrl('individual/view', array('id' => $id))
                )
                : '---'
            )
        );
    }

    $attributes[] = array(
        'name' => 'organization_signatories',
        'label' => 'Подписанты по организации',
        'type' => 'raw',
        'value' => $this->widget('bootstrap.widgets.TbGridView',
            array(
                'dataProvider' => new CArrayDataProvider($organizationSignatories),
                'template' => "{items}",
                'columns' => array(
                    array(
                        'name' => 'name',
                        'header' => '',
                        'type' => 'raw',
                    )
                ),
            ),
            true
        )
    );
}
if ($contractType->isShowAttribute('country_applicable_law')){
    $attributes[] = array(
        'name' => 'country_applicable_law',
        'label' => 'Страна применяемого права',
        'value' => (isset($countries[$model->country_applicable_law])) ? $countries[$model->country_applicable_law] : '---'
    );
}
if ($contractType->isShowAttribute('country_exportation')){
    $attributes[] = array(
        'name' => 'country_exportation',
        'label' => 'Страна экспорта',
        'value' => (isset($countries[$model->country_exportation])) ? $countries[$model->country_exportation] : '---'
    );
}
if ($contractType->isShowAttribute('country_imports')){
    $attributes[] = array(
        'name' => 'country_imports',
        'label' => 'Страна импорта',
        'value' => (isset($countries[$model->country_imports])) ? $countries[$model->country_imports] : '---'
    );
}
if ($contractType->isShowAttribute('country_service_product')){
    $attributes[] = array(
        'name' => 'country_service_product',
        'label' => 'Страна предоставления услуги товара',
        'value' => (isset($countries[$model->country_service_product])) ? $countries[$model->country_service_product] : '---'
    );
}
if ($contractType->isShowAttribute('currency_id')){
    $attributes[] = array(
        'name' => 'currency_id',
        'label' => 'Валюта взаиморасчетов',
        'value' => (isset($currencies[$model->currency_id])) ? $currencies[$model->currency_id] : '---'
    );
}
if ($contractType->isShowAttribute('currency_payment_contract')){
    $attributes[] = array(
        'name' => 'currency_payment_contract',
        'label' => 'Валюта оплаты по договору',
        'value' => (isset($currencies[$model->currency_payment_contract])) ? $currencies[$model->currency_payment_contract] : '---'
    );
}
if ($contractType->isShowAttribute('incoterm')){
    $attributes[] = array(
        'name' => 'incoterm',
        'label' => 'Инкотерм',
        'value' => (isset($incoterms[$model->incoterm])) ? $incoterms[$model->incoterm] : '---'
    );
}
if ($contractType->isShowAttribute('kind_of_contract')){
    $attributes[] = array(
        'name' => 'kind_of_contract',
        'label' => 'Вид условий договора',
        'value' => (isset($kindsOfContract[$model->kind_of_contract])) ? $kindsOfContract[$model->kind_of_contract] : '---'
    );
}
if ($contractType->isShowAttribute('maintaining_mutual')){
    $attributes[] = array(
        'name' => 'maintaining_mutual',
        'label' => 'Ведение взаиморасчетов',
        'value' => (isset($maintainingMutual[$model->maintaining_mutual])) ? $maintainingMutual[$model->maintaining_mutual] : '---'
    );
}
if ($contractType->isShowAttribute('signatory_contractor')){
    $attributes[] = array(
        'name' => 'signatory_contractor',
        'label' => 'Подписант контрагента',
        'value' => (isset($individuals[$model->signatory_contractor])) ? $individuals[$model->signatory_contractor] : '---'
    );
}
if ($contractType->isShowAttribute('type_extension')){
    $attributes[] = array(
        'name' => 'type_extension',
        'label' => 'Тип пролонгации',
        'value' => (isset($prolongationTypes[$model->type_extension])) ? $prolongationTypes[$model->type_extension] : '---'
    );
}
if ($contractType->isShowAttribute('type_contract')){
    $attributes[] = array(
        'name' => 'type_contract',
        'label' => 'Вид договора',
        'value' => (isset($typesAgreementsAccounts[$model->type_contract])) ? $typesAgreementsAccounts[$model->type_contract] : '---'
    );
}

$this->widget('bootstrap.widgets.TbDetailView', array(
    'htmlOptions' => array('style' => 'width:100%;'),
    'data' => $model,
    'attributes' => $attributes
));

/**
 * Файлы и сканы.
 */
echo CHtml::tag('div', array(
    'class' => 'model-info',
    'data-id' => $model->primaryKey,
    'data-class-name' => get_class($model)
));
if ($contractType->isShowAttribute('list_documents') && !empty($model->list_documents)) {
    echo '<h4>Документы:</h4>';
    foreach ($model->list_documents as $f) {
        echo CHtml::link($f, '#',
            array(
                'class' => 'download_file',
                'data-type' => MDocumentCategory::FILE,
            )
        ) . '<br/>';
    }
}
if ($contractType->isShowAttribute('list_scans') && !empty($model->list_scans)) {
    echo '<h4>Сканы:</h4>';
    foreach ($model->list_scans as $f) {
        echo CHtml::link($f, '#', array(
                'class' => 'download_file',
                'data-type' => MDocumentCategory::SCAN,
            )
        ) . '<br/>';
    }
}
if ($contractType->isShowAttribute('list_templates') && !empty($contractTemplates)) {
    echo '<h4>Шаблоны:</h4>';
    foreach ($contractTemplates as $k=>$f) {
        echo CHtml::link($f, '#', array(
                'class' => 'download_template',
                'data-template-id' => $k,
            )
        ) . '<br/>';
    }
}


?>
<div id="preparing-file-modal" title="Подготовка файла..." style="display: none;">
    Подготавливается файл для скачивания, подождите...

    <div class="ui-progressbar-value ui-corner-left ui-corner-right" style="width: 100%; height:22px; margin-top: 20px;"></div>
</div>
<div id="error-modal" title="Error" style="display: none;">
    Возникли проблемы при подготовке файла, повторите попытку
</div>