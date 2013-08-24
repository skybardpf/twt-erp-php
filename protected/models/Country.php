<?php
/**
 * Модель: Страна.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $id
 * @property string $name
*/
class Country extends SOAPModel {
    const PREFIX_CACHE_LIST_NAMES = '_list_names';

	/**
	 * @static
	 * @param string $className
	 * @return Country
	 */
	public static function model($className = __CLASS__)
    {
		return parent::model($className);
	}

	/**
	 * Список стран
	 * @return Country[]
	 */
	protected function findAll()
    {
        $request = array('filters' => array(), 'sort' => array($this->order));
		$ret = $this->SOAP->listCountries($request);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

    /**
     * @return array
     */
    public function attributeNames()
    {
        return array(
            'id',            // string
            'name',          // string
        );
    }

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels()
    {
		return array(
			'id' => '#',
			'name' => 'Название',
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
    {
		return array(
			array('name', 'required'),
		);
	}

    /**
     * Список доступных значений Стран [id => name].
     * Результат сохраняем в кеш.
     * @param bool $force_cache
     * @return array
     */
    public function listNames($force_cache = false)
    {
        $cache_id = __CLASS__ . self::PREFIX_CACHE_LIST_NAMES;
        if ($force_cache || ($data = Yii::app()->cache->get($cache_id)) === false) {
            $data = array();
            $elements = $this->where('deleted', true)->findAll();
            foreach ($elements as $elem) {
                $data[$elem->primaryKey] = $elem->name;
            }
            asort($data);
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }
}