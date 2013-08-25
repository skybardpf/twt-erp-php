<?php
/**
 * Тип документа
 *
 * User: Forgon
 * Date: 25.02.13
 *
 * @property int    $id
 * @property string $name_of_doc
 * @property array  $list_of_countries
 * @property string $deleted
 */
class LEDocumentType extends SOAPModel {
    const PREFIX_CACHE_ID_LIST_NAMES = '_list_names';

	static public $values = array();
	public $new_countries = array();

	public $deleted = false;

	/**
	 * @static
	 * @param string $className
	 * @return LEDocumentType
	 */
	public static function model($className = __CLASS__)
    {
		return parent::model($className);
	}

	/**
	 * Типы документов
	 * @return LEDocumentType[]
	 */
	public function findAll()
    {
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
	public function findByPk($id)
    {
		$ret = $this->SOAP->getLEDocumentType(array('id' => $id));
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_elem(current($ret), __CLASS__);
	}

	/**
	 * Удаление типа документа
	 *
	 * @return bool
	 */
	public function delete()
    {
		$cacher = new CFileCache();
		$cacher->set('LEDoc_type_values', false, 1);

		if ($pk = $this->getprimaryKey()) {
			$ret = $this->SOAP->deleteLEDocumentType(array('id' => $pk));
			return $ret->return;
		}
		return false;
	}

	public function save()
    {
		$cacher = new CFileCache();
		$cacher->set('LEDoc_type_values', false, 1);

		$attrs = $this->getAttributes();
		if (!$this->getprimaryKey()) {
			$attrs['id'] = '';
		} // New record

		$ret = $this->SOAP->saveLEDocumentType(array('data' => $attrs));//SoapComponent::getStructureElement($attrs))
		$ret = SoapComponent::parseReturn($ret, false);
		return $ret;
	}

    /**
     * @return array
     */
    public function attributeNames()
    {
        return array(
            'id',            // string
            'name_of_doc',   // string
        );
    }

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels()
    {
		return array(
			'id'                => '#',
			'name_of_doc'       => 'Название',
			'list_of_countries' => 'Страны юрисдикции',
			'deleted'           => 'Помечен на удаление'
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		return array(
			array('name_of_doc, new_countries', 'required'),
			array('new_countries', 'validateCountriesList'),
			array('list_of_countries', 'unsafe'),
			array('id, name_of_doc', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * Переопределенный метод setAttribute для отдельной установки списка документов
	 * @param string $name
	 * @param mixed $value
	 *
	 * @return bool
	 */
	public function setAttribute($name,$value)
	{
		if(property_exists($this,$name))
			$this->$name = $value;
		elseif(in_array($name, $this->attributeNames()))
			$this->_attributes[$name]=$value;
		else
			return false;
		return true;
	}

	/**
     * @deprecated
	 * Список доступных значений Типов документов
	 * @return array
	 */
    public static function getValues() {
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

    /**
     * Список доступных значений Типов документов
     * @param bool $force_cache
     * @return array
     */
    public function listNames($force_cache = false)
    {
        $cache_id = __CLASS__ . self::PREFIX_CACHE_ID_LIST_NAMES;
        if ($force_cache || ($data = Yii::app()->cache->get($cache_id)) === false){
            $elements = $this->where('deleted', false)->findAll();
            $data = array();
            if ($elements) {
                foreach ($elements as $elem) {
                    $data[$elem->primaryKey] = $elem->name_of_doc;
                }
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }

	/*public function validateCountriesList($attribute, $params) {
		if ($attribute != 'new_countries') throw new Exception('Данный метод только для валидации новых стран');
		$countries = array();

		// Страны, которые были до редактирования (установим те, что загружены администрацией)
		if ($this->getprimaryKey()) {
			// Не новая запись - проверить администраторские страны
			foreach ($this->list_of_countries as $country) {
				if ($country['from_user'] == false) {
					// Страна не редактируется
					$countries[$country['country']] = $country;
				}
			}
			unset($country);
		}

		//Страны, полученные в форме, надо проверить
		foreach ($this->new_countries as $country) {
			if (isset($countries[$country['country']])) {
				if (!$this->getError($attribute)) {
					$this->addError($attribute, 'Страны не должны повторяться.');
				}
				continue;
			}
			$country['user'] = 'test';
			$country['from_user'] = true;
			$countries[$country['country']] = $country;
		}

		if (!$countries) {
			$this->addError($attribute, 'Укажите хоть 1 страну юрисдикции.');
		}
		$this->list_of_countries = array_values($countries);
	}*/
}