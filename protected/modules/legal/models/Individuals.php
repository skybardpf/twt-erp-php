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
	 * @internal param array $filter
	 */
	/*public function findByPk($id) {
		$ret = $this->SOAP->getLegalEntity(array('id' => $id));
		$ret = SoapComponent::parseReturn($ret);
		return $this->publish_elem(current($ret), __CLASS__);
	}*/

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels() {
		return array(
			'id'            => '#',                                 // +
			'name'          => 'Сокращенное наименование',          // +
			'full_name'     => 'Полное наименование',                        // +
			'country'       => 'Страна юрисдикции',                 // + id
			'resident'      => 'Резидент РФ',                       // + boolean
			'type_no_res'   => 'Тип нерезидента',                   // + int
			'contragent'    => 'Контрагент',                        // + boolean
			'parent'        => 'Группа контрагентов',               // +
			'comment'       => 'Комментарий',                       // +
			'inn'           => 'ИНН',                               // +
			'kpp'           => 'КПП',                               // +
			'ogrn'          => 'ОГРН',                              // +
			'yur_address'   => 'Адрес юридический',                 // +
			'fact_address'  => 'Адрес фактический',                 // +
			'reg_nom'       => 'Регистрационный номер',             // +
			'sert_nom'      => 'Номер сертификата о регистрации',   // +
			'sert_date'     => 'Дата сертификата о регистрации',    // +
			'vat_nom'       => 'VAT-номер',                         // +
			'profile'       => 'Основной вид деятельности',         // +
			'eng_name'      => 'Английское наименование',           // +
			'deleted'       => 'Помечен на удаление'                // +
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