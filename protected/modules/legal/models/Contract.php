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
 */
class Contract extends SOAPModel {
    /**
     * @static
     * @param string $className
     * @return Contract
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * Список договоров.
     * @return Contract[]
     */
    public function findAll() {
        $filters = SoapComponent::getStructureElement($this->where);
        if (!$filters) $filters = array(array());
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
        $ret = $this->SOAP->saveContract(array(
            'data' => SoapComponent::getStructureElement($data)
        ));
        return SoapComponent::parseReturn($ret, false);
    }

    /**
     * Returns the list of attribute names of the model.
     * @return array list of attribute names.
     */
    public function attributeLabels() {
        return array(
            'id'                => '#',
            'name'              => 'Наименование:',
            'number'            => 'Номер:',
            'deleted'           => 'Удален',
            'invalid'           => 'Статус договора:',

            'date'              => 'Дата заключения:',
            'expire'            => 'Действителен до:',
            'date_infomation'   => 'Уведомления об окончании действия договора за:',

            'responsible'       => 'Ответственный по договору:',
            'place_contract'    => 'Место заключения:', // Справочник допМестоЗаключенияДоговора
            'typ_doc'           => 'Вид договора:',    // Справочник .допВидыДоговоров
            'prolongation_type' => 'Тип пролонгация:',
            'everymonth_summ'   => 'Сумма ежемесячного платежа:',
            'currency'          => 'Валюта:',
            'dogovor_summ'      => 'Сумма договора:',
            'place_court'       => 'Место судебной инстанции:',

            'role_ur_face'      => 'Роль юр. лица',
            'signatory'         => 'Подписант',
            'signatory_contr'   => 'Подписант Контрагента', // Справочник Физ. Лица

            'orig_doc'          => 'Оригинальный документ:',
            'scan'              => 'Сканы:',
            'comment'           => 'Коментарий:',

            'id_yur'            => 'Идентификатор юр. лица',
            'le_id'             => 'Контрагент',  // Справочник Контрагенты, Организации
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('name', 'required'),
//            array('name', 'length', 'max' => 25),

            array('number', 'required'),
//            array('name', 'length', 'max' => 25),

            array('typ_doc', 'required'),
            array('typ_doc', 'in', 'range' => array_keys(self::getTypes())),

            array('le_id', 'required'),
            array('le_id', 'in', 'range' => array_keys(Contractor::getValues())),

            array('date, expire', 'required'),
            array('date, expire', 'date', 'format' => 'yyyy-MM-dd'),

            array('invalid', 'required'),
            array('invalid', 'in', 'range' => array(0,1)),

            array('prolongation_type', 'required'),
            array('prolongation_type', 'in', 'range' => array_keys(self::getProlongationTypes())),

            array('currency', 'required'),
            array('currency', 'in', 'range' => array_keys(Currencies::getValues())),

            array('responsible', 'required'),
            array('responsible', 'in', 'range' => array_keys(Individuals::getValues())),

            array('dogovor_summ', 'required'),
            array('dogovor_summ', 'numerical', 'integerOnly' => true, 'min' => 0),
            array('everymonth_summ', 'numerical', 'integerOnly' => true, 'min' => 0),
            array('date_infomation', 'numerical', 'integerOnly' => true, 'min' => 0),

            array('role_ur_face', 'required'),
            array('role_ur_face', 'in', 'range' => array_keys(self::getRoles())),
        );
    }

    /**
     * Список договоров. Формат [key => name]. Результат сохранеятся в кеш.
     * @return array
     */
    public static function getValues() {
        $cache = new CFileCache();
        $cache_id = __CLASS__. 'values';
        $data = $cache->get($cache_id);
        if ($data === false) {
            $elements = self::model()->findAll();
            $data = array();
            if ($elements) {
                foreach ($elements as $elem) {
                    $data[$elem->getprimaryKey()] = $elem->name;
                }
            }
            $cache->add($cache_id, $data, 3000);
        }
        return $data;
    }

    /**
     * @return array Возвращает список видов договора. Формат [key => name].
     */
    public static function getTypes(){
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
    public static function getProlongationTypes(){
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
    public static function getRoles(){
        return array(
            'Поставщик' => 'Поставщик',
            'Покупатель' => 'Покупатель',
        );
    }
}