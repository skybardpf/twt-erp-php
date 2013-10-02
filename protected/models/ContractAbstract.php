<?php
/**
 * Общий класс для видов договора и договора.
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $account_counterparty
 * @property string $account_payment_contract
 * @property string $additional_charge_contract
 * @property string $additional_project
 * @property string $additional_third_party
 * @property string $address_object
 * @property string $address_warehouse
 * @property string $allowable_amount_of_debt
 * @property string $allowable_number_of_days
 * @property string $amount_charges
 * @property string $amount_contract
 * @property string $amount_insurance
 * @property string $amount_liability
 * @property string $amount_marketing_support
 * @property string $amount_other_services
 * @property string $amount_property_insurance
 * @property string $amount_security_deposit
 * @property string $amount_transportation
 * @property string $calculated_third
 * @property string $comment
 * @property string $commission
 * @property string $contractor_id
 * @property string $control_amount_debt
 * @property string $control_number_days
 * @property string $country_applicable_law
 * @property string $country_exportation
 * @property string $country_imports
 * @property string $country_service_product
 * @property string $currency_id
 * @property string $currency_payment_contract
 * @property string $date
 * @property string $description_goods
 * @property string $description_leased
 * @property string $description_work
 * @property string $destination
 * @property string $guarantee_period
 * @property string $incoterm
 * @property string $interest_book_value
 * @property string $interest_guarantee
 * @property string $interest_loan
 * @property string $invalid
 * @property string $keep_reserve_without_paying
 * @property string $kind_of_contract
 * @property string $list_documents
 * @property string $list_scans
 * @property string list_templates
 * @property string $location_court
 * @property string $maintaining_mutual
 * @property string $maturity_date_loan
 * @property string $method_providing
 * @property string $name_title_deed
 * @property string $notice_period_contract
 * @property string $number
 * @property string $number_days_without_payment
 * @property string $number_hours_services
 * @property string $number_locations
 * @property string $number_of_months
 * @property string $number_right_property
 * @property string $number_specialists
 * @property string $object_address_leased
 * @property string $one_number_shares
 * @property string $pay_day
 * @property string $paying_storage_month
 * @property string $payment_loading
 * @property string $percentage_liability
 * @property string $percentage_turnover
 * @property string $period_of_notice
 * @property string $place_of_contract
 * @property string $point_departure
 * @property string $prolongation_a_treaty
 * @property string $purpose_use
 * @property string $registration_number_mortgage
 * @property string $separat_records_goods
 * @property string $signatory_contractor
 * @property string $sum_payments_per_month
 * @property string $two_number_of_shares
 * @property string $type_extension
 * @property string $type_contract
 * @property string $unit_storage
 * @property string $usage_purpose
 * @property string $validity
 * @property string $view_buyer
 * @property string $view_one_shares
 * @property string $view_two_shares
 */
abstract class ContractAbstract extends SOAPModel
{
    const  PREFIX_CACHE_LIST_NAMES = '_list_names';
    const  PREFIX_CACHE_LIST_MODELS = '_list_models';

    /**
     * Очистка кеша.
     */
    public function clearCache()
    {
        $class = get_class($this);
        $cache = Yii::app()->cache;
        if ($this->primaryKey)
            $cache->delete($class . self::PREFIX_CACHE_MODEL_PK . $this->primaryKey);
        $cache->delete($class . self::PREFIX_CACHE_LIST_MODELS);
        $cache->delete($class . self::PREFIX_CACHE_LIST_NAMES);
    }

    /**
     * Виды параметров договоров
     * @return array
     */
    public function listAttributes()
    {
        return array(
            'account_counterparty',
            'account_payment_contract',
            'additional_charge_contract',
            'additional_project',
            'additional_third_party',
            'additional_type_contract',
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
            'contractor_id',
            'contractor_signatories',
            'control_amount_debt',
            'control_number_days',
            'country_applicable_law',
            'country_exportation',
            'country_imports',
            'country_service_product',
            'currency_id',
            'currency_payment_contract',
            'date',
            'description_goods',
            'description_leased',
            'description_work',
            'destination',
            'guarantee_period',
            'incoterm',
            'interest_book_value',
            'interest_guarantee',
            'interest_loan',
            'invalid',
            'keep_reserve_without_paying',
            'kind_of_contract',
            'list_documents',
            'list_scans',
            'list_templates',
            'location_court',
            'maintaining_mutual',
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
            'organization_signatories',
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
            'signatory_contractor',
            'sum_payments_per_month',
            'two_number_of_shares',
            'type_extension',
            'type_contract',
            'unit_storage',
            'usage_purpose',
            'validity',
            'view_buyer',
            'view_one_shares',
            'view_two_shares',
        );
    }

    /**
     * @return array
     */
    public function attributeNames()
    {
        return $this->listAttributes();
    }

    /**
     * Returns the list of attribute names of the model.
     * @return array list of attribute names.
     */
    public function attributeLabels()
    {
        return array(
            'account_counterparty' => 'Расчетный счет контрагента',
            'account_payment_contract' => 'Расчетный счет платежа по договору',
            'additional_charge_contract' => 'Ответственный по договору',
            'additional_project' => 'Проект',
            'additional_third_party' => 'Третья сторона',
            'additional_type_contract' => 'Вид договора',
            'address_object' => 'Адрес объекта',
            'address_warehouse' => 'Адрес склада',
            'allowable_amount_of_debt' => 'Допустимая сумма задолженности',
            'allowable_number_of_days' => 'Допустимое число дней задолженности',
            'amount_charges' => 'Сумма за доставку',
            'amount_contract' => 'Сумма договора',
            'amount_insurance' => 'Сумма страхования гражданской ответственности',
            'amount_liability' => 'Сумма ответственности',
            'amount_marketing_support' => 'Сумма маркетинговой поддержки',
            'amount_other_services' => 'Сумма за иные услуги',
            'amount_property_insurance' => 'Сумма страхования имущества',
            'amount_security_deposit' => 'Сумма обеспечительного взноса',
            'amount_transportation' => 'Сумма за транспортировку',
            'calculated_third' => 'Расчетный счет третьей стороны',
            'comment' => 'Комментарий',
            'commission' => 'Комиссионное вознаграждение',
            'contractor_id' => 'Контрагент',
            'contractor_signatories' => 'Подписанты контрагента',
            'control_amount_debt' => 'Контролировать сумму задолженности',
            'control_number_days' => 'Контролировать число дней задолженности',
            'country_applicable_law' => 'Страна применяемого права',
            'country_exportation' => 'Страна экспорта',
            'country_imports' => 'Страна импорта',
            'country_service_product' => 'Страна предоставления услуги товара',
            'currency_id' => 'Валюта взаиморасчетов',
            'currency_payment_contract' => 'Валюта оплаты по договору',
            'date' => 'Дата',
            'description_goods' => 'Описание товара',
            'description_leased' => 'Описание объекта переданного в аренду',
            'description_work' => 'Описание работ',
            'destination' => 'Пункт назначения',
            'guarantee_period' => 'Срок гарантии',
            'incoterm' => 'Инкотерм',
            'interest_book_value' => 'Проценты от балансовой стоимости',
            'interest_guarantee' => 'Проценты по гарантии',
            'interest_loan' => 'Проценты по займу',
            'invalid' => 'Недействителен',
            'keep_reserve_without_paying' => 'Держать резерв без оплаты ограниченное время',
            'kind_of_contract' => 'Вид условий договора',
            'list_documents' => 'Документы',
            'list_scans' => 'Сканы',
            'list_templates' => 'Шаблоны',
            'location_court' => 'Местонахождения суда',
            'maintaining_mutual' => 'Ведение взаиморасчетов',
            'maturity_date_loan' => 'Дата погашения кредита до',
            'method_providing' => 'Способ обеспечения',
            'name_title_deed' => 'Наименование документа на право собственности',
            'notice_period_contract' => 'Срок уведомления по договору',
            'number' => 'Номер',
            'number_days_without_payment' => 'Число дней резерва без оплаты',
            'number_hours_services' => 'Количество часов услуг',
            'number_locations' => 'Количество локаций',
            'number_of_months' => 'Кол-во месяцев',
            'number_right_property' => 'Номер документа на право собственности',
            'number_specialists' => 'Количество специалистов',
            'object_address_leased' => 'Адрес объекта переданного в аренду',
            'one_number_shares' => 'Количество 1 акций',
            'organization_signatories' => 'Подписанты по организации',
            'pay_day' => 'День оплаты',
            'paying_storage_month' => 'Платеж за хранение в месяц',
            'payment_loading' => 'Платеж за погрузочные работы за единицу',
            'percentage_liability' => 'Процент ответственности',
            'percentage_turnover' => 'Процент с оборота',
            'period_of_notice' => 'Срок уведомления',
            'place_of_contract' => 'Место заключения договора',
            'point_departure' => 'Пункт отправления',
            'prolongation_a_treaty' => 'Пролонгация договора',
            'purpose_use' => 'Назначение использования',
            'registration_number_mortgage' => 'Регистрационный номер договора залога',
            'separat_records_goods' => 'Обособленный учет товаров по заказам покупателей',
            'signatory_contractor' => 'Подписант контрагента',
            'sum_payments_per_month' => 'Сумма платежей в месяц',
            'two_number_of_shares' => 'Количество 2 акций',
            'type_contract' => 'Вид договора контрагентов',
            'type_extension' => 'Тип пролонгации',
            'unit_storage' => 'Единица места хранения',
            'usage_purpose' => 'Цель использования',
            'validity' => 'Срок действия',
            'view_buyer' => 'Вид деятельности',
            'view_one_shares' => 'Вид 1 акций',
            'view_two_shares' => 'Вид 2 акций',
        );
    }
}