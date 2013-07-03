<?php
/**
 *  Заинтересованные лица.
 *
 *  User: Skibardin A.A.
 *  Date: 03.07.13
 */
class BeneficiaryController extends Controller {
    public $layout = 'inner';
    public $menu_current = 'legal';

    /**
	 *  Список Бенефициаров
     *
     *  @param  string $org_id
	 */
	public function actionIndex($org_id)
    {
        $this->redirect($this->createUrl('list', array('org_id' => $org_id)));
	}

    /**
     *  Список Бенефициаров
     *
     *  @param  string $org_id
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
            'content' => $this->renderPartial('/beneficiary/list',
                array(
                    'organization' => $org
                ), true),
            'organization' => $org,
            'cur_tab' => 'benefits',
        ));
    }

    public function actionBenefits($id) {
        $this->menu_current = 'index';
        $this->cur_tab = 'benefits';
        $this->render('show', array('tab_content' => $this->renderPartial('../template_example/benefits/list', array('id' => $id), true), 'model' => $model));
    }

    public function actionBenefit_add($id) {
        $this->menu_current = 'index';
        $this->cur_tab = 'benefits';
        $model = Organizations::model()->findByPk($id);
        $this->render('show', array('tab_content' => $this->renderPartial('../template_example/benefits/add', array('id' => $id), true), 'model' => $model));
    }

    public function actionBenefit_show($id) {
        $this->menu_current = 'index';
        $this->cur_tab = 'benefits';
        $this->render('show', array('tab_content' => $this->renderPartial('../template_example/benefits/show', array('id' => $id), true), 'model' => $model));
    }

}
