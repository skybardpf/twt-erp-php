<?php
/**
 * Банк
 *
 * User: Forgon
 * Date: 26.02.13
 *
 */

class Calc extends SOAPModel {
	public $excel_file;

	static public $categories;
	/**
	 * Объект модели
	 * @static
	 *
	 * @param string $className
	 *
	 * @return Calc
	 */
	public static function model($className = __CLASS__) {
		return parent::model($className);
	}

	/**
	 * Переопределим на новый SOAP
	 */
	protected function afterConstruct() {
		$this->SOAP = Yii::app()->calc;
		if($this->hasEventHandler('onAfterConstruct'))
			$this->onAfterConstruct(new CEvent($this));
	}

	/*static public function getCategories() {
		$cacher = new CFileCache();
		$cache = $cacher->get('calc_categories');
		if ($cache === false) {
			if (!self::$categories) {
				$tmp = Yii::app()->calc->GetCategory();
				CVarDumper::dump($tmp,3,1);
				$tmp = current(SoapComponent::parseReturn($tmp));
				$values = array();
				if (isset($tmp['Код']) && isset($tmp['Наименование']) && count($tmp['Код']) == count($tmp['Наименование'])) {
					foreach ($tmp['Код'] as $k => $t) {
						$values[$t] = intval($t).' - '.$tmp['Наименование'][$k];
					}
				} else {
					throw new Exception('В выводе SOAP метода GetCategory нет ключа Код или Наименование или количество элементов в них не равно.');
				}

				CVarDumper::dump($values,3,1); exit;
				self::$categories = $values;
			}
			$cacher->add('calc_categories', self::$categories, 3000);
		} elseif (!self::$values) {
			self::$categories = $cache;
		}
		return self::$categories;
	}*/

	/**
	 * Returns the list of attribute names of the model.
	 * @return array list of attribute names.
	 */
	public function attributeLabels() {
		return array(
			'id'            => '#',
			'name'          => 'Название',
			'country'       => 'Страна юрисдикции',
			'city'          => 'Город',
			'address'       => 'Адрес',
			'phone'         => 'Телефон',
			'bik'           => 'БИК код',
			'cor_sh'        => 'Корр. счет',
			'swift'         => 'SWIFT код',
			'deleted'       => 'Помечен на удаление'
			//Адрес отделения
		);
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules() {
		return array(
			array('name, country, city, address, phone, cor_sh, swift', 'required'),
			array('excel_file', 'file', 'types'=>'xls, xlsx', 'maxSize' => 10485760),
			array('id, name, show', 'safe', 'on'=>'search'),
		);
	}
}