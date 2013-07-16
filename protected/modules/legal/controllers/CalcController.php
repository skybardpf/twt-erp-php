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
	protected $_categories = array();

	public $layout = '//layouts/calc';

    public function filters() {
        return array(
            'accessControl',
        );
    }
    public function actions()
    {
        return array(
            'captcha'=>array(
                'class'=>'CCaptchaAction',
            ),
        );
    }

	/**
	 * Список позиций
	 */
	public function actionIndex() {
		$values = array();
		$model = new Calc();
		$error = '';

		// todo Авторизованный пользователь. пока передается пустой.
		$data = array('UserID' => 'test_user@nomail.asd', 'Strings' => array());
        try {
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
                    if (isset($_POST['tnved'])) {
                        $data['ItIsCategory'] = $_POST['tnved'] == 'yes' ? 'false' : 'true';
                    }
                    foreach($_POST['data'] as $k => $val){
                        if (!empty($val['code']) && !empty($val['summ'])) {
                            $values[] = $val;
                            $needed_length = (isset($_POST['tnved']) && $_POST['tnved'] == 'yes') ? 10 : 9;
                            $code_length = strlen($val['code']);
                            $code = ($code_length == $needed_length) ? $val['code'] : str_repeat('0', $needed_length-$code_length).$val['code'];
                            $data['Strings'][] = array('Kod' => $code, 'Summ' => $val['summ']);
                        } else {
                            unset($_POST['data'][$k]);
                        }
                    }
                    if (empty($data['Strings'])){
                        throw new Exception('Выберите товар и его стоимость.');
                    }
                    Currencies::getValues();
                    if (isset($_POST['currency']) && $_POST['currency'] && isset(Currencies::$values[$_POST['currency']])) $data['Currency'] = $_POST['currency'];
                    else throw new Exception('Укажите валюту');

//                    var_dump($data);die;
                    $ret = Yii::app()->calc->GetSumm(array('Data' => $data));

                    Yii::app()->session['calc'] = $_POST;
                    $ret = SoapComponent::parseReturn($ret);
                    if (isset($ret['variants'])) {
                        $i = 1;
                        foreach ($ret['variants'] as &$variant) {
                            $variant['number'] = $i++;
                        }
                    }
                    $this->render('step2', array('insurance' => $ret));
                    return;

                }
            }
        } catch (Exception $e) {
            $error = $e->getMessage();
        }
		if ($error) {
			Yii::app()->user->setFlash('error', $error);
		}
        if (Yii::app()->request->isAjaxRequest) {
            $ret = $this->render('index', array('model' => $model, 'values' => $values), 1);
            echo CJSON::encode(array('result' => $ret));
            Yii::app()->end();
        } else {
            $this->render('index', array('model' => $model, 'values' => $values));
        }

	}

	/**
	 * Второй шаг - выбор компании и типа страхования (если ничего не выбрано - повторить с ошибкой)
	 */
	public function actionStep2() {
		$data = array();
		if (
			   !isset($_POST['variants']) || !$_POST['variants']
			|| !isset($_POST['order_number']) || !$_POST['order_number']
			|| !isset($_POST['order_date']) || !$_POST['order_date'])
		{
			$this->redirect($this->createUrl('index'));
		} else {
			$data = array(
				'NumberOfPreOrder' => $_POST['order_number'],
				'DateOfPreOrder' => $_POST['order_date'],
				'variants' =>  $_POST['variants']
			);
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
					'DateOfPreOrder'    => $_POST['order_date'],
					'Company'           => $selected_var['company'],
					'UserID'            => 'test_user@nomail.asd',
					'InsuranceType'     => $selected_var['ins_type']
				)));
				$ret = SoapComponent::parseReturn($ret);
				if ($ret) {
					Yii::app()->user->setState('ins_type', $selected_var['ins_type']);
					$order = array(
						'NumberOfPreOrder'  => $_POST['order_number'],
						'DateOfPreOrder'    => $_POST['order_date'],
					);
                    // достаём из сессии коды категорий
                    $session_calc = Yii::app()->session['calc'];
                    if ($session_calc['tnved'] == 'no') {
                        $values = '';
                        $arr = $this->getCategories();
                        if (!empty($session_calc['data'])) {
                            foreach($session_calc['data'] as $kode){
                                $q = mb_convert_case($kode['code'], MB_CASE_LOWER, "UTF-8");
                                array_walk($arr, function($val, $key) use ($q, &$values) {
                                    if (mb_strpos(mb_convert_case($val, MB_CASE_LOWER, "UTF-8"), $q) !== false || mb_stripos($key, $q) !== false) {
                                        $values .= trim($val).":\n";
                                    }
                                });
                            }
                        }

                        $order['Consignment'] = $values;
                    }
					$this->render('order', array('order' => $order));
					return;
					//$this->redirect($this->createUrl('order', array('order_id' => $_POST['order_number'])));
				}
			} catch(Exception $e) {
				Yii::app()->user->setFlash('error', $e->getMessage());
				$this->render('step2', array('insurance' => $data));
				return;
			}
			$this->render('step2', array('insurance' => $data));
		}
	}

	public function actionOrder($order_id = '', $order_date = '') {
		$send_order = ($order_id && $order_date)
			? array('NumberOfPreOrder'  => $order_id,'DateOfPreOrder'    => $order_date)
			: array();

		if ($_POST && isset($_POST['order'])) {
            $calc = new Calc();
            $calc->attributes = $order = $_POST['order'];
			try {
                /**
                 * TODO. Нужно все переписать с использованием CFormModel.
                 */
//                if (empty($_POST['order']['CompanyName']))
//                {
//                    throw new Exception("Нужно указать название компании.");
//                }
//                if (empty($_POST['order']['Beneficiary']))
//                {
//                    throw new Exception("Нужно указать выгодоприобретателя.");
//                }

                $send_order['UserID'] = 'test_user@nomail.asd';

                $send_order['DateOfPreOrder'] = (!empty($_POST['order']['DateOfPreOrder'])) ? $_POST['order']['DateOfPreOrder'] : '';
                $send_order['NumberOfPreOrder'] = (!empty($_POST['order']['NumberOfPreOrder'])) ? $_POST['order']['NumberOfPreOrder'] : '';
                $send_order['CompanyName'] = (!empty($_POST['order']['CompanyName'])) ? $_POST['order']['CompanyName'] : '';
                $send_order['Beneficiary'] = (!empty($_POST['order']['Beneficiary'])) ? $_POST['order']['Beneficiary'] : '';
                $send_order['NumberOfSeat'] = (!empty($_POST['order']['NumberOfSeat'])) ? $_POST['order']['NumberOfSeat'] : '';
                $send_order['Consignment'] = (!empty($_POST['order']['Consignment'])) ? $_POST['order']['Consignment'] : '';
                $send_order['NumberOfSeatMeasure'] = (!empty($_POST['order']['NumberOfSeatMeasure'])) ? $_POST['order']['NumberOfSeatMeasure'] : '';
                $send_order['Weight'] = (!empty($_POST['order']['Weight'])) ? $_POST['order']['Weight'] : '';
                $send_order['WeightMeasure'] = (!empty($_POST['order']['WeightMeasure'])) ? $_POST['order']['WeightMeasure'] : '';
                $send_order['Documents'] = (!empty($_POST['order']['Documents'])) ? $_POST['order']['Documents'] : '';

                if (empty($_POST['order']['StartDate'])){
                    throw new Exception("Нужно указать дату начала страхования.");
                } elseif (strtotime($_POST['order']['StartDate']) === false){
                    throw new Exception("Неправильный формат даты начала страхования.");
                }
                $send_order['StartDate'] = $_POST['order']['StartDate'];

                if (empty($_POST['order']['EndDate'])){
                    throw new Exception("Нужно указать дату окончания страхования.");
                } elseif (strtotime($_POST['order']['EndDate']) === false){
                    throw new Exception("Неправильный формат даты окончания страхования.");
                }
                $send_order['EndDate'] = $_POST['order']['EndDate'];

                if ($send_order['StartDate'] > $send_order['EndDate']) {
                    throw new Exception("Дата начала страхования не может быть позже даты окончания страхования.");
                }

                // вычисляем разницу между датами

                $EndDate = new DateTime($send_order['EndDate']);
                $StartDate = new DateTime($send_order['StartDate']);
                $diff = $EndDate->diff($StartDate, 1);
                if ($diff->days > 60) {
                    throw new Exception("Разница между датой начала и датой окончания страхования не может превышать более 60 дней.");
                }

//				if (!empty($_POST['order']['NumberOfPreOrder']))     $send_order['NumberOfPreOrder']     = $_POST['order']['NumberOfPreOrder'];
//				if (!empty($_POST['order']['DateOfPreOrder']))       $send_order['DateOfPreOrder']       = $_POST['order']['DateOfPreOrder'];
//				if (!empty($_POST['order']['Beneficiary']))          $send_order['Beneficiary']          = $_POST['order']['Beneficiary'];
//				if (!empty($_POST['order']['Beneficiary']))          $send_order['Beneficiary']          = $_POST['order']['Beneficiary'];
//				if (!empty($_POST['order']['Consignment']))          $send_order['Consignment']          = $_POST['order']['Consignment'];
//				if (!empty($_POST['order']['NumberOfSeat']))         $send_order['NumberOfSeat']         = $_POST['order']['NumberOfSeat'];
//				if (!empty($_POST['order']['NumberOfSeatMeasure']))  $send_order['NumberOfSeatMeasure']  = $_POST['order']['NumberOfSeatMeasure'];
//				if (!empty($_POST['order']['Weight']))               $send_order['Weight']               = $_POST['order']['Weight'];
//				if (!empty($_POST['order']['WeightMeasure']))        $send_order['WeightMeasure']        = $_POST['order']['WeightMeasure'];
//				if (!empty($_POST['order']['Documents']))            $send_order['Documents']            = $_POST['order']['Documents'];
//				if (!empty($_POST['order']['StartDate']))            $send_order['StartDate']            = $_POST['order']['StartDate'];
//				if (!empty($_POST['order']['EndDate']))              $send_order['EndDate']              = $_POST['order']['EndDate'];

				$send_order['Transports'] = array();
				if (isset($_POST['order']['route']) && isset($_POST['order']['route']['begin'])
					&& !empty($_POST['order']['route']['begin']['Country'])
					&& !empty($_POST['order']['route']['begin']['City'])
					&& !empty($_POST['order']['route']['begin']['Transport'])
					&& !empty($_POST['order']['route']['begin']['RegistrationNumber'])
				) {
					$send_order['Transports'][] = $_POST['order']['route']['begin'];
				} else {
					throw new Exception("Нужно указать начальную точку маршрута.");
				}

				if (isset($_POST['order']['route']) && isset($_POST['order']['route']['middle'])) {
					foreach($_POST['order']['route']['middle'] as $route_point) {
						$send_order['Transports'][] = $route_point;
					}
				}

				if (isset($_POST['order']['route']) && isset($_POST['order']['route']['end'])
					&& !empty($_POST['order']['route']['end']['Country'])
					&& !empty($_POST['order']['route']['end']['City'])
					&& !empty($_POST['order']['route']['end']['Transport'])
					&& !empty($_POST['order']['route']['end']['RegistrationNumber'])
				) {
					$send_order['Transports'][] = $_POST['order']['route']['end'];
				} else {
					throw new Exception("Нужно указать конечную точку маршрута.");
				}
                if ($calc->validate(array('verifyCode'))) {
                    $ret = Yii::app()->calc->CreateOrder(array('Data' => $send_order));
                    $ret = SoapComponent::parseReturn($ret, false);
//				    CVarDumper::dump($ret,5,1);
                } else {
                    Yii::app()->user->setFlash('error', $calc->getError('verifyCode'));
                }
			} catch (Exception $e) {
				Yii::app()->user->setFlash('error', $e->getMessage());
			}
		}
		$this->render('order', array('order' => $order));
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