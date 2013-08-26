<?php
/**
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class IndexAction extends CAction
{
    public function run()
    {
        /**
         * @var RequestController $controller
         */
        $controller = $this->controller;

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
                            $data['Strings'][] = array('Kod' => $code, 'Summ' => (float)$val['summ']);
                        } else {
                            unset($_POST['data'][$k]);
                        }
                    }
                    if (empty($data['Strings'])){
                        throw new Exception('Выберите товар и его стоимость.');
                    }
                    $currency = Currencies::getValues();

                    if (isset($_POST['currency']) && $_POST['currency'] && isset($currency[$_POST['currency']])) {
                        $data['Currency'] = $_POST['currency'];
                    }
                    else throw new Exception('Укажите валюту');

                    $ret = Yii::app()->calc->GetSumm(array('Data' => $data));
                    $ret = CJSON::decode($ret->return);


                    Yii::app()->session['calc'] = $_POST;

                    if (isset($ret['variants'])) {
                        $i = 1;
                        foreach ($ret['variants'] as $k=>$v) {
                            $ret['variants'][$k]['number'] = $i++;
                            //округление тарифа до целых
                            $ret['variants'][$k]['cost'] = round($v['cost']);
                        }
                    }

                    $controller->render('step2', array('insurance' => $ret));
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
            $ret = $controller->render('index', array('model' => $model, 'values' => $values), 1);
            echo CJSON::encode(array('result' => $ret));
            Yii::app()->end();
        } else {
            $controller->render('index', array('model' => $model, 'values' => $values));
        }
    }
}