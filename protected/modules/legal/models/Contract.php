<?php
/**
 * Модель, реализующая сущность договора организации.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @property string     $id
 * @property string     $name
 * @property boolean    $deleted
 * @property string     $id_yur
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
            'name'              => 'Название',
            'number'            => 'Номер договора',
            'deleted'           => 'Удален',
            'invalid'           => 'Недействителен',

            'date'              => 'Дата заключения',
            'expire'            => 'Действителен до',
            'date_infomation'   => 'Срок Уведомления По Договору',

            'responsible'       => 'Ответственный По Договору',
            'place_contract'    => 'Место Заключения Договора', // Справочник допМестоЗаключенияДоговора
            'typ_doc'           => 'Вид Договора',    // Справочник .допВидыДоговоров
            'prolongation_type' => 'Тип Пролонгация Договора',
            'everymonth_summ'   => 'Сумма Платежей В Месяц',
            'currency'          => 'Валюта Взаиморасчетов',
            'dogovor_summ'      => 'Сумма договора',
            'place_court'       => 'Местонахождения Суда',


            'role_ur_face'      => 'Роль юр. лица',
            'signatory'         => 'Подписант',
            'signatory_contr'   => 'Подписант Контрагента', // Справочник Физ. Лица

            'orig_doc'          => 'Оригинальные документы',
            'scan'              => 'Сканы',

            'id_yur'            => 'Идентификатор юр. лица',
            'le_id'             => 'Владелец',  // Справочник Контрагенты, Организации
        );
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
//            array('name', 'required'),
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
}