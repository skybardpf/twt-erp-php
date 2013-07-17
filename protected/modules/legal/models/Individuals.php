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
		$cacher = new CFileCache();
		$data = $cacher->get(__CLASS__.'_objects_'.$id);
		if (!$data) {
			$data = $this->SOAP->getIndividual(array('id' => $id));
			$data = SoapComponent::parseReturn($data);
			$data = current($data);
			$cacher->set(__CLASS__.'_objects_'.$id, $data, self::CACHE_TTL);
		} else {
			if (YII_DEBUG) Yii::log('model '.__CLASS__.' id:'.$id.' from cache', CLogger::LEVEL_INFO, 'soap');
		}
		return $this->publish_elem($data, __CLASS__);
	}

    /**
     * Сохранение Физ.Лица
     * @return array
     */
    public function save() {
        $cacher = new CFileCache();
        $cacher->set(__CLASS__.'_values', false, 1);

        $attrs = $this->getAttributes();

        if (!$this->primaryKey) unset($attrs['id']);
        else $cacher->set(__CLASS__.'_objects_'.$this->primaryKey, false, 1);

        unset($attrs['deleted']);

        $ret = $this->SOAP->saveIndividual(array('data' => SoapComponent::getStructureElement($attrs, array('convert_boolean' => true))));
        $ret = SoapComponent::parseReturn($ret, false);
        return $ret;
    }

	/**
	 * Удаление Физ.лица
	 *
	 * @return array|bool
	 */
	public function delete() {
		$cacher = new CFileCache();
		$cacher->set(__CLASS__.'_values', false, 1);
		$cacher->set(__CLASS__.'_objects_'.$this->primaryKey, false, 1);

		if ($id = $this->getprimaryKey()) {
			$ret = $this->SOAP->deleteIndividual(array('id' => $id));
			return SoapComponent::parseReturn($ret, false);
		} else {
			return false;
		}
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
	public static function getValues() {
		$cacher = new CFileCache();
		$cache = $cacher->get(__CLASS__.'_values');
		if ($cache === false) {
			if (!self::$values) {
				$elements = self::model()->findAll();
				$return   = array();
				if ($elements) {
                    foreach ($elements as $elem) {
					    $return[$elem->getprimaryKey()] = $elem->family.' '.$elem->name.' '.$elem->parent_name;
				    }
                    ksort($return);
                }
				self::$values = $return;

			}
			$cacher->add(__CLASS__.'_values', self::$values, 3000);
		} elseif (!self::$values) {
			self::$values = $cache;
		}
		return self::$values;
	}

    /**
     * Список доступных значений Физ.лиц
     * @return array
     */
    public static function getFullValues() {
        $cacher = new CFileCache();
        $cache = $cacher->get(__CLASS__.'_full_values');
        if ($cache === false) {
            if (!self::$values) {
                $elements = self::model()->findAll();
                $return   = array();
                if ($elements) {
                    foreach ($elements as $elem) {
                        $return[$elem->family.'_'.$elem->primaryKey] = $elem;
                    }
                    ksort($return);
                    $elements   = array();
                    foreach ($return as $elem) {
                        $elements[] = $elem;
                    }
                }
                self::$values = $elements;

            }
            $cacher->add(__CLASS__.'_full_values', self::$values, 3000);
        } elseif (!self::$values) {
            self::$values = $cache;
        }
        return self::$values;
    }

	public function rules() {
		return array(
            array('citizenship', 'required'),
            array('citizenship', 'in', 'range'  => array_keys(Countries::getValues())),

            array('name', 'required'),
            array('family', 'required'),

			array('name, parent_name, family, birth_date, birth_place, date_exp_pass',     'safe'),
            array('phone, email, adres, ser_nom_pass, date_pass, organ_pass, citizenship', 'safe'),
		);
	}
}