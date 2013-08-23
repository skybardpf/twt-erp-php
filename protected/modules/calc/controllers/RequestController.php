<?php
/**
 * Cтраховой калькулятор.
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class RequestController extends Controller
{
    public $layout = 'calc';
	public $controller_title = 'Страховой калькулятор';

	protected $_seat_measures = false;
	protected $_weight_measures = false;
	protected $_cities = array();
	protected $_categories = array();

//    public function filters() {
//        return array(
//            'accessControl',
//        );
//    }

    /**
     * @return array
     */
    public function actions()
    {
        return array(
            'index' => 'application.modules.calc.controllers.Request.IndexAction',
            'step2' => 'application.modules.calc.controllers.Request.Step2Action',
            'order' => 'application.modules.calc.controllers.Request.OrderAction',

            'captcha'=>array('class'=>'CCaptchaAction',),
        );
    }

	public function actionFormat($id, $val = "0") {
		echo CJSON::encode(array('id' => $id, 'values' => number_format($val, 0, ",", " ")));
//		Yii::app()->end();
	}
	
	/**
	 * AJAX функция автодополнения кодов ТНВЭД
	 * @param string $q
	 * @param bool $page_limit
	 */
	public function actionTnved($q = '', $page_limit = false, $tnved = 'yes') {
		$values = array();
		// Инициализация селекта
		if (isset($_GET['id']) && $_GET['id']) {
			if ($tnved == 'yes') {
				$command = Yii::app()->db->createCommand()
					->select('code, title')
					->from('tnved')
					->where('code = :id', array(':id' => $_GET['id']));
				$tmp = $command->queryRow(true);
				$values = array(
					'id' => $tmp['code'],
					'text' => $tmp['code'].' - '.$tmp['title']
				);
			} else {
				$arr = $this->getCategories();
				if (isset($arr[strtolower($_GET['id'])])) {
					$values = array(
						'id' => $_GET['id'],
						'text' => $_GET['id'].' - '.$arr[$_GET['id']]
					);
				}
			}
		// Автодополнение селекта
		} elseif ($q && mb_strlen($q) >= 4) {
			if ($tnved == 'yes') {
				$command = Yii::app()->db->createCommand()
					->select('code, title')
					->from('tnved')
					->where('code LIKE :q OR title LIKE :q', array(':q' => '%'.$q.'%'));
				if ($page_limit) $command->limit($page_limit);
				$tmp = $command->queryAll(true);
				if ($tmp) { foreach($tmp as $t) {
					$values[] = array(
						'id' => $t['code'],
						'text' => $t['code'].' - '.$t['title']
					);
				} }
			} else {
				$arr = $this->getCategories();
                $q = mb_convert_case($q, MB_CASE_LOWER, "UTF-8");
				array_walk($arr, function($val, $key) use ($q, &$values) {
					if (mb_strpos(mb_convert_case($val, MB_CASE_LOWER, "UTF-8"), $q) !== false || mb_stripos($key, $q) !== false) {
						$values[] = array(
							'id' => $key,
							'text' => $key.' - '.$val
						);
					}
				});
			}

		}
		echo CJSON::encode(array('values' => $values));
		Yii::app()->end();
	}

	/**
	 * AJAX получение списка городов страны
	 * @param $country
	 */
	public function actionCities($country) {
		$ret = array();
		try {
			$ret = $this->getCitiesList($country);
		} catch (Exception $e) {
			$ret = array('error' => $e->getMessage());
		}
		echo CJSON::encode(array('values' => $ret));
		Yii::app()->end();
	}

	/**
	 * Список Единиц измерения мест
	 *
	 * @return array|bool|mixed
	 */
	public function getSeatMeasures() {
		if (!$this->_seat_measures) {
			$cacher = new CFileCache();
			$cache = $cacher->get('Calc_seat_measures');
			if ($cache === false) {
				if (!$this->_seat_measures) {
					$ret = Yii::app()->calc->GetMeasureOfSeat();
					$ret = SoapComponent::parseReturn($ret);
					$return = array();
					if ($ret) { foreach ($ret as $elem) {
						$return[key($elem)] = current($elem);
					} }
					asort($return);
					$this->_seat_measures = $return;
				}
				$cacher->set('Calc_seat_measures', $this->_seat_measures, 3000);
			} elseif (!$this->_seat_measures) {
				$this->_seat_measures = $cache;
			}
		}
		return $this->_seat_measures;
	}

	/**
	 * Список Единица измерения веса
	 *
	 * @return array|bool|mixed
	 */
	public function getWeightMeasures() {
		if (!$this->_weight_measures) {
			$cacher = new CFileCache();
			$cache = $cacher->get('Calc_weight_measures');
			if ($cache === false) {
				if (!$this->_weight_measures) {
					$ret = Yii::app()->calc->GetWeightMeasure();
					$ret = SoapComponent::parseReturn($ret);
					$return = array();
					if ($ret) { foreach ($ret as $elem) {
						$return[key($elem)] = current($elem);
					} }
					asort($return);
					$this->_weight_measures = $return;
				}
				$cacher->set('Calc_weight_measures', $this->_weight_measures, 3000);
			} else {
				$this->_weight_measures = $cache;
			}
		}
		return $this->_weight_measures;
	}

	/**
	 * Список Городов страны
	 *
	 * @param $country_id
	 *
	 * @return mixed
	 */
	public function getCitiesList($country_id) {
		if (!isset($this->_cities[$country_id])) {
			$cacher = new CFileCache();
			$cache = $cacher->get('Calc_cities_'.$country_id);
			if ($cache === false) {
				$ret = Yii::app()->calc->getCities(array('id' => $country_id));
				$ret = SoapComponent::parseReturn($ret);
				$return = array();
				if ($ret) { foreach ($ret as $elem) {
					$return[key($elem)] = current($elem);
				} }
				asort($return);
				$this->_cities[$country_id] = $return;
				$cacher->set('Calc_cities_'.$country_id, $this->_cities[$country_id], 3000);
			} else {
				$this->_cities[$country_id] = $cache;
			}
		}
		return $this->_cities[$country_id];
	}

	/**
	 * Список категорий грузов, используемых калькулятором
	 * @return array|mixed Массив идентификатор => название
	 */
	public function getCategories() {
		$cacher = new CFileCache();
		$cache = $cacher->get(__CLASS__.'_categories_values');
		if ($cache === false) {
			if (!$this->_categories) {
				$tmp = Yii::app()->calc->GetCategory();
				$tmp = SoapComponent::parseReturn($tmp);
				$return   = array();
				if ($tmp) foreach ($tmp as $sub_tmp) {
					// Т.к. sub_tmp = array('key' => 'title');
					$return[strtolower(key($sub_tmp))] = current($sub_tmp);
				}
				$this->_categories = $return;
			}
			$cacher->add(__CLASS__.'_categories_values', $this->_categories, 3000);
		} elseif (!$this->_categories) {
			$this->_categories = $cache;
		}
		return $this->_categories;
	}
}