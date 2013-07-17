<?php
/**
 * Коды ОКОПФ (Организационо-правовая форма).
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class CodesOKOPF extends SOAPModel {
	/**
	 * @static
	 * @param string $className
	 * @return Countries
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * Список кодов.
	 * @return CodesOKOPF[]
	 */
	public function findAll() {
        $request = array('filters' => array(), 'sort' => array($this->order));

		$ret = $this->SOAP->listOKOPF($request);
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
//			array('id, name', 'safe', 'on'=>'search'),
		);
	}

	/**
	 * Список доступных кодов
	 * @param bool $key_name. Если TRUE, то ключом будет название кода ОКОПФ.
	 * @return array
	 */
	public static function getValues($key_name = false) {
		$cache = new CFileCache();
        $cache_id = 'codes_okopf_values_' . ($key_name ? 'id' : 'name');
		$data = $cache->get($cache_id);
		if ($data === false) {
            $elements = self::model()->findAll();
            $data = array();
            if ($elements) {
                foreach ($elements as $elem) {
                    $key = ($key_name ? $elem->name : $elem->getprimaryKey());
                    $data[$key] = $elem->name;
                }
            }
			$cache->add($cache_id, $data, 3000);
		}
		return $data;
	}
}