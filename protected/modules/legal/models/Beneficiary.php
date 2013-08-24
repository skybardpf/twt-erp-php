<?php
/**
 * User: Forgon
 * Date: 02.04.13
 */

class Beneficiary extends SOAPModel {
	/**
	 * @static
	 *
	 * @param string $className
	 *
	 * @return Beneficiary
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * Список Бенефициаров
	 *
	 * @return Beneficiary[]
	 */
	public function findAll() {
        $filters = SoapComponent::getStructureElement($this->where);
        $ret = $this->SOAP->listBeneficiaries(array(
            'filters' => (!$filters) ? array(array()) : $filters,
            'sort' => array($this->order)
        ));
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Бенефициар
	 *
	 * @param string $id
	 * @param string $id_yur
	 * @param string $numPack
	 * @return bool|Beneficiary
	 * @internal param array $filter
	 */
	public function findByPk($id, $id_yur, $numPack) {
		$ret = $this->SOAP->getBeneficiary(
            array(
                'id' => $id,
                'id_yur' => $id_yur,
                'numPack' => $numPack
            )
        );
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_elem(current($ret), __CLASS__);
	}

	/**
	 * Удаление бенефициара
	 *
	 * @return bool
	 */
	public function delete() {
		if ($pk = $this->getprimaryKey()) {
			$ret = $this->SOAP->deleteBeneficiary(array('id' => $pk, 'id_yur' => $this->id_yur));
			return $ret->return;
		}
		return false;
	}

    /**
     * Сохранение бенефициара.
     *
     * @return string Если успешно сохранилось, возвращает id записи.
     * @throws CHttpException
     */
    public function save()
    {
        $data = $this->getAttributes();

        if (!$this->primaryKey){
            unset($data['id']);
        }
        $data['deleted'] = ($data['deleted'] == 1) ? false : true;

//        if ($data['type_lico'] == self::TYPE_LICO_INDIVIDUAL){
//            $data['id'] = $data['list_Individual'];
//        } elseif ($data['type_lico'] == self::TYPE_LICO_ORGANIZATION){
//            $data['id'] = $data['list_organizations'];
//        } else {
//            throw new CHttpException(500, 'Неизвестный тип лица.');
//        }
        unset($data['list_organizations']);
        unset($data['list_individuals']);
        unset($data['yur_url']);
        unset($data['lico']);
        unset($data['job_title']);
        unset($data['currency']);
        unset($data['nominal']);

        $ret = $this->SOAP->saveBeneficiary(array(
            'data' => SoapComponent::getStructureElement($data),
        ));
        return SoapComponent::parseReturn($ret, false);
    }

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels() {
		return array(
			'id'            => 'Лицо',                                 // +
			'role'          => 'Роль',                              // +
			'id_yur'        => 'Юр.лицо',
            'type_yur'      => '',
			'date'          => 'Дата вступления в должность',
			'typeStock'     => 'Вид акций',
			'dateIssue'     => 'Дата выпуска пакета акций',
			'deleted'       => 'Текущее состояние',
			'percent'       => 'Величина пакета акций, %',
			'quantStock'    => 'Количество акций',
			'lico'          => 'ФИО',
			'numPack'       => 'Номер пакета акций',

			'yur_url'       => '', // private
			'nominal'       => '',
			'currency'      => '',
			'type_lico'     => '',
			'job_title'     => '',
			'add_info'      => '',

            'list_individuals'     => 'Список физ. лиц',
            'list_organizations'     => 'Список юр. лиц',
		);

		/*

		ID (уникальный идентификатор, целое число, обязательное);
		+   Юридическое лицо (выбор из справочника, обязательное);
		+   Вид лица (выбор из вариантов: физ. лицо, юр. лицо; обязательное);
		+   Лицо (выбор из справочника юр. лиц или физ. лиц, обязательное);
		+   Роль (выбор из списка, обязательное);
		+   Величина пакета акций в процентах (дробное число);
		+   Номинальная стоимость пакета акций (дробное число, 2 знака)
		+   Валюта номинальной стоимости (выбор из справочника валют);
		+   Дополнительные сведения (текст).

		*/
	}

	/**
	 * Правила валидации
	 * @return array
	 */
	public function rules()
	{
        $stock_keys = array_keys(array_merge(array('' => ''), InterestedPerson::getStockTypes()));

		return array(
//			array('id_yur, vid, id, role', 'required'),
            array('date', 'required'),
            array('date', 'date', 'format' => 'yyyy-MM-dd'),

            array('dateIssue', 'date', 'format' => 'yyyy-MM-dd'),

            array('deleted', 'required'),
            array('deleted', 'boolean'),

            array('percent', 'numerical', 'integerOnly' => true),
            array('percent', 'validPercent'),
            array('numPack', 'numerical', 'integerOnly' => true),
            array('quantStock', 'numerical', 'integerOnly' => true),

            array('typeStock', 'in', 'range' => $stock_keys),

			array('add_info, job_title', 'safe')
		);
	}

    /**
     * @param $attribute
     */
    public function validPercent($attribute)
    {
        if ($this->$attribute < 0 || $this->$attribute > 100){
            $this->addError($attribute, 'Величина пакета акций в %, должна находиться в диапозоне от 0 до 100.');
        }
    }
}