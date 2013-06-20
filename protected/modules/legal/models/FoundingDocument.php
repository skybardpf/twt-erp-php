<?php
/**
 * Учредительный документ
 *
 * User: Forgon
 * Date: 25.02.13
 * @property string $id         Идентификатор
 * @property string $id_yur     Юр.Лицо
 * @property string $type_yur   Тип юрлица ("Контрагенты", "Организации")
 * @property string $name       Название
 * @property string $num        Номер
 * @property string $comment    Комментарий
 * @property string $date       Дата
 * @property string $expire     Дата окончания
 * @property string $typ_doc    Тип документа (LEDocumentType)
 *
 * @property string $from_user  Создан пользователем
 * @property string $user       Пользователь
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
		$cacher = new CFileCache();
		$data = $cacher->get(__CLASS__.'_objects_'.$id);
		if (!$data) {
			$data = $this->SOAP->getFoundingDocument(array('id' => $id));
			$data = SoapComponent::parseReturn($data);
			$data = current($data);
			$cacher->set(__CLASS__.'_objects_'.$id, $data, self::CACHE_TTL);
		} else {
			if (YII_DEBUG) Yii::log('model '.__CLASS__.' id:'.$id.' from cache', CLogger::LEVEL_INFO, 'soap');
		}

		return $this->publish_elem($data, __CLASS__);
	}

	/**
	 * Удаление учредительного документа
	 *
	 * @return bool
	 */
	public function delete() {
		if ($pk = $this->primaryKey) {
			$cacher = new CFileCache();
			$cacher->set(__CLASS__.'_objects_'.$pk, false, 1);
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
		if (!$this->primaryKey) unset($attr['id']);
		else {
			$cacher = new CFileCache();
			$cacher->set(__CLASS__.'_objects_'.$this->primaryKey, false, 1);
		}
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
			'type_yur'          => 'Тип Юр.лица',
			'name'              => 'Наименование',
			'num'               => 'Номер',
			'comment'           => 'Комментарий',
			'date'              => 'Дата начала действия',
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
			array('num, comment', 'safe'),
			//array('id, name', 'safe', 'on'=>'search'),
		);
	}
}