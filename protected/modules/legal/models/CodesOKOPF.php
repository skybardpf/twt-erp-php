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
	 * @return array
	 */
	public static function getValues() {
		$cache = new CFileCache();
		$data = $cache->get('codes_okopf_values');
		if ($data === false) {
            $elements = self::model()->findAll();
            $data = array();
            if ($elements) {
                foreach ($elements as $elem) {
                    $data[$elem->getprimaryKey()] = $elem->name;
                }
            }
			$cache->add('codes_okopf_values', $data, 3000);
		}
		return $data;
	}
}