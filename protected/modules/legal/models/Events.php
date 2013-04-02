<?php
/**
 * User: Forgon
 * Date: 02.04.13
 */
class Events extends SOAPModel {
	/**
	 * @static
	 *
	 * @param string $className
	 *
	 * @return Events
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * Список Мероприятий
	 *
	 * @return Events[]
	 */
	public function findAll() {
		$filters = SoapComponent::getStructureElement($this->where);
		if (!$filters) $filters = array(array());
		$request = array('filters' => $filters, 'sort' => array($this->order));

		$ret = $this->SOAP->listEvents($request);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Мероприятие
	 *
	 * @param $id
	 * @return bool|Events
	 * @internal param array $filter
	 */
	public function findByPk($id) {
		$ret = $this->SOAP->getEvent(array('id' => $id));
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_elem(current($ret), __CLASS__);
	}

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels() {
		return array(
			'id'                => '#',                   // +
			'name'              => 'Название',            // +
			'deleted'           => 'На удаление',
			'made_by_user'      => 'Пользовательское',
			'user'              => 'Пользователь',
			'files'             => 'Файлы',
			'event_date'        => '',
			'notification_date' => '',
			'period'            => 'Периодичность',
			'description'       => 'Описание',

			/*
		user:,
		period:Месяц,
		id:000000002,
		deleted:false,
		notification_date:2013-03-01,
		event_date:2013-03-14,
		description:всывсыфвсфыс,
		made_by_user:false,
		name:Тест2,
		files:
			file1:D:_DOCUMENTS_07 IT DepartmentPRICE_1C.XLS,
            file2:D:_DOCUMENTS_07 IT DepartmentТестовое ТЗ v2.doc

Юридическое лицо (выбор из справочника, обязательное);
Дата появления/актуализации (дата, обязательное);
Дата наступления (дата, обязательное);
Периодичность (выбор из списка: разовое, ежемесячное, ежеквартальное, ежегодное);
			*/
		);
	}

	/**
	 * Удаление Мероприятия
	 *
	 * @return bool
	 */
	public function delete() {
		if ($pk = $this->getprimaryKey()) {
			$ret = $this->SOAP->deleteEvent(array('id' => '1'.$pk));
			return $ret->return;
		}
		return false;
	}

}