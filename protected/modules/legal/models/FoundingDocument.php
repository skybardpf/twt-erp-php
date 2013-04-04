<?php
/**
 * Учредительный документ
 *
 * User: Forgon
 * Date: 25.02.13
 * @property int $id
 * @property string $id_yur
 * @property string $name
 * @property string $date
 * @property string $expire
 * @property string $typ_doc
 *
 * @property string $deleted
 */

class FoundingDocument extends SOAPModel {

	public $from_user = true;

	/**
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
	 * Список учредительных документов
	 *
	 * @return FoundingDocument[]
	 */
	public function findAll() {
		$filters = SoapComponent::getStructureElement($this->where);
		if (!$filters) $filters = array(array());
		$request = array('filters' => $filters, 'sort' => array($this->order));

		$ret = $this->SOAP->listFoundingDocuments($request);

		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Учредительный документ
	 * @param $id
	 *
	 * @return FoundingDocument
	 */
	public function findByPk($id) {
		$ret = $this->SOAP->getFoundingDocument(array('id' => $id));
		$ret = SoapComponent::parseReturn($ret);
		if ($ret) return $this->publish_elem(current($ret), __CLASS__);
		else return null;
	}

	/**
	 * Удаление учредительного документа
	 *
	 * @return bool
	 */
	public function delete() {
		if ($pk = $this->getprimaryKey()) {
			$ret = $this->SOAP->deleteFoundingDocument(array('id' => $pk));
			return $ret->return;
		}
		return false;
	}

	/**
	 * Сохранение учредительного документа
	 * @return array
	 */
	public function save() {
		$attr = $this->attributes;
		if (!$this->getprimaryKey()) unset($attr['id']);
		$attr['user'] = 'test';
		unset($attr['deleted']);

		$data = array('data' => SoapComponent::getStructureElement($attr));
		$ret = $this->SOAP->saveFoundingDocument($data);
		$ret = SoapComponent::parseReturn($ret);
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
			'typ_doc'           => 'Тип документа',
			'deleted'           => 'Помечен на удаление',
			'from_user'         => 'Добавлено пользователем',
			'user'              => 'Пользователь'
		);

		/*
		ТЗ:

		+	ID (уникальный идентификатор, целое число, автоинкремент, обязательное);
		+	Дата загрузки документа (дата, обязательное);
		+	Пользовательское? (флаг: да или нет; обозначает источник документа, загружен оператором системы, или самим пользователем; обязательное);
		+	Пользователь, загрузивший документ (пользователь системы);
		+	Юридическое лицо (выбор из справочника, обязательное);
		+	Тип документа (выбор из справочника, обязательное);
		+	Наименование (текст, обязательно);
		+	Срок действия (дата, обязательное);
		-	Электронная версия (файл);
		-	Скан (файл или набор файлов).
		*/
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		return array(
			array('name, id_yur, date, expire, typ_doc', 'required'),
			array('date, expire, typ_doc', 'safe'),
			array('id, name', 'safe', 'on'=>'search'),
		);
	}
}