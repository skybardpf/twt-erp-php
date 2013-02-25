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
			'deleted'           => 'Помечен на удаление'
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		return array(
			array('id, name', 'required'),
			array('id, date, expire, from_user, nom, user, deleted', 'safe'),

			array('id, name', 'safe', 'on'=>'search'),
		);
	}

}
