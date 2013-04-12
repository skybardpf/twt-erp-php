<?php
/**
 * User: Forgon
 * Date: 26.02.13
 */

class CalcController extends Controller
{
	public $menu_elem = 'legal.Calc';
	public $controller_title = 'Страховой калькулятор';

	protected $_seat_measures = false;
	protected $_weight_measures = false;
	protected $_cities = array();

	public $layout = '//layouts/calc';

	/**
	 * Список позиций
	 */
	public function actionIndex() {
		$values = array();
		$model = new Calc();
		$error = '';

		// todo Авторизованный пользователь. пока передается пустой.
		$data = array('UserID' => 'test_user@nomail.asd', 'Strings' => array());

		if (!empty($_POST['parse_file'])) {
			// Парсинг XML файла с кодами ТНВЭД
			$file = CUploadedFile::getInstance($model, 'excel_file');
			$excel = Yii::app()->yexcel->readActiveSheet($file->getTempName());
			if ($excel) { foreach($excel as $e_row) {
				if (!empty($e_row['A']) || !empty($e_row['B'])) {
					$values[] = array('code' => $e_row['A'], 'summ' => $e_row['B']);
					$data['Strings'][] = array('Kod' => $e_row['A'], 'Summ' => $e_row['B']);
				}
			} }
		} elseif (isset($_POST['data'])) {
			// Нажали кнопку расчитать
			if (!$error) {
				if (isset($_POST['tnved'])) $data['ItIsCategory'] = $_POST['tnved'] == 'yes' ? 'false' : 'true';
				foreach($_POST['data'] as $val){
					if (!empty($val['code']) || !empty($val['summ'])) {
						$values[] = $val;
						$data['Strings'][] = array('Kod' => $val['code'], 'Summ' => $val['summ']);
					}
				}
				try {
					Currencies::getValues();
					if (isset($_POST['currency']) && $_POST['currency'] && isset(Currencies::$values[$_POST['currency']])) $data['Currency'] = $_POST['currency'];
					else throw new Exception('Укажите валюту');
					$ret = Yii::app()->calc->GetSumm(array('Data' => $data));
					$ret = SoapComponent::parseReturn($ret);
					if (isset($ret['variants'])) {
						$i = 1;
						foreach ($ret['variants'] as &$variant) {
							$variant['number'] = $i++;
						}
					}
					$this->render('step2', array('insurance' => $ret));
					return;
				} catch (Exception $e) {
					$error = $e->getMessage();
				}
			}
		}
		if ($error) {
			Yii::app()->user->setFlash('error', $error);
		}
		$this->render('index', array('model' => $model, 'values' => $values));
	}

	/**
	 * Второй шаг - выбор компании и типа страхования (если ничего не выбрано - повторить с ошибкой)
	 */
	public function actionStep2() {
		$data = array();
		if (!isset($_POST['variants']) || !$_POST['variants'] || !isset($_POST['order_number']) || !$_POST['order_number']) {
			$this->redirect($this->createUrl('index'));
		} else {
			$data = array('NumberOfPreOrder' => $_POST['order_number'], 'variants' =>  $_POST['variants']);
		}

		if (!isset($_POST['variant']) || !$_POST['variant']) {
			Yii::app()->user->setFlash('error', 'Выберите вариант страхования');
			$this->render('step2', array('insurance' => $data));
		} else {
			$data['variants'][$_POST['variant']]['selected'] = 1;
			try {
				$selected_var = $data['variants'][$_POST['variant']];
				$ret = Yii::app()->calc->ApplyMethod(array('Data' => array(
					'NumberOfPreOrder'  => $_POST['order_number'],
					'Company'           => $selected_var['company'],
					'UserID'            => 'test_user@nomail.asd',
					'InsuranceType'     => $selected_var['ins_type']
				)));
				$ret = SoapComponent::parseReturn($ret);
				if ($ret) {
					Yii::app()->user->setState('ins_type', $selected_var['ins_type']);
					$this->redirect($this->createUrl('order', array('order_id' => $_POST['order_number'])));
				}
			} catch(Exception $e) {
				Yii::app()->user->setFlash('error', $e->getMessage());
				$this->render('step2', array('insurance' => $data));
				return;
			}
			$this->render('step2', array('insurance' => $data));
		}
	}

	public function actionOrder($order_id) {
		$order = array();
		if ($_POST && isset($_POST['order'])) {
			$order = $_POST['order'];
		}
		$this->render('order', array('order' => $order));
	}

	/**
	 * AJAX функция автодополнения кодов ТНВЭД
	 * @param string $q
	 * @param bool $page_limit
	 */
	public function actionTnved($q = '', $page_limit = false) {
		$values = array();
		// Инициализация селекта
		if (isset($_GET['id']) && $_GET['id']) {
			$command = Yii::app()->db->createCommand()
				->select('code, title')
				->from('tnved')
				->where('code = :id', array(':id' => $_GET['id']));
			$tmp = $command->queryRow(true);
			$values = array(
				'id' => $tmp['code'],
				'text' => $tmp['code'].' - '.$tmp['title']
			);
		// Автодополнение селекта
		} elseif ($q && mb_strlen($q) >= 4) {
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
}