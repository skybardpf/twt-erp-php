<?php
/**
 * Валюты
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $id
 * @property string $name
 */
class Currency extends SOAPModel
{
    const PREFIX_CACHE_LIST_MODELS = '_list_models';

	/**
	 * @static
	 * @param string $className
	 * @return Currency
	 */
	public static function model($className = __CLASS__)
    {
		return parent::model($className);
	}

	/**
	 * Список валют
	 * @return Currency[]
	 */
	protected function findAll()
    {
		$ret = $this->SOAP->listCurrencies(
            array('filters' => array(array()), 'sort' => array($this->order))
        );
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
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
	 * @return array validation rules for model attributes.
	 */
	public function rules()
    {
		return array(
			array('name', 'required'),
		);
	}

	/**
	 * Список доступных названий Валют. Формат [key=>value]
     * @param bool $force_cache
	 * @return array
	 */
	public function listNames($force_cache=false)
    {
        $cache_id = __CLASS__ . self::PREFIX_CACHE_LIST_MODELS;
		if ($force_cache || ($data = Yii::app()->cache->get($cache_id)) === false) {
            $elements = $this->findAll();
            $data = array();
            foreach ($elements as $elem) {
                $data[$elem->primaryKey] = $elem->name;
            }
            Yii::app()->cache->set($cache_id, $data);
		}
		return $data;
	}
}