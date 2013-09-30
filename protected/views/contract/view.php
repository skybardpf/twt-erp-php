<?php
/**
 * Просмотр информации о договоре.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @var ContractController $this
 * @var Contract $model
 * @var Organization $organization
 */
?>

<?php
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

$contractTypes = ContractType::model()->listNames($this->getForceCached());
$kindsOfContract = Contract::getKindsOfContract();
$maintainingMutual = Contract::getMaintainingMutual();
$prolongationTypes = Contract::getProlongationTypes();
$typesAgreementsAccounts = Contract::getTypesAgreementsAccounts();

/**
 * Список подписантов организации
 */
$organizationSignatories = array();
foreach($model->organization_signatories as $id){
    $pid = 'person_'.$id;
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

/**
 * Список подписантов контрагента
 */
$contractorSignatories = array();
foreach($model->contractor_signatories as $id){
    $pid = 'person_'.$id;
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

$this->widget('bootstrap.widgets.TbDetailView', array(
    'htmlOptions' => array('style' => 'width:100%;'),
    'data' => $model,
    'attributes' => array(
        'name',

        'account_counterparty',
        'account_payment_contract',
        array(
            'name' => 'additional_charge_contract',
            'label' => 'Ответственный по договору',
            'value' => (isset($individuals[$model->additional_charge_contract])) ? $individuals[$model->additional_charge_contract] : '---'
        ),
        array(
            'name' => 'additional_project',
            'label' => 'Проект',
            'value' => (isset($projects[$model->additional_project])) ? $projects[$model->additional_project] : '---'
        ),
        array(
            'name' => 'additional_third_party',
            'label' => 'Третья сторона',
            'value' => (isset($contractors[$model->additional_third_party])) ? $contractors[$model->additional_third_party] : '---'
        ),
        array(
            'name' => 'additional_type_contract',
            'label' => 'Вид договора',
            'value' => (isset($contractTypes[$model->additional_type_contract])) ? $contractTypes[$model->additional_type_contract] : '---'
        ),

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

        array(
            'name' => 'contractor_id',
            'label' => 'Контрагент',
            'value' => (isset($organizations[$model->contractor_id])) ? $organizations[$model->contractor_id] : '---'
        ),

        array(
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
        ),

        'control_amount_debt',
        'control_number_days',

        array(
            'name' => 'country_applicable_law',
            'label' => 'Страна применяемого права',
            'value' => (isset($countries[$model->country_applicable_law])) ? $countries[$model->country_applicable_law] : '---'
        ),
        array(
            'name' => 'country_exportation',
            'label' => 'Страна экспорта',
            'value' => (isset($countries[$model->country_exportation])) ? $countries[$model->country_exportation] : '---'
        ),
        array(
            'name' => 'country_imports',
            'label' => 'Страна импорта',
            'value' => (isset($countries[$model->country_imports])) ? $countries[$model->country_imports] : '---'
        ),
        array(
            'name' => 'country_service_product',
            'label' => 'Страна предоставления услуги товара',
            'value' => (isset($countries[$model->country_service_product])) ? $countries[$model->country_service_product] : '---'
        ),

        array(
            'name' => 'currency_id',
            'label' => 'Валюта взаиморасчетов',
            'value' => (isset($currencies[$model->currency_id])) ? $currencies[$model->currency_id] : '---'
        ),
        array(
            'name' => 'currency_payment_contract',
            'label' => 'Валюта оплаты по договору',
            'value' => (isset($currencies[$model->currency_payment_contract])) ? $currencies[$model->currency_payment_contract] : '---'
        ),

        'date',
        'description_goods',
        'description_leased',
        'description_work',
        'destination',
        'guarantee_period',

        array(
            'name' => 'incoterm',
            'label' => 'Инкотерм',
            'value' => (isset($incoterms[$model->incoterm])) ? $incoterms[$model->incoterm] : '---'
        ),

        'interest_book_value',
        'interest_guarantee',
        'interest_loan',
        'invalid',
        'keep_reserve_without_paying',

        array(
            'name' => 'kind_of_contract',
            'label' => 'Вид условий договора',
            'value' => (isset($kindsOfContract[$model->kind_of_contract])) ? $kindsOfContract[$model->kind_of_contract] : '---'
        ),

        'location_court',

        array(
            'name' => 'maintaining_mutual',
            'label' => 'Ведение взаиморасчетов',
            'value' => (isset($maintainingMutual[$model->maintaining_mutual])) ? $maintainingMutual[$model->maintaining_mutual] : '---'
        ),

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

        array(
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
        ),

        'pay_day',
        'paying_storage_month',
        'payment_loading',
        'percentage_liability',
        'percentage_turnover',
        'period_of_notice',
        'place_of_contract',
        'point_departure',
        'prolongation_a_treaty',
        'purpose_use',
        'registration_number_mortgage',
        'separat_records_goods',

        array(
            'name' => 'signatory_contractor',
            'label' => 'Подписант контрагента',
            'value' => (isset($individuals[$model->signatory_contractor])) ? $individuals[$model->signatory_contractor] : '---'
        ),

        'sum_payments_per_month',
        'two_number_of_shares',

        array(
            'name' => 'type_extension',
            'label' => 'Тип пролонгации',
            'value' => (isset($prolongationTypes[$model->type_extension])) ? $prolongationTypes[$model->type_extension] : '---'
        ),
        array(
            'name' => 'type_contract',
            'label' => 'Вид договора',
            'value' => (isset($typesAgreementsAccounts[$model->type_contract])) ? $typesAgreementsAccounts[$model->type_contract] : '---'
        ),

        'unit_storage',
        'usage_purpose',
        'validity',
        'view_buyer',
        'view_one_shares',

//            'list_documents',
//            'list_scans',
//            'list_templates',
    )
));

//    echo 'Скачать электронную версию: '.CHtml::link('сгенерированную системой').' или '.CHtml::link('пользовательскую');
//    echo '<br/>'.CHtml::link('Скачать скан');
?>