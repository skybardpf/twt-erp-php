<?php
/**
 * Тип документа
 *
 * User: Forgon
 * Date: 25.02.13
 *
 * @property int $id
 * @property string $name_of_doc
 * @property string $list_of_countries
 *
 * @property string $deleted
 */


class LEDocumentType extends SOAPModel {
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
	 * Типы документов
	 *
	 * @return LEDocumentType[]
	 */
	public function findAll() {
		$filters = SoapComponent::getStructureElement($this->where);
		if (!$filters) $filters = array(array());
		$request = array('filters' => $filters, 'sort' => array($this->order));

		$ret = $this->SOAP->listLEDocumentTypes($request);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Тип документа
	 * @param $id
	 *
	 * @return LEDocumentType
	 */
	public function findByPk($id) {
		$ret = $this->SOAP->getLEDocumentType(array('id' => $id));
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_elem(current($ret), __CLASS__);
	}

	/**
	 * Удаление типа документа
	 *
	 * @return bool
	 */
	public function delete() {
		$cacher = new CFileCache();
		$cacher->add('LEDoc_type_values', false, 1);

		if ($pk = $this->getprimaryKey()) {
			$ret = $this->SOAP->deleteLEDocumentType(array('id' => $pk));
			return $ret->return;
		}
		return false;
	}

	public function save() {
		$attr = array();
		//[{"data":{"id":"","name_of_doc": "тест", "deleted": false, "list_of_countries": [ {"country":"004","name_in_country":"doc004","user": "rt34000000002", "from_user": false} ]} }]
	}

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels() {
		return array(
			'id'                => '#',
			'name_of_doc'       => 'Название',
			'list_of_countries' => 'Страны юрисдикции',
			'deleted'           => 'Помечен на удаление'
			//Адрес отделения
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		return array(
			array('name, list_of_countries', 'required'),
			array('id, name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * Список доступных значений Типов документов
	 * @return array
	 */
	static function getValues() {
		$cacher = new CFileCache();
		$cache = $cacher->get('LEDoc_type_values');
		if ($cache === false) {
			if (!self::$values) {
				$elements = self::model()->where('deleted', false)->findAll();
				$return   = array();
				if ($elements) { foreach ($elements as $elem) {
					$return[$elem->getprimaryKey()] = $elem->name_of_doc;
				} }
				self::$values = $return;

			}
			$cacher->add('LEDoc_type_values', self::$values, 3000);
		} elseif (!self::$values) {
			self::$values = $cache;
		}
		return self::$values;
	}
}