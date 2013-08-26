<?php
/**
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class OrderAction extends CAction
{
    public function run($order_id = '', $order_date = '')
    {
        $controller = $this->controller;
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
                    $send_order['UserID'] = 'test_user@nomail.asd';
                    $ret = Yii::app()->calc->CreateOrder(array('Data' => $send_order));

                    $ret = SoapComponent::parseReturn($ret, false);
                    $ret = CJSON::decode($ret);

//                    CVarDumper::dump($ret,5,1);
                    CVarDumper::dump($ret['link'],5,1);
                    CVarDumper::dump($ret['number_of_order'],5,1);
                    CVarDumper::dump($ret['date_of_order'],5,1);
                    die;

                } else {
                    Yii::app()->user->setFlash('error', $calc->getError('verifyCode'));
                }
            } catch (Exception $e) {
                Yii::app()->user->setFlash('error', $e->getMessage());
            }
        }
        $controller->render('order', array('order' => $order));
    }
}