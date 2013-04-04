<?php
/**
 * Свободный документ
 *
 * User: Forgon
 * Date: 25.02.13
 * @property int $id
 * @property string $id_yur
 * @property string $name
 * @property string $date
 * @property string $expire
 * @property string $typ_doc
 * @property string $from_user
 * @property string $nom
 * @property string $user
 *
 * @property string $deleted
 */
class FreeDocument extends SOAPModel {

	/**
	 * @static
	 *
	 * @param string $className
	 *
	 * @return FreeDocument
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * Список свободных документов
	 *
	 * @return FreeDocument[]
	 */
	public function findAll() {
		$filters = SoapComponent::getStructureElement($this->where);
		if (!$filters) $filters = array(array());
		$request = array('filters' => $filters, 'sort' => array($this->order));

		$ret = $this->SOAP->listFreeDocuments($request);

		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Свободный документ
	 * @param $id
	 *
	 * @return FoundingDocument
	 */
	public function findByPk($id) {
		$ret = $this->SOAP->getFreeDocument(array('id' => $id));
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_elem(current($ret), __CLASS__);
	}

	/**
	 * Удаление Свободного документа
	 *
	 * @return bool
	 */
	public function delete() {
		if ($pk = $this->getprimaryKey()) {
			$ret = $this->SOAP->deleteFreeDocument(array('id' => $pk));
			return $ret->return;
		}
		return false;
	}

	/**
	 * Сохранение свободного документа
	 * @return array
	 */
	public function save() {
		$attrs = $this->getAttributes();

		if (!$this->getprimaryKey()) unset($attrs['id']); // New record
		unset($attrs['deleted']);
		unset($attrs['file']); // TODO когда появятся файлы
		$attrs['user'] = 'test';
		$attrs['from_user'] = true;

		$ret = $this->SOAP->saveFreeDocument(array('data' => SoapComponent::getStructureElement($attrs)));
		$ret = SoapComponent::parseReturn($ret, false);
		return $ret;
	}
	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels() {
		return array(
			'id'                => '#',
			'id_yur'            => 'Юр.Лицо',
			'name'              => 'Название',
			'date'              => 'Дата загрузки',
			'expire'            => 'Срок действия',
			'from_user'         => 'От пользователя',
			'nom'               => 'Номер документа',
			'user'              => 'Пользователь',
			'deleted'           => 'Помечен на удаление',
			'file'              => 'Электронная версия'

		);
		/*

		ТЗ
		+	ID (уникальный идентификатор, целое число, автоинкремент, обязательное);
		+	Дата загрузки документа (дата, обязательное);
		+	Пользовательское? (флаг: да или нет; обозначает источник документа, загружен оператором системы, или самим пользователем; обязательное);
		+	Пользователь, загрузивший документ (пользователь системы);
		+	Юридическое лицо (выбор из справочника, обязательное);
		+	Номер документа (текст);
		+	Наименование (текст, обязательно);
		+	Срок действия (дата, обязательное);
		+	Электронная версия (файл);
			Скан (файл или набор файлов).

			user:test,
			date:2013-03-22,
			id_yur:0000000032,
			id:000000001,
			expire:2013-03-27,
			deleted:false,
			nom:23847,
			name:НаименованиеПолное,
			from_user:false,
			file:ЦЕДокументсСсылка
		*/
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		return array(
			array('name, date, id_yur, expire', 'required'),
			array('id, from_user, nom, user, deleted', 'safe'),

			array('id, name', 'safe', 'on'=>'search'),
		);
	}

}
