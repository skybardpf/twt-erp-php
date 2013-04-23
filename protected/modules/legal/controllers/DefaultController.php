<?php
/**
 * User: Forgon
 * Date: 21.02.13
 */
class DefaultController extends Controller {
	public function actionIndex() {
		$data = array();
		if ($_POST && !empty($_POST['method'])) {
			$data = $_POST;
			$method = $_POST['method'];
			$ret = call_user_func_array(array(Yii::app()->soap, $method), !empty($_POST['args']) ? json_decode($_POST['args']) : array());
			CVarDumper::dump($ret, 3, 1);
			$ret = SoapComponent::parseReturn($ret);
			CVarDumper::dump($ret, 5, 1);
		}
		$this->render('index', array('data' => $data));
	}
}