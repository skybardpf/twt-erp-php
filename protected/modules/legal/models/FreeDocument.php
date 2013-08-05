<?php
/**
 * Свободный документ
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @property int    $id
 * @property int    $id_yur
 * @property string $name
 * @property string $date
 * @property string $expire
 * @property string $type_yur
 * @property string $from_user
 * @property string $num
 * @property string $user
 * @property bool   $deleted
 */
class FreeDocument extends SOAPModel
{
    const PREFIX_CACHE_ID_LIST_DATA = '_list_org_id_';

	/**
	 * @static
	 * @param string $className
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
	 * @param   int $id
	 * @return  FreeDocument
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
		$data = $this->getAttributes();

		if (!$this->getprimaryKey()){
            unset($data['id']);
        }
		unset($data['deleted']);
		unset($data['file']); // TODO когда появятся файлы
		$data['type_yur']   = 'Организации';
//        (isset($this->_aTypeYur[$data['type_yur']])) ? $this->_aTypeYur[$data['type_yur']] : $this->_aTypeYur[0];
		$data['user']       = SOAPModel::USER_NAME;
		$data['from_user']  = true;

		$ret = $this->SOAP->saveFreeDocument(array(
            'data' => SoapComponent::getStructureElement($data)
        ));
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
			'type_yur'          => 'Тип юр. лица',
			'name'              => 'Название',
			'date'              => 'Дата начала действия',
			'expire'            => 'Срок действия',
			'from_user'         => 'От пользователя',
			'num'               => 'Номер документа',
			'user'              => 'Пользователь',
			'comment'           => 'Комментарий',
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
	public function rules()
    {
		return array(
			array('name', 'length', 'max' => 25),
			array('name', 'required'),
            array('date, expire', 'date', 'format' => 'yyyy-MM-dd'),
            array('comment', 'length', 'max' => 50),
            array('num', 'length', 'max' => 50),
		);
	}

    public function validNum($attribute)
    {
    }

    public function validDate($attribute)
    {
    }

    /**
     * Список довереностей.
     * @param Organization $org
     * @return FreeDocument[]
     */
    public function getData(Organization $org){
        $cache_id = get_class($this).self::PREFIX_CACHE_ID_LIST_DATA.$org->primaryKey;
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false){
            $data = $this->where('deleted', false)
                ->where('id_yur',  $org->primaryKey)
                ->findAll();
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }
}
