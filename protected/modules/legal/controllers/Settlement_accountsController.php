<?php
/**
 *  Банковские счета -> Список для всех юр. лиц.
 *  User: Skibardin A.A.
 *  Date: 28.06.13
 */
class Settlement_accountsController extends Controller {
    /** @var string Текущий вид */
    public $layout = 'inner';

    /** @var string Пункт левого меню */
    public $menu_current = 'settlements';

	/**
	 *  Список банковских счетов для всех юр. лиц.
	 */
	public function actionIndex() {
		$accounts = SettlementAccount::model()
            ->where('deleted', false)
            ->findAll();
		$this->render('index', array(
            'accounts' => $accounts
        ));
	}

    /**
     *  Получить название банка по его БИК/SWIFT
     */
    public function actionGet_bank_name($bank) {
        if (Yii::app()->request->isAjaxRequest) {
            echo CJSON::encode(array(
                'bank_name' => SettlementAccount::getBankName($bank)
            ));
            Yii::app()->end();
        }
    }

    /**
     *  Управление менеджерами счета.
     */
    public function actionSelected_managing_persons($selected_ids) {
        if (Yii::app()->request->isAjaxRequest) {
            try {
                $selected_ids = CJSON::decode($selected_ids);
                $p = Individuals::getValues();
                foreach ($selected_ids as $pid){
                    if (isset($p[$pid])){
                        unset($p[$pid]);
                    }
                }
                $p = array_merge(array('' => 'Выберите'), $p);

                $this->renderPartial('/settlement_accounts/select_managing_persons',array(
                    'data'  => $p,
                ), false);

            } catch (CException $e){
                echo $e->getMessage();
            }
        }
    }

//    /**
//     *  Управление менеджерами счета.
//     */
//    public function actionManaging_persons($action, $id, $pid) {
//        try {
//            $acc = SettlementAccount::model()->findByPk($id);
//            if (!$acc) {
//                throw new CException(404, 'Не найден указанный банковский счет.');
//            }
//
//            var_dump($acc->managing_persons);
//
//
//            $data = array();
//
//            switch ($action) {
//                case 'add' : {
//
//                } break;
//
//                case 'delete' : {
//
//                } break;
//
//                default : {
//                    throw new CException(500, 'Не найдено указанное действие.');
//                }
//            }
//
//            echo CJSON::encode(
//                array(
//                    'success'   => true,
//                    'data'      => $data,
//                    'html'      => 'delete'
//                )
//            );
//        } catch (CException $e){
//            echo CJSON::encode(
//                array(
//                    'success' => false,
//                    'message' => $e->getMessage()
//                )
//            );
//        }
//    }
}
