<?php
/**
 * Второй шаг - выбор компании и типа страхования (если ничего не выбрано - повторить с ошибкой)
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class Step2Action extends CAction
{
    public function run()
    {
        $controller = $this->controller;
        $data = array();
        if (
            !isset($_POST['variants']) || !$_POST['variants']
            || !isset($_POST['order_number']) || !$_POST['order_number']
            || !isset($_POST['order_date']) || !$_POST['order_date'])
        {
            $controller->redirect($controller->createUrl('index'));
        } else {
            $data = array(
                'NumberOfPreOrder' => $_POST['order_number'],
                'DateOfPreOrder' => $_POST['order_date'],
                'variants' =>  $_POST['variants']
            );
        }
        if (!isset($_POST['variant']) || !$_POST['variant']) {
            Yii::app()->user->setFlash('error', 'Выберите вариант страхования');
            $controller->render('step2', array('insurance' => $data));
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
                        $arr = $controller->getCategories();
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
                    $controller->render('order', array('order' => $order));
                    return;
                    //$this->redirect($this->createUrl('order', array('order_id' => $_POST['order_number'])));
                }
            } catch(Exception $e) {
                Yii::app()->user->setFlash('error', $e->getMessage());
                $controller->render('step2', array('insurance' => $data));
                return;
            }
            $controller->render('step2', array('insurance' => $data));
        }
    }
}