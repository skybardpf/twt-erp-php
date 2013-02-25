<?php
/**
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

	static public $values = array();

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
	 * @param $id
	 *
	 * @return FoundingDocument
	 */
	public function findByPk($id) {
		$ret = $this->SOAP->getFoundingDocument(array('id' => $id));
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_elem(current($ret), __CLASS__);
	}

	/**
	 * Set or remove deletion mark
	 *
	 * @return bool
	 */
	/*public function delete() {
		if ($pk = $this->getprimaryKey()) {
			$ret = $this->SOAP->deleteLEDocumentType(array('id' => $pk));
			return $ret->return;
		}
		return false;
	}*/

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels()
	{
		return array(
			'id'                => '#',
			'id_yur'            => 'Юр.Лицо',
			'name'              => 'Название',
			'date'              => 'Дата загрузки',
			'expire'            => 'Срок действия',
			'typ_doc'           => 'Срок действия',
			'deleted'           => 'Помечен на удаление'
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		return array(
			array('name, id_yur', 'required'),
			array('id, name', 'safe', 'on'=>'search'),
		);
	}
}