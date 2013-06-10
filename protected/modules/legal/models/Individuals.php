<?php
/**
 * User: Forgon
 * Date: 01.04.13
 */
class Individuals extends SOAPModel {

	static public $values = array();

	/**
	 * @static
	 *
	 * @param string $className
	 *
	 * @return Individuals
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * Список Физ.лиц
	 *
	 * @internal param array $filter
	 * @return Individuals[]
	 */
	public function findAll() {
		$filters = SoapComponent::getStructureElement($this->where);
		if (!$filters) $filters = array(array());
		$request = array('filters' => $filters, 'sort' => array($this->order));

		$ret = $this->SOAP->listIndividuals($request);
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_list($ret, __CLASS__);
	}

	/**
	 * Физ.Лицо
	 *
	 * @param $id
	 * @return bool|Individuals
	 */
	public function findByPk($id) {
		$ret = $this->SOAP->getIndividual(array('id' => $id));
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_elem(current($ret), __CLASS__);
	}

    /**
     * Сохранение Физ.Лица
     * @return array
     */
    public function save() {
        $cacher = new CFileCache();
        $cacher->set('LEntity_values', false, 1);

        $attrs = $this->getAttributes();

        $attrs['resident'] = (boolean)intval($attrs['resident']);
        
        if (!$this->getprimaryKey()) unset($attrs['id']); // New record
        unset($attrs['deleted']);

        //$ret = $this->SOAP->saveLegalEntity(array('data' => SoapComponent::getStructureElement($attrs))); // DEPRECATED
        $ret = $this->SOAP->saveOrganization(array('data' => SoapComponent::getStructureElement($attrs, array('convert_boolean' => true))));
        $ret = SoapComponent::parseReturn($ret, false);
        return $ret;
    }
    
	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels() {
        // старые поля
		/*return array(
			'id'              => '#',
			'name'            => 'Имя',
			'family'          => 'Фамилия',
			'parent_name'     => 'Отчество',
			'fullname'        => 'ФИО',

			'ser_nom_pass'    => 'Серия-номер паспорта',
			'date_pass'       => 'Дата выдачи пасопрта',
			'organ_pass'      => 'Орган, выдавший паспорт',
			'date_exp_pass'   => 'Срок действия паспорта',

			'ser_nom_passrf'  => 'Серия-номер паспорта',
			'date_passrf'     => 'Дата выдачи пасопрта',
			'organ_passrf'    => 'Орган, выдавший паспорт',
			'date_exp_passrf' => 'Срок действия паспорта',

			'group_code'      => 'Группа физ.лиц',

			'resident'        => 'Резидент РФ',

			'phone'           => 'Номер телефона',

			'adres'           => 'Адрес',
			'email'           => 'E-mail',

			'deleted'       => 'Помечен на удаление'
		);
                    id:0000000007,
                    parent_name:Давудович,
                    name:34345Иван,
                    family:ЗАО,
                    fullname:ЗАО 34345Иван Давудович,
                    ser_nom_pass:772601001,
                    resident:true,
                    organ_pass:,
                    date_pass:,
                    date_exp_pass:,

                    deleted:false,
                    group_code:10000000004,

                    organ_passrf:,
                    date_exp_passrf:,
                    ser_nom_passrf:,
                    date_passrf:

                    phone:643,
                    adres:368000,Дагестан Респ,Дербент,Г. Далгата,дом № 1А,
                    email:77@267006.22,

                +    ID (уникальный идентификатор, целое число, обязательное);
                +    Фамилия (текст, обязательное);
                +    Имя (текст, обязательное);
                +    Отчество (текст, обязательное);
                    Резидент РФ? (флаг: да или нет);
                    Контактные данные (текст);
                    Серия-номер паспорта (текст);
                    Дата выдачи пасопрта (текст);
                +    Орган выдавший паспорт (текст);
                    Срок действия паспорта (текст).

                */
        // новые поля
        return array(
            'id'              => '#',
            'name'            => 'Имя',
            'family'          => 'Фамилия',
            'parent_name'     => 'Отчество',
            'fullname'        => 'ФИО',
            
            'citizenship'     => 'Гражданство',
            
            'date_of_birth'   => 'Дата рождения',
            'place_of_birth'   => 'Место рождения',
            // контактных данных нет, вместо него пока раздельно телефон и емэйл
            'phone'           => 'Номер телефона',
            'email'           => 'E-mail',
            'adres'           => 'Адрес',            

            'ser_nom_pass'    => 'Серия и номер удостоверения',
            'date_pass'       => 'Дата выдачи удостоверения',
            'organ_pass'      => 'Орган, выдавший удостоверение',
            'date_exp_pass'   => 'Срок действия удостоверения',

            // устаревшие поля
            'deleted'       => 'Помечен на удаление',

            'ser_nom_passrf'  => 'Серия-номер паспорта',
            'date_passrf'     => 'Дата выдачи паспорта',
            'organ_passrf'    => 'Орган, выдавший паспорт',
            'date_exp_passrf' => 'Срок действия паспорта',
            'resident'        => 'Резидент РФ',
            'group_code'      => 'Группа физ.лиц',
        );
	}

	/**
	 * Список доступных значений Физ.лиц
	 * @return array
	 */
	static function getValues() {
		$cacher = new CFileCache();
		$cache = $cacher->get('Individuals_values');
		if ($cache === false) {
			if (!self::$values) {
				$elements = self::model()->findAll();
				$return   = array();
				if ($elements) { foreach ($elements as $elem) {
					$return[$elem->getprimaryKey()] = $elem->name;
				} }
				self::$values = $return;

			}
			$cacher->add('Individuals_values', self::$values, 3000);
		} elseif (!self::$values) {
			self::$values = $cache;
		}
		return self::$values;
	}
}