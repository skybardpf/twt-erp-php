<?php
/**
 * Модель, реализующая сущность договора организации.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string     $id
 * @property string     $name
 * @property boolean    $deleted
 * @property string     $additional_type_contract
 *
 * @property array      $organization_signatories
 * @property array      $contractor_signatories
 *
 * @property UploadDocument $uploadDocument
 * @method  bool upload(string $path, CUploadedFile $file)
 * @method  void removeFiles(string $path, array $files)
 * @method  void moveFiles(string $source, string $destination, array $files)
 */
class Contract extends ContractAbstract
{
    const STATUS_INVALID = 1;
    const STATUS_VALID = 2;

    const ROLE_BUYER = 'Продавец';
    const ROLE_CONTRACTOR = 'Поставщик';

    public $json_organization_signatories;
    public $json_contractor_signatories;
    public $json_exists_documents;
    public $json_exists_scans;

    public $upload_scans = array();
    public $upload_documents = array();

    /**
     * Инициализируем переменные
     */
    protected function afterConstruct()
    {
        /*
        $this->_rules = array(
            array('name', 'required'),
            array('name', 'length', 'max' => 50),

            array(
                'organization_signatories,
                contractor_signatories',

                'validSignatory'
            ),

            array('
                json_organization_signatories,
                json_contractor_signatories
                json_exists_documents
                json_exists_scans',

                'validJson'
            ),

            array('validity,
                date,
                maturity_date_loan,
                pay_day,
                period_of_notice,',

                'date', 'format' => 'yyyy-MM-dd'
            ),

            array('guarantee_period,
                notice_period_contract,
                number_specialists,
                one_number_shares,
                paying_storage_month,
                payment_loading,
                percentage_liability,
                percentage_turnover,
                prolongation_a_treaty,
                sum_payments_per_month,
                two_number_of_shares,',

                'numerical', 'integerOnly' => true, 'min' => 0, 'max' => 9999999999999
            ),

            array('
                control_amount_debt,
                control_number_days,
                invalid,
                keep_reserve_without_paying,
                separat_records_goods,',

                'boolean'
            ),

            array('address_object,
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
                description_goods,
                description_leased,
                description_work,
                destination,
                interest_book_value,
                interest_guarantee,
                interest_loan,
                location_court,
                method_providing,
                name_title_deed,
                number,
                number_days_without_payment,
                number_hours_services,
                number_locations,
                number_of_months,
                number_right_property,
                object_address_leased,
                view_buyer,
                view_one_shares,
                view_two_shares,
                unit_storage,
                usage_purpose,
                purpose_use,
                registration_number_mortgage,
                place_of_contract,
                point_departure,',

                'length', 'max' => 100
            ),

            array('country_applicable_law,
                country_exportation,
                country_imports,
                country_service_product',

                'in', 'range' => array_keys(Country::model()->listNames($this->forceCached))
            ),

            array('currency_id, currency_payment_contract',
                'in', 'range' => array_keys(Currency::model()->listNames($this->forceCached))
            ),

            array('type_extension', 'in', 'range' => array_keys(Contract::getProlongationTypes())),
            array('type_contract', 'in', 'range' => array_keys(Contract::getTypesAgreementsAccounts())),

            array('maintaining_mutual', 'in', 'range' => array_keys(Contract::getMaintainingMutual())),

            array('kind_of_contract', 'in', 'range' => array_keys(Contract::getKindsOfContract())),

            array('incoterm', 'in', 'range' => array_keys(Incoterm::model()->listNames($this->forceCached))),

            array('contractor_id', 'in', 'range' => array_keys(Organization::model()->getListNames($this->forceCached))),

            array('additional_type_contract', 'in', 'range' => array_keys(ContractType::model()->listNames($this->forceCached))),

            array('additional_third_party', 'in', 'range' => array_keys(Contractor::model()->getListNames($this->forceCached))),

            array('additional_project', 'in', 'range' => array_keys(Project::model()->listNames($this->forceCached))),

            array('additional_charge_contract,
                signatory_contractor,',

                'in', 'range' => array_keys(Individual::model()->listNames($this->forceCached))
            ),

            array('account_counterparty, account_payment_contract', 'in', 'range' => array_keys(SettlementAccount::model()->listNames($this->forceCached))),

            array('place_of_contract', 'in', 'range' => array_keys(ContractPlace::model()->listNames($this->getForceCached()))),
        );
*/

        $this->country_service_product = 'Null';
        $this->country_imports = 'Null';
        $this->country_exportation = 'Null';
        $this->country_applicable_law = 'Null';
        $this->currency_payment_contract = 'Null';
        $this->type_extension = 'Null';

        $this->kind_of_contract = 'Null';
        $this->incoterm = 'Null';
        $this->additional_third_party = 'Null';
        $this->additional_project = 'Null';
        $this->additional_charge_contract = 'Null';
        $this->signatory_contractor = 'Null';
        $this->account_counterparty = 'Null';
        $this->place_contract = 'Null';

        $this->currency_id = '643'; // RUB
        $this->type_contract = 'Прочее';
        $this->maintaining_mutual = 'ПоДоговоруВЦелом';

        $this->organization_signatories = array();
        $this->contractor_signatories = array();
        $this->list_documents = array();
        $this->list_scans = array();

        $this->attachBehaviors($this->behaviors());
        parent::afterConstruct();
    }

    /**
     * Подключаем поведение для загрузки файлов.
     * @return array
     */
    public function behaviors()
    {
        return array(
            'uploadDocument' => array(
                'class' => 'application.components.Behavior.UploadDocument',
                'uploadDir' => Yii::getPathOfAlias(Yii::app()->params->uploadDocumentDir),
            ),
        );
    }

    /**
     * @static
     * @param string $className
     * @return Contract
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    /**
     * @param string $organizationId
     * @param bool $forceCached
     * @return Contract[]
     * @throws CHttpException
     */
    public function listModels($organizationId, $forceCached = false)
    {
        $cache_id = __CLASS__ . self::PREFIX_CACHE_LIST_MODELS . $organizationId;
        if ($forceCached || ($data = Yii::app()->cache->get($cache_id)) === false) {
            $data = $this->where('contractor_id', $organizationId)->findAll();
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * Список договоров.
     * @return Contract[]
     */
    protected function findAll()
    {
        $filters = SoapComponent::getStructureElement($this->where);
        if (!$filters)
            $filters = array(array());
        $request = array('filters' => $filters, 'sort' => array(array()));
        $ret = $this->SOAP->listContracts($request);
        $ret = SoapComponent::parseReturn($ret);
        return $this->publish_list($ret, __CLASS__);
    }

    /**
     * Получить договор по его номеру.
     *
     * @param string $id
     * @param bool $forceCached
     * @return Contract
     * @throws CHttpException
     */
    public function findByPk($id, $forceCached = false)
    {
        $cache_id = __CLASS__ . self::PREFIX_CACHE_MODEL_PK . $id;
        if ($forceCached || ($model = Yii::app()->cache->get($cache_id)) === false) {
            $ret = $this->SOAP->getContracts(array('id' => $id));
            $ret = SoapComponent::parseReturn($ret);
            $model = $this->publish_elem(current($ret), __CLASS__);
            if ($model === null)
                throw new CHttpException(404, 'Не найден договор');
            Yii::app()->cache->set($cache_id, $model);
        }
        return $model;
    }

    /**
     * Создаем правила
     */
    public function makeRules(ContractType $contractType)
    {
        $safe = array();

        $data = array(
            array(
                'validator' => 'validSignatory',
                'attributes' => array(
                    'organization_signatories',
                    'contractor_signatories'
                ),
                'params' => array(),
            ),

            array(
                'validator' => 'date',
                'attributes' => array(
                    'validity',
                    'date',
                    'maturity_date_loan',
                    'pay_day',
                    'period_of_notice',
                ),
                'params' => array(
                    'format' => 'yyyy-MM-dd'
                ),
            ),

            array(
                'validator' => 'numerical',
                'attributes' => array(
                    'guarantee_period',
                    'notice_period_contract',
                    'number_specialists',
                    'one_number_shares',
                    'paying_storage_month',
                    'payment_loading',
                    'percentage_liability',
                    'percentage_turnover',
                    'prolongation_a_treaty',
                    'sum_payments_per_month',
                    'two_number_of_shares',
                ),
                'params' => array(
                    'integerOnly' => true,
                    'min' => 0,
                    'max' => 9999999999999,
                ),
            ),

            array(
                'validator' => 'boolean',
                'attributes' => array(
                    'control_amount_debt',
                    'control_number_days',
                    'invalid',
                    'keep_reserve_without_paying',
                    'separat_records_goods',
                ),
                'params' => array(),
            ),

            array(
                'validator' => 'length',
                'attributes' => array(
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
                    'description_goods',
                    'description_leased',
                    'description_work',
                    'destination',
                    'interest_book_value',
                    'interest_guarantee',
                    'interest_loan',
                    'location_court',
                    'method_providing',
                    'name_title_deed',
                    'number',
                    'number_days_without_payment',
                    'number_hours_services',
                    'number_locations',
                    'number_of_months',
                    'number_right_property',
                    'object_address_leased',
                    'view_buyer',
                    'view_one_shares',
                    'view_two_shares',
                    'unit_storage',
                    'usage_purpose',
                    'purpose_use',
                    'registration_number_mortgage',
                    'place_contract',
                    'point_departure',
                ),
                'params' => array(
                    'max' => 100
                ),
            ),

            array(
                'validator' => 'in',
                'attributes' => array(
                    'country_applicable_law',
                    'country_exportation',
                    'country_imports',
                    'country_service_product',
                ),
                'params' => array(
                    'range' => array_keys(Country::model()->listNames($this->forceCached))
                ),
            ),

            array(
                'validator' => 'in',
                'attributes' => array(
                    'currency_id',
                    'currency_payment_contract',
                ),
                'params' => array(
                    'range' => array_keys(Currency::model()->listNames($this->forceCached))
                ),
            ),
            array(
                'validator' => 'in',
                'attributes' => array(
                    'type_extension',
                ),
                'params' => array(
                    'range' => array_keys(Contract::getProlongationTypes())
                ),
            ),
            array(
                'validator' => 'in',
                'attributes' => array(
                    'type_contract',
                ),
                'params' => array(
                    'range' => array_keys(Contract::getTypesAgreementsAccounts())
                ),
            ),
            array(
                'validator' => 'in',
                'attributes' => array(
                    'type_contract',
                ),
                'params' => array(
                    'range' => array_keys(Contract::getTypesAgreementsAccounts())
                ),
            ),
            array(
                'validator' => 'in',
                'attributes' => array(
                    'maintaining_mutual',
                ),
                'params' => array(
                    'range' => array_keys(Contract::getMaintainingMutual())
                ),
            ),
            array(
                'validator' => 'in',
                'attributes' => array(
                    'kind_of_contract',
                ),
                'params' => array(
                    'range' => array_keys(Contract::getKindsOfContract())
                ),
            ),
            array(
                'validator' => 'in',
                'attributes' => array(
                    'incoterm',
                ),
                'params' => array(
                    'range' => array_keys(Incoterm::model()->listNames($this->forceCached))
                ),
            ),
            array(
                'validator' => 'in',
                'attributes' => array(
                    'contractor_id',
                ),
                'params' => array(
                    'range' => array_keys(Organization::model()->getListNames($this->forceCached))
                ),
            ),
            array(
                'validator' => 'in',
                'attributes' => array(
                    'additional_third_party',
                ),
                'params' => array(
                    'range' => array_keys(Contractor::model()->getListNames($this->forceCached))
                ),
            ),
            array(
                'validator' => 'in',
                'attributes' => array(
                    'additional_project',
                ),
                'params' => array(
                    'range' => array_keys(Project::model()->listNames($this->forceCached))
                ),
            ),
            array(
                'validator' => 'in',
                'attributes' => array(
                    'additional_charge_contract',
                    'signatory_contractor',
                ),
                'params' => array(
                    'range' => array_keys(Individual::model()->listNames($this->forceCached))
                ),
            ),
            array(
                'validator' => 'in',
                'attributes' => array(
                    'account_counterparty',
                    'account_payment_contract',
                ),
                'params' => array(
                    'range' => array_keys(SettlementAccount::model()->listNames($this->forceCached))
                ),
            ),
            array(
                'validator' => 'in',
                'attributes' => array(
                    'place_contract',
                ),
                'params' => array(
                    'range' => array_keys(ContractPlace::model()->listNames($this->getForceCached()))
                ),
            ),
        );
        foreach ($data as $v){
            foreach ($v['attributes'] as $attr){
                if (!$contractType->isShowAttribute($attr)){
                    $safe[] = $attr;
                } else {
                    $this->validatorList->add(
                        CValidator::createValidator($v['validator'], $this, $attr, $v['params'])
                    );
                    if ($contractType->isRequiredAttribute($attr)){
                        $this->validatorList->add(
                            CValidator::createValidator('required', $this, $attr)
                        );
                    }
                }
            }
        }

        $this->validatorList->add(
            CValidator::createValidator('safe', $this, implode(',', $safe))
        );
    }

    /**
     * Удаление договора
     * @return bool Успешность операции удаления
     */
    public function delete()
    {
        if ($pk = $this->getprimaryKey()) {
            $ret = $this->SOAP->deleteContract(array('id' => $pk));

            /**
             * Сбрасываем кеш.
             */
            $this->clearCache();

            return $ret->return;
        }
        return false;
    }

    /**
     *  Редактирование/создание договора.
     * @return string Идентификатор созданой/отредактированой записи
     * @throws CHttpException
     */
    public function save()
    {
        $data = $this->getAttributes();

        if (!$this->primaryKey) {
            unset($data['id']);
        }
        unset($data['deleted']);
        unset($data['json_organization_signatories']);
        unset($data['json_contractor_signatories']);
        unset($data['json_exists_files']);
        unset($data['json_exists_scans']);

        unset($data['list_scans']);
        unset($data['list_documents']);
        unset($data['list_templates']);

        unset($data['organization_signatories']);
        unset($data['contractor_signatories']);

        $list_scans = array();
        $list_files = array();
        $id = ($this->primaryKey) ? $this->primaryKey : 'tmp_id';
        $path = Yii::app()->user->getId() . DIRECTORY_SEPARATOR . __CLASS__ . DIRECTORY_SEPARATOR . $id;
        $path_scans = $path . DIRECTORY_SEPARATOR . MDocumentCategory::SCAN;
        $path_files = $path . DIRECTORY_SEPARATOR . MDocumentCategory::FILE;
        foreach ($this->upload_scans as $f) {
            if ($this->upload($path_scans, $f)) {
                $list_scans[] = $f->name;
            }
        }
        foreach ($this->upload_documents as $f) {
            if ($this->upload($path_files, $f)) {
                $list_files[] = $f->name;
            }
        }
        $list_files = array_merge($list_files, $this->list_documents);
        $list_scans = array_merge($list_scans, $this->list_scans);
        $list_files = (empty($list_files)) ? array('Null') : $list_files;
        $list_scans = (empty($list_scans)) ? array('Null') : $list_scans;

        $data['responsible_contract_id'] = "0000000067";

        $ret = $this->SOAP->saveContract(array(
            'data' => SoapComponent::getStructureElement($data),
            'list_documents' => $list_files,
            'list_scans' => $list_scans,
            'organization_signatories' => $this->organization_signatories,
            'contractor_signatories' => $this->contractor_signatories,
//            'list_templates' => $this->list_templates,
        ));
        $ret = SoapComponent::parseReturn($ret, false);

        /**
         * 1. Возникли ошибки - удаляем все документы из временной диретории.
         * 2. Все нормально - переносим документы из временной папки в папку
         * созданного документа ($this->primaryKey).
         */
        if (!$this->primaryKey) {
            try {
                if (!ctype_digit($ret)) {
                    $this->removeFiles($path_files, $list_files);
                    $this->removeFiles($path_scans, $list_scans);
                } else {
                    $path = Yii::app()->user->getId()
                        . DIRECTORY_SEPARATOR . __CLASS__
                        . DIRECTORY_SEPARATOR . $ret;
                    $dest_scans = $path . DIRECTORY_SEPARATOR . MDocumentCategory::SCAN;
                    $dest_files = $path . DIRECTORY_SEPARATOR . MDocumentCategory::FILE;

                    $this->moveFiles($path_files, $dest_files, $list_files);
                    $this->moveFiles($path_scans, $dest_scans, $list_scans);
                }
            } catch (UploadDocumentException $e) {
                Yii::log($e->getMessage(), cLogger::LEVEL_ERROR);
                $this->addError('id', $e->getMessage());
            }
        }
        $this->clearCache();
        return $ret;
    }

    /**
     * @return array
     */
    public function attributeNames()
    {
        return array_merge(
            array(
                'id', // string
                'deleted', // bool
                'name', // string
                'le_id', // string
                'additional_type_contract',
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
                'name' => 'Название',
                'le_id' => 'Владелец',
            ),
            parent::attributeLabels()
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('name', 'required'),
            array('name', 'length', 'max' => 50),

            array('le_id', 'required'),
            array('le_id', 'in', 'range' => array_keys(Contractor::model()->getListNames($this->forceCached))),

            array('
                json_organization_signatories,
                json_contractor_signatories
                json_exists_documents
                json_exists_scans',

                'validJson'
            ),

            array(
                'additional_type_contract',
                'in', 'range' => array_keys(ContractType::model()->listNames($this->forceCached))
            ),
        );
    }

    /**
     * @return array Возвращает список видов договора контрагентов. Формат [key => name].
     */
    public static function getTypesAgreementsAccounts()
    {
        return array(
            'СПоставщиком' => 'С поставщиком',
            'СПокупателем' => 'С покупателем',
            'СКомитентом' => 'С комитентом',
            'СКомиссионером' => 'С комиссионером',
            'Прочее' => 'Прочее',
        );
    }

    /**
     * @return array Возвращает список типов прологации. Формат [key => name].
     */
    public static function getProlongationTypes()
    {
        return array(
            'Нет' => 'Нет',
            'Автоматическая' => 'Автоматическая',
            'ПоСоглашениюСторон' => 'По соглашению сторон',
            'Перезаключение' => 'Перезаключение',
        );
    }

    /**
     * Список видов условий договора.
     * @return array
     */
    public static function getKindsOfContract()
    {
        return array(
            'БезДополнительныхУсловий' => 'Без дополнительных условий',
            'СДополнительнымиУсловиями' => 'С дополнительными условиями',
        );
    }

    /**
     * Список ведений взаиморасчетов
     * @return array
     */
    public static function getMaintainingMutual()
    {
        return array(
            'ПоДоговоруВЦелом' => 'По договору в целом',
            'ПоЗаказам' => 'По заказам',
            'ПоСчетам' => 'По счетам',
        );
    }

    /**
     * @return array Возвращает список ролей. Формат [key => name].
     */
    public static function getRoles()
    {
        return array(
            self::ROLE_CONTRACTOR => self::ROLE_CONTRACTOR,
            self::ROLE_BUYER => self::ROLE_BUYER,
        );
    }

    /**
     * @param string $attribute
     */
    public function validSignatory($attribute)
    {
        if (!is_array($this->$attribute)) {
            $this->addError($attribute, 'Передан неправильный формат данных.');
        } elseif (empty($this->$attribute)) {
            $this->addError($attribute, 'Должен быть выбран хотя бы один подписант.');
        } /*elseif (count($this->$attribute) > 2) {
            $this->addError($attribute, 'Выберите не более 2-х подписантов.');
        }*/
    }
}