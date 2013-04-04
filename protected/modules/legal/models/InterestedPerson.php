<?php
/**
 * User: Forgon
 * Date: 02.04.13
 */
class InterestedPerson extends SOAPModel {
	public $new = true;

	/**
	 * Объект модели
	 * @static
	 *
	 * @param string $className
	 *
	 * @return Banks
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * Список заинтересованных лиц
	 *
	 * @return PEGroup[]
	 */
	public function findAll() {
		$filters = SoapComponent::getStructureElement($this->where);
		if (!$filters) $filters = array(array());
		$request = array('filters' => $filters, 'sort' => array($this->order));
		$ret = $this->SOAP->listInterestedPersons($request);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Заинтересованное лицо
	 *
	 * @param $id
	 * @param $id_yur
	 * @param $role
	 * @return bool|InterestedPerson
	 * @internal param array $filter
	 */
	public function findByPk($id, $id_yur, $role) {
		$ret = $this->SOAP->getInterestedPerson(array('id' => $id, 'id_yur' => $id_yur, 'role' => $role));
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_elem(current($ret), __CLASS__);
	}

	/**
	 * Установим флаг новой сущности в false
	 * @param $data
	 * @param $class
	 *
	 * @return mixed
	 */
	public function publish_elem($data, $class)
	{
		$model = parent::publish_elem($data, $class);
		$model->new = false;
		return $model;
	}

	/**
	 * Удаление заинтересованного лица
	 *
	 * @return bool
	 */
	public function delete() {
		if ($pk = $this->getprimaryKey()) {
			$ret = $this->SOAP->deleteInterestedPerson(array('id' => $pk));
			return $ret->return;
		}
		return false;
	}

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels() {
		return array(
			'id'            => 'Лицо',
			'role'          => 'Роль',
			'add_info'      => 'Дополнительные сведения',
			'cost'          => 'Номинальная стоимость пакета акций',
			'percent'       => 'Величина пакета акций',
			'vid'           => 'Вид лица', // (выбор из справочника юр. лиц или физ. лиц, обязательное); Физические лица
			'cur'           => 'Валюта номинальной стоимости',
			'deleted'       => 'Удален',
			'id_yur'        => 'Юр.Лицо'
		);

		/*

	-	ID (уникальный идентификатор, целое число, обязательное);
	+	Юридическое лицо (выбор из справочника, обязательное);
	+	Вид лица (выбор из вариантов: физ. лицо, юр. лицо; обязательное);
	+	Лицо (выбор из справочника юр. лиц или физ. лиц, обязательное);
	+	Роль (выбор из списка, обязательное);
	+	Величина пакета акций (дробное число);
	+	Номинальная стоимость пакета акций (дробное число, 2 знака)
	+	Валюта номинальной стоимости (выбор из справочника валют);
	+	Дополнительные сведения (текст).

		id:10000000007,
        id_yur:0000000005,
		add_info:,
		cost:0,
		percent:0,
		control:,
		role:Номинальный акционер,
		vid:Физические лица,
		cur:
		deleted:false,
		*/
	}

	public function rules()
	{
		return array(
			array('role, id_yur, id, vid', 'required'),
			array('percent, cost, cur, add_info', 'safe'),

			array('id, title, show', 'safe', 'on'=>'search'),
		);
	}


}