<?php
/**
 * User: Forgon
 * Date: 26.02.13
 */

class CalcController extends Controller
{
	public $menu_elem = 'legal.Calc';
	public $controller_title = 'Страховой калькулятор';

	/**
	 * Список позиций
	 */
	public function actionIndex() {
		$values = array();
		$model = new Calc();
		$error = '';

		// todo Авторизованный пользователь. пока передается пустой.
		$data = array('UserID' => '', 'Strings' => array());

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
}