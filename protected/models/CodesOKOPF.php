<?php
/**
 * Коды ОКОПФ (Организационо-правовая форма).
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @property string $id
 * @property string $name
 */
class CodesOKOPF extends SOAPModel
{
    const  PREFIX_CACHE_ID_LIST_NAMES_BY = '_list_names_by_key_';

	/**
	 * @static
	 * @param string $className
	 * @return CodesOKOPF
	 */
	public static function model($className = __CLASS__)
    {
		return parent::model($className);
	}

	/**
	 * Список кодов.
	 * @return CodesOKOPF[]
	 */
	protected function findAll()
    {
        $request = array('filters' => array(array()), 'sort' => array($this->order));
		$ret = $this->SOAP->listOKOPF($request);
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
     * Список доступных кодов ОКОПФ.
     * @param bool $force_cache.
     * @param bool $key_name. Если TRUE, то ключом будет название кода ОКОПФ.
     * @return array
     */
    public function listNames($force_cache = false, $key_name = false)
    {
        $cache_id = __CLASS__.self::PREFIX_CACHE_ID_LIST_NAMES_BY.($key_name ? 'name' : 'key');
        if ($force_cache || ($data = Yii::app()->cache->get($cache_id)) === false) {
            $data = array();
            $elements = $this->findAll();
            foreach ($elements as $elem) {
                $key = ($key_name ? $elem->name : $elem->getprimaryKey());
                $data[$key] = $elem->name;
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }
}