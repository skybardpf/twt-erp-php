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
        $data = $this->SOAP->getIndividual(array('id' => $id));
        $data = SoapComponent::parseReturn($data);
        $data = current($data);
		return $this->publish_elem($data, __CLASS__);
	}

    /**
     * Сохранение Физ.Лица
     * @return array
     */
    public function save() {
        $attr = $this->getAttributes();
        if (!$this->primaryKey) {
            unset($attr['id']);
        }
        unset($attr['deleted']);

        $ret = $this->SOAP->saveIndividual(array(
            'data' => SoapComponent::getStructureElement($attr, array('convert_boolean' => true))
        ));
        $ret = SoapComponent::parseReturn($ret, false);
        if ($this->primaryKey){
            Yii::app()->cache->delete(__CLASS__.'_'.$this->primaryKey);
        }
        Yii::app()->cache->delete(__CLASS__.'_full_list');
        return $ret;
    }

	/**
	 * Удаление Физ.лица
	 *
	 * @return array|bool
	 */
	public function delete() {
		if ($id = $this->getprimaryKey()) {
			$ret = $this->SOAP->deleteIndividual(array('id' => $id));

            Yii::app()->cache->delete(__CLASS__.'_'.$this->primaryKey);
            Yii::app()->cache->delete(__CLASS__.'_full_list');

			return SoapComponent::parseReturn($ret, false);
		}
        return false;
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
	 * Список доступных значений Физ.лиц. [key => value]
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
                    $data[$elem->getprimaryKey()] = $elem->name.' '.$elem->family;
                }
                ksort($data);
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
	}

    /**
     * Список доступных значений Физ.лиц. Формат [family + key] = element
     * @return array
     */
    public static function getFullValues() {
        $cache_id = __CLASS__.'_full_list';
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false) {
            $elements = self::model()
                ->where('deleted', false)
                ->findAll();
            $data = array();
            if ($elements) {
                $tmp = array();
                foreach ($elements as $elem) {
                    $tmp[$elem->family.'_'.$elem->primaryKey] = $elem;
                }
                ksort($tmp);
                foreach ($tmp as $elem) {
                    $data[] = $elem;
                }
            }
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
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