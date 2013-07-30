<?php
/**
 * Модель, реализующая сущность договора организации.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
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
    const STATUS_INVALID = 1;
    const STATUS_VALID = 2;

    const ROLE_BUYER = 'Продавец';
    const ROLE_CONTRACTOR = 'Поставщик';

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
     * Список договоров.
     * @return Contract[]
     */
    public function findAll()
    {
        $filters = SoapComponent::getStructureElement($this->where);
        if (!$filters) {
            $filters = array(array());
        }
        $request = array('filters' => $filters, 'sort' => array($this->order));
        $ret = $this->SOAP->listContracts($request);
        $ret = SoapComponent::parseReturn($ret);
        return $this->publish_list($ret, __CLASS__);
    }

    /**
     * Получить договор по его номеру.
     *
     * @param string $id
     * @return Contract
     */
    public function findByPk($id)
    {
        $ret = $this->SOAP->getContract(array('id' => $id));
        $ret = SoapComponent::parseReturn($ret);
        return $this->publish_elem(current($ret), __CLASS__);
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
     *  @return string Идентификатор созданой/отредактированой записи
     *  @throws CHttpException
     */
    public function save()
    {
        $data = $this->getAttributes();

        if (!$this->primaryKey){
            unset($data['id']);
        }
        unset($data['deleted']);
        unset($data['character']);
        unset($data['json_signatory']);
        unset($data['json_signatory_contractor']);

        unset($data['scan']);
        unset($data['orig_doc']);

        $data['invalid'] = $data['invalid'] == 1 ? true : false;
        $data['signatory_contr'] = '0000000001';
        $data['signatory'] = '0000000001';
        if ($data['role_ur_face'] == self::ROLE_CONTRACTOR){
            $data['role_ur_face'] = 'Контрагент';
        } else {
            $data['role_ur_face'] = 'Организация';
        }

        $ret = $this->SOAP->saveContract(array(
            'data' => SoapComponent::getStructureElement($data)
        ));
        $ret = SoapComponent::parseReturn($ret, false);
        if (!ctype_digit($ret)){
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
        if ($this->primaryKey){
            Yii::app()->cache->delete(__CLASS__.'_'.$this->primaryKey);
        }
        Yii::app()->cache->delete(__CLASS__.'_list_org_id_'.$this->id_yur);
    }

    /**
     * Returns the list of attribute names of the model.
     * @return array list of attribute names.
     */
    public function attributeLabels()
    {
        return array(
            'id'                => '#',
            'name'              => 'Наименование',
            'number'            => 'Номер',
            'deleted'           => 'Удален',
            'invalid'           => 'Статус договора',

            'character'         => 'Характер договора',
            'date'              => 'Дата заключения',
            'expire'            => 'Действителен до',
            'date_infomation'   => 'Уведомления об окончании действия договора за',

            'responsible'       => 'Ответственный по договору',
            'place_contract'    => 'Место заключения', // Справочник допМестоЗаключенияДоговора
            'typ_doc'           => 'Вид договора',    // Справочник .допВидыДоговоров
            'prolongation_type' => 'Тип пролонгация',
            'everymonth_summ'   => 'Сумма ежемесячного платежа',
            'currency'          => 'Валюта',
            'dogovor_summ'      => 'Сумма договора',
            'place_court'       => 'Место судебной инстанции',

            'role_ur_face'      => 'Роль юр. лица',
            'signatory'         => 'Подписант',
            'signatory_contr'   => 'Подписант контрагента', // Справочник Физ. Лица

            'orig_doc'          => 'Оригинальный документ',
            'scan'              => 'Сканы',
            'comment'           => 'Коментарий',

            'id_yur'            => 'Идентификатор юр. лица',
            'le_id'             => 'Контрагент',  // Справочник Контрагенты, Организации

            'json_signatory_contractor' => '',  // private
            'json_signatory' => '',             // private
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

            array('typ_doc', 'required'),
            array('typ_doc', 'in', 'range' => array_keys(self::getTypes())),

            array('le_id', 'required'),
            array('le_id', 'in', 'range' => array_keys(Contractor::model()->getListNames())),

            array('date, expire', 'required'),
            array('date, expire', 'date', 'format' => 'yyyy-MM-dd'),

//            array('invalid', 'required'),
            array('invalid', 'in', 'range' => array(1,2)),

            array('prolongation_type', 'required'),
            array('prolongation_type', 'in', 'range' => array_keys(self::getProlongationTypes())),

            array('currency', 'required'),
            array('currency', 'in', 'range' => array_keys(Currencies::getValues())),

            array('responsible', 'required'),
            array('responsible', 'in', 'range' => array_keys(Individuals::getValues())),

            array('dogovor_summ', 'required'),
            array('dogovor_summ', 'numerical', 'integerOnly' => true, 'min' => 0, 'max'=>'9999999999999'),
            array('everymonth_summ', 'numerical', 'integerOnly' => true, 'min' => 0, 'max'=>'9999999999999'),
            array('date_infomation', 'numerical', 'integerOnly' => true, 'min' => 0, 'max'=>'999'),

            array('json_signatory_contractor, json_signatory', 'validJson'),

            array('role_ur_face', 'required'),
            array('role_ur_face', 'in', 'range' => array_keys(self::getRoles())),

            array('signatory_contr', 'validSignatory'),
            array('signatory', 'validSignatory'),

            array('place_contract, place_court, comment', 'safe')
        );
    }

    /**
     * Список договоров. Формат [key => name].
     * Результат сохранеятся в кеш.
     * @return array
     */
    public static function getValues()
    {
        $cache_id = __CLASS__. '_list';
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
        if (CJSON::decode($this->$attribute) === null){
            $this->addError($attribute, 'Неправильная JSON строка.');
        }
    }

    /**
     * @param string $attribute
     */
    public function validSignatory($attribute)
    {
        if (!is_array($this->$attribute)){
            $this->addError($attribute, 'Передан неправильный формат данных.');
        } elseif (empty($this->$attribute)){
            $this->addError($attribute, 'Должен быть выбран хотя бы один подписант.');
        } elseif (count($this->$attribute) > 2){
            $this->addError($attribute, 'Выберите не более 2-х подписантов.');
        }
    }
}