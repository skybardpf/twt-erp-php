<?php
/**
 * Вид договора.
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $name
 * @property bool   $is_standart
 * @property bool   $deleted
 *
 * @property string $contractor
 * @property string $title
 * @property string $number
 * @property string $date
 * @property string $date_expire
 * @property string $contract_status
 * @property string $place_of_contract
 * @property string $type_of_prolongation
 * @property string $notice_end_of_contract
 * @property string $currency
 * @property string $sum_contract
 * @property string $sum_month
 * @property string $responsible_contract
 * @property string $role
 * @property string $organization_signatories
 * @property string $contractor_signatories
 * @property string $third_parties_signatories
 * @property string $place_of_court
 * @property string $comment
 * @property string $scans
 * @property string $original_documents
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
        $this->contractor = self::STATUS_SHOW;
        $this->title = self::STATUS_SHOW;
        $this->number  = self::STATUS_SHOW;
        $this->date = self::STATUS_SHOW;
        $this->date_expire = self::STATUS_SHOW;
        $this->contract_status = self::STATUS_SHOW;
        $this->place_of_contract = self::STATUS_SHOW;
        $this->type_of_prolongation = self::STATUS_SHOW;
        $this->notice_end_of_contract = self::STATUS_SHOW;
        $this->currency = self::STATUS_SHOW;
        $this->sum_contract = self::STATUS_SHOW;
        $this->sum_month = self::STATUS_SHOW;
        $this->responsible_contract = self::STATUS_SHOW;
        $this->role = self::STATUS_SHOW;
        $this->organization_signatories = self::STATUS_SHOW;
        $this->contractor_signatories = self::STATUS_SHOW;
        $this->third_parties_signatories = self::STATUS_SHOW;
        $this->place_of_court = self::STATUS_SHOW;
        $this->comment = self::STATUS_SHOW;
        $this->scans = self::STATUS_SHOW;
        $this->original_documents = self::STATUS_SHOW;

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
            if ($ret){
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
        return array(
            'id', // string
            'name', // string
            'is_standart', // bool
            'deleted', // bool

            'contractor', // string
            'title', // string
            'number', // string
            'date', // string
            'date_expire', // string
            'contract_status', // string
            'place_of_contract', // string
            'type_of_prolongation', // string
            'notice_end_of_contract', // string
            'currency', // string
            'sum_contract', // string
            'sum_month', // string
            'responsible_contract', // string
            'role', // string
            'organization_signatories', // string
            'contractor_signatories', // string
            'third_parties_signatories', // string
            'place_of_court', // string
            'comment', // string
            'scans', // string
            'original_documents', // string
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

            'contractor' => 'Контрагент',
            'title' => 'Наименование',
            'number' => 'Номер',
            'date' => 'Дата заключения',
            'date_expire' => 'Действителен до',
            'contract_status' => 'Статус договора',
            'place_of_contract' => 'Место заключения',
            'type_of_prolongation' => 'Тип пролонгации',
            'notice_end_of_contract' => 'Уведомление об окончании действия договора (дней)',
            'currency' => 'Валюта',
            'sum_contract' => 'Сумма договора',
            'sum_month' => 'Сумма ежемесячного платежа',
            'responsible_contract' => 'Ответственный по договору',
            'role' => 'Роль',
            'organization_signatories' => 'Подписанты юр. лица',
            'contractor_signatories' => 'Подписанты контрагента',
            'third_parties_signatories' => 'Подписанты 3-й стороны',
            'place_of_court' => 'Место судебной инстанции',
            'comment' => 'Комментарий',
            'scans' => 'Сканы',
            'original_documents' => 'Оригинальные документы',
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
	public function rules()
    {
        $status = array_keys(self::getStatuses());
		return array(
			array('name', 'required'),
			array('name', 'length', 'max' => '100'),

            array('contractor, title, number, date, date_expire, contract_status, place_of_contract', 'required'),
            array('type_of_prolongation, notice_end_of_contract, currency, sum_contract, sum_month', 'required'),
            array('responsible_contract, role, organization_signatories, contractor_signatories, third_parties_signatories', 'required'),
            array('place_of_court, comment, scans, original_documents', 'required'),

            array('contractor, title, number, date, date_expire, contract_status, place_of_contract', 'in', 'range' => $status),
            array('type_of_prolongation, notice_end_of_contract, currency, sum_contract, sum_month', 'in', 'range' => $status),
            array('responsible_contract, role, organization_signatories, contractor_signatories, third_parties_signatories', 'in', 'range' => $status),
            array('place_of_court, comment, scans, original_documents', 'in', 'range' => $status),
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