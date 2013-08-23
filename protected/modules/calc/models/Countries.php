<?php
/**
 * Модель: Страна.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @property string $id
 * @property string $name
*/
class Countries extends SOAPModel {
    const PREFIX_CACHE_ID_LIST_DATA_NAMES = '_list_data_names';
    const CACHE_EXPIRE = 0;

	/**
	 * @static
	 *
	 * @param string $className
	 *
	 * @return Countries
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * Список стран
	 *
	 * @return Countries[]
	 */
	public function findAll() {
        $request = array('filters' => array(), 'sort' => array($this->order));

		$ret = $this->SOAP->listCountries($request);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels() {
		return array(
			'id' => '#',
			'name' => 'Название',
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		return array(
			array('name', 'required'),
			array('id, name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * Список доступных значений Стран [id => name].
     * Результат сохраняем в кеш.
     * @deprecated @see getNames()
	 * @return array
	 */
	public static function getValues() {
        $cache_id = __CLASS__.'_list';
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false) {
            $elements = self::model()->findAll();
            $data = array();
            if ($elements) {
                foreach ($elements as $elem) {
                    $data[$elem->getprimaryKey()] = $elem->name;
                }
                asort($data);
            }
            Yii::app()->cache->set($cache_id, $data, 3000);
        }
        return $data;
	}

    /**
     * Список доступных значений Стран [id => name].
     * Результат сохраняем в кеш.
     * @param bool $force_cache
     * @return array
     */
    public function getDataNames($force_cache = false) {
        $cache_id = __CLASS__ . self::PREFIX_CACHE_ID_LIST_DATA_NAMES;
        if ($force_cache || ($data = Yii::app()->cache->get($cache_id)) === false) {
            $data = array();
            $elements = $this->where('deleted', true)->findAll();
            if ($elements) {
                foreach ($elements as $elem) {
                    $data[$elem->primaryKey] = $elem->name;
                }
                asort($data);
            }
            Yii::app()->cache->set($cache_id, $data, self::CACHE_EXPIRE);
        }
        return $data;
    }
}