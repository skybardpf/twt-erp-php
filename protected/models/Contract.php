<?php
/**
 * Модель, реализующая сущность договора организации.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string     $id
 * @property string     $name
 * @property boolean    $deleted
 * @property boolean    $invalid
 * @property string     $id_yur
 * @property string     $le_id
 * @property string     $responsible
 * @property string     $currency
 * @property integer    $dogovor_summ
 * @property array      $signatory
 * @property array      $signatory_contr
 * @property string     $role_ur_face
 *
 * @property string     $json_signatory_contractor      private
 * @property string     $json_signatory                 private
 */
class Contract extends SOAPModel
{
    const PREFIX_CACHE_LIST_MODELS = '_list_models_';

    const STATUS_INVALID = 1;
    const STATUS_VALID = 2;

    const ROLE_BUYER = 'Продавец';
    const ROLE_CONTRACTOR = 'Поставщик';

    public $json_organization_signatories;
    public $json_contractor_signatories;

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
        $cache_id = __CLASS__ . self::PREFIX_CACHE_LIST_MODELS. $organizationId;
        if ($forceCached || ($data = Yii::app()->cache->get($cache_id)) === false){
            $data = $this->where('id_yur', $organizationId)->findAll();
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
     */
    public function findByPk($id, $forceCached = false)
    {
        $cache_id = __CLASS__ . self::PREFIX_CACHE_MODEL_PK. $id;
        if ($forceCached || ($model = Yii::app()->cache->get($cache_id)) === false){
            $ret = $this->SOAP->getContracts(array('id' => $id));
            $ret = SoapComponent::parseReturn($ret);
            $model = $this->publish_elem(current($ret), __CLASS__);
            Yii::app()->cache->set($cache_id, $model);
        }
        return $model;
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
        unset($data['character']);
        unset($data['json_signatory']);
        unset($data['json_signatory_contractor']);

        unset($data['scan']);
        unset($data['orig_doc']);

        $data['invalid'] = $data['invalid'] == 1 ? true : false;
        $data['signatory_contr'] = implode(',', $data['signatory_contr']);
        $data['signatory'] = implode(',', $data['signatory']);
        $data['role_ur_face'] = ($data['role_ur_face'] == self::ROLE_CONTRACTOR) ? Contractor::TYPE : Organization::TYPE;

        $ret = $this->SOAP->saveContract(array(
            'data' => SoapComponent::getStructureElement($data)
        ));
        $ret = SoapComponent::parseReturn($ret, false);
        if (!ctype_digit($ret)) {
            throw new CHttpException(500, 'Ошибка при сохранении договора.');
        }

        /**
         * Сбрасываем кеш.
         */
        $this->clearCache();

        return $ret;
    }

    /**
     * Сбрасываем кеш по данному договору и для списка договоров.
     */
    public function clearCache()
    {
        if ($this->primaryKey) {
            Yii::app()->cache->delete(__CLASS__ . '_' . $this->primaryKey);
        }
        Yii::app()->cache->delete(__CLASS__ . '_list_org_id_' . $this->id_yur);
    }

    /**
     * @return array list of attribute names.
     */
    public function attributeNames()
    {
        return array(
            'id',
            'name',
            'number',
            'deleted',
//            'character' => 'Характер договора',
            'date',
            'date_expire',
            'date_infomation' => 'Уведомления об окончании действия договора за',

            'responsible',
            'place_contract_id',
            'contract_type_id',
            'prolongation_type',
            'sum_month',
            'currency',
            'sum',
            'place_court_id',
            'role',
            'organization_signatories',
            'contractor_signatories',
            'third_parties_signatories',

            'list_documents',
            'list_scans',
            'comment',

            'id_yur',
            'contractor_id',
        );
    }

    /**
     * Returns the list of attribute names of the model.
     * @return array list of attribute names.
     */
    public function attributeLabels()
    {
        return array(
            'id' => '#',
            'name' => 'Наименование',
            'number' => 'Номер',
            'deleted' => 'Удален',
//            'invalid' => 'Статус договора',

//            'character' => 'Характер договора',
            'date' => 'Дата заключения',
            'date_expire' => 'Действителен до',
//            'date_infomation' => 'Уведомления об окончании действия договора за',

            'responsible' => 'Ответственный по договору',
            'place_contract_id' => 'Место заключения', // Справочник допМестоЗаключенияДоговора
            'contract_type_id' => 'Вид договора', // Справочник .допВидыДоговоров
            'prolongation_type' => 'Тип пролонгация',
            'sum_month' => 'Сумма ежемесячного платежа',
            'currency' => 'Валюта',
            'sum' => 'Сумма договора',
            'place_court_id' => 'Место судебной инстанции',

            'role' => 'Роль юр. лица',

            'organization_signatories' => 'Подписант',
            'contractor_signatories' => 'Подписант контрагента',
            'third_parties_signatories' => 'Подписант 3-й стороны',

            'list_documents' => 'Оригинальный документ',
            'list_scans' => 'Сканы',
            'comment' => 'Коментарий',

            'id_yur' => 'Идентификатор юр. лица',
            'contractor_id' => 'Контрагент', // Справочник Контрагенты, Организации

//            'json_signatory_contractor' => '', // private
//            'json_signatory' => '', // private
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        return array(
            array('name', 'required'),
//            array('name', 'length', 'max' => 25),

            array('number', 'required'),
//            array('name', 'length', 'max' => 25),

            array('contract_type_id', 'required'),
            array('contract_type_id', 'in', 'range' => array_keys(self::getTypes())),

            array('contractor_id', 'required'),
            array('contractor_id', 'in', 'range' => array_keys(Contractor::model()->getListNames($this->forceCached))),

            array('date, expire', 'required'),
            array('date, expire', 'date', 'format' => 'yyyy-MM-dd'),

//            array('invalid', 'required'),
//            array('invalid', 'in', 'range' => array(1, 2)),

            array('prolongation_type', 'required'),
            array('prolongation_type', 'in', 'range' => array_keys(self::getProlongationTypes())),

            array('currency', 'required'),
            array('currency', 'in', 'range' => array_keys(Currency::model()->listNames($this->forceCached))),

            array('responsible', 'required'),
            array('responsible', 'in', 'range' => array_keys(Individual::model()->listNames($this->forceCached))),

            array('sum', 'required'),
            array('sum', 'numerical', 'integerOnly' => true, 'min' => 0, 'max' => '9999999999999'),
            array('sum_month', 'numerical', 'integerOnly' => true, 'min' => 0, 'max' => '9999999999999'),
//            array('date_infomation', 'numerical', 'integerOnly' => true, 'min' => 0, 'max' => '999'),

            array('json_organization_signatories, json_contractor_signatories', 'validJson'),

            array('role', 'required'),
            array('role', 'in', 'range' => array_keys(self::getRoles())),

            array('contractor_signatories', 'validSignatory'),
            array('signatory', 'validSignatory'),

            array('place_contract_id, place_court_id, comment', 'safe')
        );
    }

    /**
     * Список договоров. Формат [key => name].
     * Результат сохранеятся в кеш.
     * @return array
     */
    public static function getValues()
    {
        $cache_id = __CLASS__ . '_list';
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false) {
            $elements = self::model()->findAll();
            $data = array();
            if ($elements) {
                foreach ($elements as $elem) {
                    $data[$elem->getprimaryKey()] = $elem->name;
                }
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

    /**
     * @return array Возвращает список видов договора. Формат [key => name].
     */
    public static function getTypes()
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
    public function validJson($attribute)
    {
        if (CJSON::decode($this->$attribute) === null) {
            $this->addError($attribute, 'Неправильная JSON строка.');
        }
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
        } elseif (count($this->$attribute) > 2) {
            $this->addError($attribute, 'Выберите не более 2-х подписантов.');
        }
    }
}