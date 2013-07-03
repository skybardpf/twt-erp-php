<?php
/**
 *  Договоры.
 *
 *  User: Skibardin A.A.
 *  Date: 03.07.13
 */
class ContractsController extends Controller{
    public $layout = 'inner';
    public $menu_current = 'legal';

    /**
     *  Выводим договоры для юридического лица $org_id.
     *
     *  @param string $org_id
     */
    public function actionIndex($org_id)
    {
        $this->redirect($this->createUrl('list', array('org_id' => $org_id)));
    }

    /**
     *  Выводим договоры для юридического лица $org_id.
     *
     *  @param string $org_id
     *
     *  @throws CHttpException
     */
    public function actionList($org_id)
    {
        $org = Organizations::model()->findByPk($org_id);
        if (!$org) {
            throw new CHttpException(404, 'Не найдено юридическое лицо.');
        }

        $this->render('/my_organizations/show', array(
            'content' => $this->renderPartial('/contracts/list',
                array(
                    'organization' => $org
                ), true),
            'organization' => $org,
            'cur_tab' => 'contracts',
        ));
    }

    public function actionContracts($id) {
        $this->menu_current = 'index';
        $this->cur_tab = 'contract';
        $model = Organizations::model()->findByPk($id);
        $this->render('show', array('tab_content' => $this->renderPartial('../template_example/contracts/list', array('id' => $id), true), 'model' => $model));
    }

    public function actionContract_add($id) {
        $this->menu_current = 'index';
        $this->cur_tab = 'contract';
        $this->render('show', array('tab_content' => $this->renderPartial('../template_example/contracts/add', array('id' => $id), true), 'model' => $model));
    }

    public function actionContract_show($id) {
        $this->menu_current = 'index';
        $this->cur_tab = 'contract';
        $model = Organizations::model()->findByPk($id);
        $this->render('show', array('tab_content' => $this->renderPartial('../template_example/contracts/show', array('id' => $id), true), 'model' => $model));
    }
}