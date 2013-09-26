<?php
/**
 * Вид договора.
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property bool   $is_standart
 * @property bool   $deleted
 *
 * @property string $account_counterparty
 * @property string $account_payment_contract
 * @property string $additional_charge_contract
 * @property string $additional_project
 * @property string $additional_third_party
 * @property string $additional_type_contract
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
 * @property string $contract_type_id
 * @property string $contractor_signatories
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
 * @property string $name
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
 * @property string $organization_signatories
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
class ContractType extends SOAPModel
{
    const  PREFIX_CACHE_LIST_NAMES = '_list_names';
    const  PREFIX_CACHE_LIST_MODELS = '_list_models';

    const STATUS_REQUIRED = 'Обязательное';
    const STATUS_SHOW = 'Присутствует';
    const STATUS_NO_SHOW = 'Отсутствует';

    /**
     * @static
     * @param string $className
     * @return ContractType
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @static
     * @return array
     */
    public static function getStatuses()
    {
        return array(
            self::STATUS_REQUIRED => self::STATUS_REQUIRED,
            self::STATUS_SHOW => self::STATUS_SHOW,
            self::STATUS_NO_SHOW => self::STATUS_NO_SHOW,
        );
    }

    public function afterConstruct()
    {
        $this->is_standart = false;

        $this->account_counterparty = self::STATUS_SHOW;
        $this->account_payment_contract = self::STATUS_SHOW;
        $this->additional_charge_contract = self::STATUS_SHOW;
        $this->additional_project = self::STATUS_SHOW;
        $this->additional_third_party = self::STATUS_SHOW;
        $this->additional_type_contract = self::STATUS_SHOW;
        $this->address_object = self::STATUS_SHOW;
        $this->address_warehouse = self::STATUS_SHOW;
        $this->allowable_amount_of_debt = self::STATUS_SHOW;
        $this->allowable_number_of_days = self::STATUS_SHOW;
        $this->amount_charges = self::STATUS_SHOW;
        $this->amount_contract = self::STATUS_SHOW;
        $this->amount_insurance = self::STATUS_SHOW;
        $this->amount_liability = self::STATUS_SHOW;
        $this->amount_marketing_support = self::STATUS_SHOW;
        $this->amount_other_services = self::STATUS_SHOW;
        $this->amount_property_insurance = self::STATUS_SHOW;
        $this->amount_security_deposit = self::STATUS_SHOW;
        $this->amount_transportation = self::STATUS_SHOW;
        $this->calculated_third = self::STATUS_SHOW;
        $this->comment = self::STATUS_SHOW;
        $this->commission = self::STATUS_SHOW;
        $this->contractor_id = self::STATUS_SHOW;
        $this->contract_type_id = self::STATUS_SHOW;
        $this->contractor_signatories = self::STATUS_SHOW;
        $this->control_amount_debt = self::STATUS_SHOW;
        $this->control_number_days = self::STATUS_SHOW;
        $this->country_applicable_law = self::STATUS_SHOW;
        $this->country_exportation = self::STATUS_SHOW;
        $this->country_imports = self::STATUS_SHOW;
        $this->country_service_product = self::STATUS_SHOW;
        $this->currency_id = self::STATUS_SHOW;
        $this->currency_payment_contract = self::STATUS_SHOW;
        $this->date = self::STATUS_SHOW;
        $this->description_goods = self::STATUS_SHOW;
        $this->description_leased = self::STATUS_SHOW;
        $this->description_work = self::STATUS_SHOW;
        $this->destination = self::STATUS_SHOW;
        $this->guarantee_period = self::STATUS_SHOW;
        $this->incoterm = self::STATUS_SHOW;
        $this->interest_book_value = self::STATUS_SHOW;
        $this->interest_guarantee = self::STATUS_SHOW;
        $this->interest_loan = self::STATUS_SHOW;
        $this->invalid = self::STATUS_SHOW;
        $this->keep_reserve_without_paying = self::STATUS_SHOW;
        $this->kind_of_contract = self::STATUS_SHOW;
        $this->list_documents = self::STATUS_SHOW;
        $this->list_scans = self::STATUS_SHOW;
        $this->list_templates = self::STATUS_SHOW;
        $this->location_court = self::STATUS_SHOW;
        $this->maintaining_mutual = self::STATUS_SHOW;
        $this->maturity_date_loan = self::STATUS_SHOW;
        $this->method_providing = self::STATUS_SHOW;
        $this->name_title_deed = self::STATUS_SHOW;
        $this->notice_period_contract = self::STATUS_SHOW;
        $this->number = self::STATUS_SHOW;
        $this->number_days_without_payment = self::STATUS_SHOW;
        $this->number_hours_services = self::STATUS_SHOW;
        $this->number_locations = self::STATUS_SHOW;
        $this->number_of_months = self::STATUS_SHOW;
        $this->number_right_property = self::STATUS_SHOW;
        $this->number_specialists = self::STATUS_SHOW;
        $this->object_address_leased = self::STATUS_SHOW;
        $this->one_number_shares = self::STATUS_SHOW;
        $this->organization_signatories = self::STATUS_SHOW;
        $this->pay_day = self::STATUS_SHOW;
        $this->paying_storage_month = self::STATUS_SHOW;
        $this->payment_loading = self::STATUS_SHOW;
        $this->percentage_liability = self::STATUS_SHOW;
        $this->percentage_turnover = self::STATUS_SHOW;
        $this->period_of_notice = self::STATUS_SHOW;
        $this->place_of_contract = self::STATUS_SHOW;
        $this->point_departure = self::STATUS_SHOW;
        $this->prolongation_a_treaty = self::STATUS_SHOW;
        $this->purpose_use = self::STATUS_SHOW;
        $this->registration_number_mortgage = self::STATUS_SHOW;
        $this->separat_records_goods = self::STATUS_SHOW;
        $this->signatory_contractor = self::STATUS_SHOW;
        $this->sum_payments_per_month = self::STATUS_SHOW;
        $this->two_number_of_shares = self::STATUS_SHOW;
        $this->type_extension = self::STATUS_SHOW;
        $this->type_contract = self::STATUS_SHOW;
        $this->unit_storage = self::STATUS_SHOW;
        $this->usage_purpose = self::STATUS_SHOW;
        $this->validity = self::STATUS_SHOW;
        $this->view_buyer = self::STATUS_SHOW;
        $this->view_one_shares = self::STATUS_SHOW;
        $this->view_two_shares = self::STATUS_SHOW;

        parent::afterConstruct();
    }

    /**
     * Список моделей "Вид договора".
     * @return ContractType[]
     */
    protected function findAll()
    {
        $ret = $this->SOAP->listContractTypes(array(
            'filters' => array(array()),
            'sort' => array(array())
        ));
        $ret = SoapComponent::parseReturn($ret);
        return $this->publish_list($ret, __CLASS__);
    }

    /**
     * Получение вида договора
     * @param string $id
     * @param bool $forceCached
     * @return ContractType
     * @throws CHttpException
     */
    public function findByPk($id, $forceCached = false)
    {
        $cache_id = __CLASS__ . self::PREFIX_CACHE_MODEL_PK . $id;
        if ($forceCached || ($model = Yii::app()->cache->get($cache_id)) === false) {
            $model = $this->SOAP->getContractTypes(
                array('id' => $id)
            );
            $model = SoapComponent::parseReturn($model);
            $model = $this->publish_elem(current($model), __CLASS__);
            if ($model === null)
                throw new CHttpException(404, 'Не найден вид договора');
            Yii::app()->cache->set($cache_id, $model);
        }
        $model->forceCached = $forceCached;
        return $model;
    }

    public function clearCache()
    {
        $cache = Yii::app()->cache;
        if ($this->primaryKey)
            $cache->delete(__CLASS__ . self::PREFIX_CACHE_MODEL_PK . $this->primaryKey);
        $cache->delete(__CLASS__ . self::PREFIX_CACHE_LIST_MODELS);
        $cache->delete(__CLASS__ . self::PREFIX_CACHE_LIST_NAMES);
    }

    /**
     * Сохранение
     */
    public function save()
    {
        $data = $this->getAttributes();
        if (!$this->primaryKey)
            unset($data['id']);

        unset($data['deleted']);
        unset($data['is_standart']);

        $ret = $this->SOAP->saveContractTypes(array(
            'data' => SoapComponent::getStructureElement($data),
        ));
        $this->clearCache();
        return SoapComponent::parseReturn($ret, true);
    }

    /**
     * Удаляем организацию.
     * @return bool
     */
    public function delete()
    {
        if ($this->primaryKey) {
            $ret = $this->SOAP->deleteContractTypes(array('id' => $this->primaryKey));
            $ret = SoapComponent::parseReturn($ret, false);
            if ($ret) {
                $this->clearCache();
            }
            return $ret;
        }
        return false;
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
            'contract_type_id',
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
        return array_merge(
            array(
                'id', // string
                'is_standart', // bool
                'deleted', // bool
                'name',
            ),
            $this->listAttributes()
        );
    }

    /**
     * Returns the list of attribute names of the model.
     * @return array list of attribute names.
     */
    public function attributeLabels()
    {
        return array(
            'id' => 'Номер',
            'name' => 'Название',

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
            'contract_type_id' => 'Вида договора',
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
            'type_contract' => 'Вид договора',
            'type_extension' => 'Тип пролонгации',
            'unit_storage' => 'Единица места хранения',
            'usage_purpose' => 'Цель использования',
            'validity' => 'Срок действия',
            'view_buyer' => 'Вид деятельности',
            'view_one_shares' => 'Вид 1 акций',
            'view_two_shares' => 'Вид 2 акций',
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('name', 'length', 'max' => '100'),

            array(
                'account_counterparty,
                account_payment_contract,
                additional_charge_contract,
                additional_project,
                additional_third_party,
                additional_type_contract,
                address_object,
                address_warehouse,
                allowable_amount_of_debt,
                allowable_number_of_days,
                amount_charges,
                amount_contract,
                amount_insurance,
                amount_liability,
                amount_marketing_support,
                amount_other_services,
                amount_property_insurance,
                amount_security_deposit,
                amount_transportation,
                calculated_third,
                comment,
                commission,
                contractor_id,
                contract_type_id,
                contractor_signatories,
                control_amount_debt,
                control_number_days,
                country_applicable_law,
                country_exportation,
                country_imports,
                country_service_product,
                currency_id,
                currency_payment_contract,
                date,
                description_goods,
                description_leased,
                description_work,
                destination,
                guarantee_period,
                incoterm,
                interest_book_value,
                interest_guarantee,
                interest_loan,
                invalid,
                keep_reserve_without_paying,
                kind_of_contract,
                list_documents,
                list_scans,
                list_templates,
                location_court,
                maintaining_mutual,
                maturity_date_loan,
                method_providing,
                name,
                name_title_deed,
                notice_period_contract,
                number,
                number_days_without_payment,
                number_hours_services,
                number_locations,
                number_of_months,
                number_right_property,
                number_specialists,
                object_address_leased,
                one_number_shares,
                organization_signatories,
                pay_day,
                paying_storage_month,
                payment_loading,
                percentage_liability,
                percentage_turnover,
                period_of_notice,
                place_of_contract,
                point_departure,
                prolongation_a_treaty,
                purpose_use,
                registration_number_mortgage,
                separat_records_goods,
                signatory_contractor,
                sum_payments_per_month,
                two_number_of_shares,
                type_extension,
                type_contract,
                unit_storage,
                usage_purpose,
                validity,
                view_buyer,
                view_one_shares,
                view_two_shares',

                'required'
            ),

            array(
                'account_counterparty,
                account_payment_contract,
                additional_charge_contract,
                additional_project,
                additional_third_party,
                additional_type_contract,
                address_object,
                address_warehouse,
                allowable_amount_of_debt,
                allowable_number_of_days,
                amount_charges,
                amount_contract,
                amount_insurance,
                amount_liability,
                amount_marketing_support,
                amount_other_services,
                amount_property_insurance,
                amount_security_deposit,
                amount_transportation,
                calculated_third,
                comment,
                commission,
                contractor_id,
                contract_type_id,
                contractor_signatories,
                control_amount_debt,
                control_number_days,
                country_applicable_law,
                country_exportation,
                country_imports,
                country_service_product,
                currency_id,
                currency_payment_contract,
                date,
                description_goods,
                description_leased,
                description_work,
                destination,
                guarantee_period,
                incoterm,
                interest_book_value,
                interest_guarantee,
                interest_loan,
                invalid,
                keep_reserve_without_paying,
                kind_of_contract,
                list_documents,
                list_scans,
                list_templates,
                location_court,
                maintaining_mutual,
                maturity_date_loan,
                method_providing,
                name_title_deed,
                notice_period_contract,
                number,
                number_days_without_payment,
                number_hours_services,
                number_locations,
                number_of_months,
                number_right_property,
                number_specialists,
                object_address_leased,
                one_number_shares,
                organization_signatories,
                pay_day,
                paying_storage_month,
                payment_loading,
                percentage_liability,
                percentage_turnover,
                period_of_notice,
                place_of_contract,
                point_departure,
                prolongation_a_treaty,
                purpose_use,
                registration_number_mortgage,
                separat_records_goods,
                signatory_contractor,
                sum_payments_per_month,
                two_number_of_shares,
                type_extension,
                type_contract,
                unit_storage,
                usage_purpose,
                validity,
                view_buyer,
                view_one_shares,
                view_two_shares',

                'in', 'range' => array_keys(self::getStatuses())),
        );
    }

    /**
     * Список видов договоров.
     * @param bool $forceCached.
     * @return array
     */
    public function listNames($forceCached = false)
    {
        $cache_id = __CLASS__ . self::PREFIX_CACHE_LIST_NAMES;
        if ($forceCached || ($data = Yii::app()->cache->get($cache_id)) === false) {
            $data = array();
            $elements = $this->listModels($forceCached);
            foreach ($elements as $elem) {
                $data[$elem->primaryKey] = $elem->name;
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * Список моделей видов договоров.
     * @param bool $forceCached.
     * @return array
     */
    public function listModels($forceCached = false)
    {
        $cache_id = __CLASS__ . self::PREFIX_CACHE_LIST_MODELS;
        if ($forceCached || ($data = Yii::app()->cache->get($cache_id)) === false) {
            $data = $this->findAll();
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }
}