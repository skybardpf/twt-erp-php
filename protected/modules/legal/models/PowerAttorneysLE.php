<?php
/**
 * User: Forgon
 * Date: 02.04.13
 */
class PowerAttorneysLE extends SOAPModel {

	public $from_user = true;
	/**
	 * @static
	 *
	 * @param string $className
	 *
	 * @return PowerAttorneysLE
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * Список доверенностей
	 *
	 * @return PowerAttorneysLE[]
	 */
	public function findAll() {
		$filters = SoapComponent::getStructureElement($this->where);
		if (!$filters) $filters = array(array());
		$request = array('filters' => $filters, 'sort' => array($this->order));

		$ret = $this->SOAP->listPowerAttorneyLE($request);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Доверенность
	 *
	 * @param $id
	 * @return bool|PowerAttorneysLE
	 * @internal param array $filter
	 */
	public function findByPk($id) {
		$ret = $this->SOAP->getPowerAttorneyLE(array('id' => $id));
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_elem(current($ret), __CLASS__);
	}

	/**
	 * Удаление Доверенности
	 *
	 * @return bool
	 */
	public function delete() {
		if ($pk = $this->getprimaryKey()) {
			$ret = $this->SOAP->deletePowerAttorneyLE(array('id' => $pk));
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
			'id'             => '#',                                 // +
			'name'           => 'Название',                          // +
			'id_yur'         => 'Юр.лицо',                           // +
			'date'           => 'Дата загрузки',                     // +
			'loaded'         => 'Дата загрузки',                 // ?
			'expire'         => 'Срок действия',                     // +
			'break'          => 'Срок действия',                 // ?
			'user'           => 'Пользователь',                      // +
			'typ_doc'        => 'Вид доверенности',                  // + (выбор из списка: генеральная, по видам договоров, свободная; обязательное);
			'nom'            => 'Номер документа',                   // +
			'deleted'        => 'Помечен на удаление',               // +
			'from_user'      => 'Загружен пользователем',
			'contract_types' => 'Виды договоров',
			'e_ver'          => 'Электронная версия',
			'scans'          => 'Сканы'

		);
		/*
		ТЗ:
		+   ID (уникальный идентификатор, целое число, автоинкремент, обязательное);
			Дата загрузки документа (дата, обязательное);
		+	Пользовательское? (флаг: да или нет; обозначает источник документа, загружен оператором системы, или самим пользователем; обязательное);
		+	Пользователь, загрузивший документ (пользователь системы);
		+	Юридическое лицо (выбор из справочника, обязательное);
		+	Номер документа (текст);
		+	Наименование (текст, обязательно);
		+	Вид доверенности (выбор из списка: генеральная, по видам договоров, свободная; обязательное);
		+	Виды договоров (произвольное количество элементов типа «вид договора);
			Срок действия (дата, обязательное);
		+	Электронная версия (файл);
		+	Скан (файл или набор файлов).
		 */

		/*
		+	id:000000001,
		+	name:12Тест ввв,
			id_yur:1000000005,

		+	date:25.11.2013 0:00:00,
		+	loaded:25.11.2013 0:00:00,
		+	expire:25.11.2013 0:00:00,
		+	break:25.11.2013 0:00:00,

			user:Главбух,
            id_lico:0000000001,
		+	typ_doc:Генеральная,
			e_ver:rt34000000002,
		+	deleted:false,
		+	nom:75,
			from_user:true,
			contract_types:
				{act:100432},
				{act:030432},
				{act:005432},
			scans:
				{scan:00432},
				{scan:вапв},
				{scan:гбнгнбгнл}

		  + ID (уникальный идентификатор, целое число, автоинкремент, обязательное);
			Пользовательское? (флаг: да или нет; обозначает источник документа, загружен оператором системы, или самим пользователем; обязательное);
			Пользователь, загрузивший документ (пользователь системы);
		  + Юридическое лицо (выбор из справочника, обязательное);

			Виды договоров (произвольное количество элементов типа «вид договора);
			Электронная версия (файл);
			Скан (файл или набор файлов).*/
	}
}
