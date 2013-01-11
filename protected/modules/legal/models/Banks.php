<?php
/**
 * User: Forgon
 * Date: 11.01.13
 * @property int $id
 * @property string $full_name
 * @property string $name
 * @property string $country
 * @property string $resident
 * @property string $type_no_res
 * @property string $contragent
 * @property string $group_name
 * @property string $comment
 * @property string $inn
 * @property string $kpp
 * @property string $ogrn
 * @property string $yur_address
 * @property string $fact_address
 * @property string $reg_nom
 * @property string $sert_nom
 * @property string $vat_nom
 * @property string $profile
 * @property string $deleted
 *
 */
class Banks extends SOAPModel {

	/**
	 * @static
	 *
	 * @param string $className
	 *
	 * @return Banks
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}

	/**
	 * Get list of Banks
	 *
	 * @return array
	 */
	public function findAll() {
		$ret = $this->SOAP->listBanks(array());
		$return = array();
		/*if ($ret->return) {
			$ret = (array)$ret->return;
			if (!empty($ret['ЮрЛицо'])) {
				$return = array();
				if (is_array($ret['ЮрЛицо'])) {
					foreach ($ret['ЮрЛицо'] as $elem) {
						$object = new self();
						$object->setAttributes((array)$elem, false);
						$return[] = $object;
					}
				} else {
					$object = new self();
					$object->setAttributes((array)$ret['ЮрЛицо'], false);
					$return[] = $object;
				}
			}
		}*/
		return $return;
	}

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels()
	{
		return array(
			'id'            => '#',
			'name'          => 'Название',
			'full_name'     => 'Полное имя',
			'country'       => 'Страна юрисдикции',
			'resident'      => 'Не является резидентом РФ',
			'type_no_res'   => 'Тип нерезидента',
			'contragent'    => 'Контрагент',
			'group_name'    => 'Группа контрагентов',
			'comment'       => 'Комментарий',
			'inn'           => 'ИНН',
			'kpp'           => 'КПП',
			'ogrn'          => 'ОГРН',
			'yur_address'   => 'Адрес юридический',
			'fact_address'  => 'Адрес фактический',
			'reg_nom'       => 'Регистрационный номер',
			'sert_nom'      => 'Номер сертификата о регистрации',
			'sert_date'     => 'Дата сертификата о регистрации',
			'vat_nom'       => 'VAT-номер',
			'profile'       => 'Основной вид деятельности',
			'deleted'       => 'Помечен на удаление'
			/*
Сокращенное наименование (текст, обязательное);
Английское наименование (текст);
*/
		);
	}

	// TODO rules
	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('full_name, country', 'required'),
			array('contragent, type_no_res, group_name, comment, inn, kpp, ogrn, yur_address, fact_address, reg_nom, sert_nom, sert_date, vat_nom, profile', 'safe'),
			array('show', 'numerical', 'integerOnly'=>true),
			// The following rule is used by search().
			// Please remove those attributes that should not be searched.
			array('id, title, show', 'safe', 'on'=>'search'),

			/*
			Сокращенное наименование (текст, обязательное);
			Английское наименование (текст);
			*/

			/*
			Страна юрисдикции (выбор из справочника, обязательное);
			Не является резидентом РФ (флаг: да или нет);
			Контрагент (флаг: да – сторонее лицо или нет – собственное; обязательное);
			Тип нерезидента (выбор из списка);
			Группа контрагентов (выбор из справочника);

		 */
		);
	}
}