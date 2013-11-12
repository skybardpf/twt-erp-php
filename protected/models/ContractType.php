<?php
/**
 * Вид договора.
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property bool   $is_standart
 * @property bool   $deleted
 * @property string $name
 *
 * @property string $organization_signatories
 * @property string $contractor_signatories
 */
class ContractType extends ContractAbstract
{
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
     * @param string $attribute
     * @return bool
     */
    public function isShowAttribute($attribute)
    {
        return (isset($this->$attribute) && ($this->$attribute == self::STATUS_REQUIRED || $this->$attribute == self::STATUS_SHOW));
    }

    /**
     * @param string $attribute
     * @return bool
     */
    public function isRequiredAttribute($attribute)
    {
        return (isset($this->$attribute) && ($this->$attribute == self::STATUS_REQUIRED));
    }

    /**
     * Список названий
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
     * Список моделей.
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
        $this->le_id = self::STATUS_SHOW;
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
        $this->place_contract = self::STATUS_SHOW;
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
        if (empty($id))
            throw new CHttpException(500, 'В договоре не указан идентификатор вида договора');

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
     * @return array
     */
    public function attributeNames()
    {
        return array_merge(
            array(
                'id', // string
                'is_standart', // bool
                'deleted', // bool
                'name', // string
            ),
            parent::attributeNames()
        );
    }

    /**
     * Returns the list of attribute names of the model.
     * @return array list of attribute names.
     */
    public function attributeLabels()
    {
        return array_merge(
            array(
                'id' => 'Номер',
                'name' => 'Название',
            ),
            parent::attributeLabels()
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        $attributes = implode(' ', $this->listAttributes());
        return array(
            array('name', 'length', 'max' => '100'),
            array($attributes, 'required'),
            array($attributes, 'in', 'range' => array_keys(self::getStatuses())),
        );
    }
}