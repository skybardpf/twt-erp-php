<?php
/**
 * User: Forgon
 * Date: 01.04.13
 *
 * @property string $id             Идентификатор
 * @property string $deleted        Удален? ("true" - да, "false" - нет)
 *
 * @property string $family         Фамилия
 * @property string $name           Имя
 * @property string $parent_name    Отчество
 * @property string $fullname       ФИО
 * @property string $citizenship    Гражданство !!! Не идентификатор страны, а строка
 * @property string $birth_place    Место рождения
 * @property string $birth_date     Дата рождения
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
        $cacher->set(__CLASS__.'_values', false, 1);

        $attrs = $this->getAttributes();

        if (!$this->getprimaryKey()) unset($attrs['id']); // New record
        unset($attrs['deleted']);

        $ret = $this->SOAP->saveIndividual(array('data' => SoapComponent::getStructureElement($attrs, array('convert_boolean' => true))));
        $ret = SoapComponent::parseReturn($ret, false);
        return $ret;
    }
    
	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels() {
        return array(
            'id'              => '#',
            'name'            => 'Имя',
            'family'          => 'Фамилия',
            'parent_name'     => 'Отчество',
            'fullname'        => 'ФИО',
            
            'citizenship'     => 'Гражданство',
            
            'birth_date'      => 'Дата рождения',
            'birth_place'     => 'Место рождения',

            // контактных данных нет, вместо него пока раздельно телефон и емэйл
            'phone'           => 'Номер телефона',
            'email'           => 'E-mail',
            'adres'           => 'Адрес',            

            'ser_nom_pass'    => 'Серия и номер удостоверения',
            'date_pass'       => 'Дата выдачи удостоверения',
            'organ_pass'      => 'Орган, выдавший удостоверение',
            'date_exp_pass'   => 'Срок действия удостоверения',

            'deleted'         => 'Помечен на удаление',

	        // устаревшие поля
            //'ser_nom_passrf'  => 'Серия-номер паспорта',
            //'date_passrf'     => 'Дата выдачи паспорта',
            //'organ_passrf'    => 'Орган, выдавший паспорт',
            //'date_exp_passrf' => 'Срок действия паспорта',
            //'resident'        => 'Резидент РФ',
            //'group_code'      => 'Группа физ.лиц',
        );
	}

	/**
	 * Список доступных значений Физ.лиц
	 * @return array
	 */
	static function getValues() {
		$cacher = new CFileCache();
		$cache = $cacher->get(__CLASS__.'_values');
		if ($cache === false) {
			if (!self::$values) {
				$elements = self::model()->findAll();
				$return   = array();
				if ($elements) { foreach ($elements as $elem) {
					$return[$elem->getprimaryKey()] = $elem->family.' '.$elem->name.' '.$elem->parent_name;
				} }
				self::$values = $return;

			}
			$cacher->add(__CLASS__.'_values', self::$values, 3000);
		} elseif (!self::$values) {
			self::$values = $cache;
		}
		return self::$values;
	}

	public function rules() {
		return array(
			array('name, parent_name, family, birth_date, birth_place, date_exp_pass',     'safe'),
			array('phone, email, adres, ser_nom_pass, date_pass, organ_pass, citizenship', 'safe'),
			//array('id, name, show', 'safe', 'on'=>'search'),
		);
	}
}