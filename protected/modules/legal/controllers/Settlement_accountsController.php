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

    /**
     * Список банковских счетов для указаного в $org_id юр. лица.
     *
     * @param   string $org_id
     *
     * @throws  CHttpException
     */
    public function actionList($org_id) {
        $this->menu_current = 'legal';

        $org = Organizations::model()->findByPk($org_id);
        if (!$org) {
            throw new CHttpException(404, 'Не найдено указанное юридическое лицо.');
        }

        $accounts = SettlementAccount::model()
            ->where('deleted', false)
            ->where('id_yur', $org->primaryKey)
//            ->where('type_yur', 'Организации')
            ->findAll();

        $this->render('/my_organizations/show', array(
            'content' => $this->renderPartial('/settlement_accounts/list',
                array(
                    'accounts'      => $accounts,
                    'organization'  => $org
                ), true),
            'organization' => $org,
            'cur_tab' => 'settlements',
        ));
    }

    /**
     *  Просмотр банковского счета с идентификатором $id.
     *
     *  @param  string $id
     *
     *  @throws CHttpException
     */
    public function actionView($id) {
        $this->menu_current = 'legal';

        $account = SettlementAccount::model()->findByPk($id);
        if (!$account){
            throw new CHttpException(404, 'Не найден банковский счет.');
        }

        $org = Organizations::model()->findByPk($account->id_yur);
        if (!$org){
            throw new CHttpException(404, 'Не найдено юридическое лицо.');
        }

        $this->render('/my_organizations/show', array(
            'content' => $this->renderPartial('/settlement_accounts/show',
                array(
                    'model'         => $account,
                    'organization'  => $org
                ), true),
            'organization' => $org,
            'cur_tab' => 'settlements',
        ));
    }

    /**
     *  Добавление нового банковского счета к указанному в $org_id юридическому лицу.
     *
     *  @param  string $org_id
     *
     *  @throws CHttpException
     */
    public function actionAdd($org_id) {
        $this->menu_current = 'legal';

        $org = Organizations::model()->findByPk($org_id);
        if (!$org){
            throw new CHttpException(404, 'Не найдено юридическое лицо.');
        }

        $account = new SettlementAccount();
        $account->id_yur = $org->primaryKey;

        $error = '';
        if ($_POST && !empty($_POST['SettlementAccount'])) {
            $account->setAttributes($_POST['SettlementAccount']);
            $account->str_managing_persons = $_POST['SettlementAccount']['str_managing_persons'];
            $account->managing_persons = CJSON::decode($account->str_managing_persons);
            if ($account->validate()) {
                try {
                    $account->save();
                    $this->redirect($this->createUrl('list', array('org_id' => $org->primaryKey)));
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
            $account->bank_name = SettlementAccount::getBankName($account->bank);
        }

        $this->render('/my_organizations/show', array(
            'content' => $this->renderPartial('/settlement_accounts/form',
                array(
                    'model'         => $account,
                    'organization'  => $org,
                    'error'         => $error
                ), true),
            'organization' => $org,
            'cur_tab' => 'settlements',
        ));
    }

    /**
     *  Редактирование банковского счета с идентификатором $id.
     *
     *  @param  int $id
     *
     *  @throws CHttpException
     */
    public function actionEdit($id) {
        $this->menu_current = 'legal';

        $account = SettlementAccount::model()->findByPk($id);
        if (!$account){
            throw new CHttpException(404, 'Не найден банковский счет.');
        }

        $org = Organizations::model()->findByPk($account->id_yur);
        if (!$org){
            throw new CHttpException(404, 'Не найдено юридическое лицо.');
        }

        $error = '';
        if ($_POST && !empty($_POST['SettlementAccount'])) {
            $account->setAttributes($_POST['SettlementAccount']);
            $account->str_managing_persons = $_POST['SettlementAccount']['str_managing_persons'];
            $account->managing_persons = CJSON::decode($account->str_managing_persons);

            if ($account->validate()) {
                try {
                    $account->save();
                    $this->redirect($this->createUrl('view', array('id' => $account->primaryKey)));
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }

        } else {
            $account->bank = ((int)$account->bank_bik > 0) ? $account->bank_bik : (!empty($account->bank_swift) ? $account->bank_swift : '');
        }
        $account->bank_name = SettlementAccount::getBankName($account->bank);

        $this->render('/my_organizations/show', array(
            'content' => $this->renderPartial('/settlement_accounts/form',
                array(
                    'model'         => $account,
                    'organization'  => $org,
                    'error'         => $error
                ), true),
            'organization' => $org,
            'cur_tab' => 'settlements',
        ));
    }

    /**
     *  Удаление банковского счета с идентификатором $id.
     *
     *  @param  int $id
     *
     *  @throws CHttpException
     */
    public function actionDelete($id) {
        $account = SettlementAccount::model()->findByPk($id);
        if (!$account){
            throw new CHttpException(404, 'Не найден банковский счет.');
        }
        $org = Organizations::model()->findByPk($account->id_yur);
        if (!$org){
            throw new CHttpException(404, 'Не найдено юридическое лицо.');
        }

        if (Yii::app()->request->isAjaxRequest) {
            $ret = array();
            try {
                $account->delete();
            } catch (Exception $e) {
                $ret['error'] = $e->getMessage();
            }
            echo CJSON::encode($ret);
            Yii::app()->end();
        } else {
            if (isset($_POST['result'])) {
                switch ($_POST['result']) {
                    case 'yes':
                        if ($account->delete()) {
                            $this->redirect($this->createUrl('list', array('org_id' => $org->primaryKey)));
                        } else {
                            throw new CHttpException(500, 'Не удалось удалить банковский счет.');
                        }
                        break;
                    default:
                        $this->redirect($this->createUrl('view', array('id' => $account->primaryKey)));
                        break;
                }
            }
//            $this->render('settlements/delete', array('model' => $account));
        }
    }
}
