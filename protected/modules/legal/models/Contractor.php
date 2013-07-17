<?php
/**
 * Модель: Контрагенты, сторонние организации.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @property string $id
 * @property string $name
 * @property string $country
 * @property string $creation_date
 * @property bool   $deleted
 * @property string $parent
 */
class Contractor extends SOAPModel {
    private static $_values;

	/**
	 * @static
	 * @param string $className
	 * @return LegalEntities
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

    /**
     * Список контрагентов.
     *
     * @return Contractor[]
     */
    public function findAll()
    {
        $filters = SoapComponent::getStructureElement($this->where);

        $ret = $this->SOAP->listContragents(array(
            'filters' => (!$filters) ? array(array()) : $filters,
            'sort' => array($this->order)
        ));
        $ret = SoapComponent::parseReturn($ret);
        return $this->publish_list($ret, __CLASS__);
    }

    /**
     * @param string $id
     * @return Contractor Возвращает контрагента.
     */
    public function findByPk($id)
    {
        $ret = $this->SOAP->getContragent(array('id' => $id));
        $ret = SoapComponent::parseReturn($ret);
        return $this->publish_elem(current($ret), __CLASS__);
    }

    /**
     * Удаление.
     * @return bool
     */
    public function delete()
    {
        if ($pk = $this->getprimaryKey()) {
            $ret = $this->SOAP->deleteContragent(array('id' => $pk));
            return $ret->return;
        }
        return false;
    }

	/**
	 * Сохранение контрагента
	 * @return string Возвращает id созданой записи (при добавлении) или
     * id отредактированного контрагента.
	 */
	public function save() {
        $data = $this->getAttributes();

        if (!$this->primaryKey){
            unset($data['id']);
            $data['creation_date'] = date('Y-m-d');
            $data['parent'] = '000000129';
        }
        unset($data['deleted']);

        $ret = $this->SOAP->saveContragent(array(
            'data' => SoapComponent::getStructureElement($data),
        ));
        return SoapComponent::parseReturn($ret, false);
	}

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels() {
        return array(
            "id"            => '#',
            "country"       => 'Страна',
            "name"          => 'Наименование',
            "full_name"     => 'Полное наименование',
            'creation_date' => 'Дата добавления',
            'parent'        => 'Пользователь, добавивший в систему',
            'deleted'       => 'Помечен на удаление',
            'sert_date'     => 'Дата государственной регистрации',
            'okopf'         => 'Организационно-правовая форма',
            'profile'       => 'Основной вид деятельности',
            'yur_address'   => 'Юридический адрес',
            'fact_address'  => 'Фактический адрес',
            'email'         => 'Email',
            'phone'         => 'Телефон',
            'fax'           => 'Факс',
            'comment'       => 'Комментарий',
            'info'          => 'Дополнительная информация',

            // --- Для российских компаний
            'inn'           => 'ИНН',
            'kpp'           => 'КПП',

            // --- Для нероссийских компаний
            'vat_nom'       => 'VAT',
            'reg_nom'       => 'Регистрационный номер',
            'sert_nom'      => 'Номер сертификата',
        );
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		return array(
			array('country', 'required'),
			array('country', 'in', 'range' => array_keys(Countries::getValues())),

            array('okopf', 'required'),
            array('okopf', 'in', 'range' => array_keys(CodesOKOPF::getValues())),
//
			array('name, full_name', 'required'),

            array('sert_date', 'date', 'format' => 'yyyy-MM-dd'),

            array('comment, inn, kpp, yur_address, fact_address,
                reg_nom, sert_nom, vat_nom, profile, info,
                phone, fax',
                'safe'
            ),

            array('email', 'email'),
		);
	}

	/**
	 *  Список доступных значений Собственных Юр.Лиц
	 *
     *  @return array
	 */
	public static function getValues() {
        /**
         * @var $cache CFileCache
         */
        $cache = new CFileCache();
		$data = $cache->get(__CLASS__.'_values');
		if ($data === false) {
			if (!self::$_values) {
				$elements = self::model()->findAll();
				$return   = array();
				if ($elements) {
                    foreach ($elements as $elem) {
					    $return[$elem->getprimaryKey()] = $elem->name;
				    }
                }
				self::$_values = $return;

			}
			$cache->add(__CLASS__.'_values', self::$_values, 3000);
		} elseif (!self::$_values) {
			self::$_values = $data;
		}
		return self::$_values;
	}
}