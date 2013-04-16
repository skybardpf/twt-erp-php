<?php
class Template_exampleController extends Controller {

    public $layout='//layouts/template_example';
    public $menu_current = '';
    public $cur_tab = '';

    public function actionIndex() {
        $this->menu_current = 'index';
        $this->render('yur_lica');
    }

    public function actionPhiz() {
        $this->menu_current = 'phiz';
        $this->render('phiz');
    }

    public function actionContragents() {
        $this->menu_current = 'contragents';
        $this->render('contragents');
    }

    public function actionEvents() {
        $this->menu_current = 'events';
        $this->render('events/list');
    }

    public function actionEvent_show() {
        $this->menu_current = 'events';
        $this->render('events/show');
    }

    public function actionEvent_add() {
        $this->menu_current = 'events';
        $this->render('events/add');
    }

    public function actionAdd() {
        $this->menu_current = 'index';
        $this->render('add');
    }

    public function actionShow() {
        $this->menu_current = 'index';
        $this->cur_tab = 'index';
        $this->render('show', array('tab_content' => $this->renderPartial('yur_info', array(), true)));
    }

    public function actionDocuments() {
        $this->menu_current = 'index';
        $this->cur_tab = 'documents';
        $this->render('show', array('tab_content' => $this->renderPartial('documents/list', array(), true)));
    }

    public function actionDocument_add() {
        $this->menu_current = 'index';
        $this->cur_tab = 'documents';
        $this->render('show', array('tab_content' => $this->renderPartial('documents/add', array(), true)));
    }

    public function actionDocument_show() {
        $this->menu_current = 'index';
        $this->cur_tab = 'documents';
        $this->render('show', array('tab_content' => $this->renderPartial('documents/show', array(), true)));
    }

    public function actionPerson_show() {
        $this->menu_current = 'phiz';
        $this->render('show', array('tab_content' => $this->renderPartial('person/show', array(), true)));
    }

    public function actionSettlements() {
        $this->menu_current = 'index';
        $this->cur_tab = 'settlements';
        $this->render('show', array('tab_content' => $this->renderPartial('settlement/list', array(), true)));
    }

    public function actionSettlement_add() {
        $this->menu_current = 'index';
        $this->cur_tab = 'settlements';
        $this->render('show', array('tab_content' => $this->renderPartial('settlement/add', array(), true)));
    }

    public function actionSettlement_show() {
        $this->menu_current = 'index';
        $this->cur_tab = 'settlements';
        $this->render('show', array('tab_content' => $this->renderPartial('settlement/show', array(), true)));
    }

    public function actionBenefits() {
        $this->menu_current = 'index';
        $this->cur_tab = 'benefits';
        $this->render('show', array('tab_content' => $this->renderPartial('benefits/list', array(), true)));
    }

    public function actionBenefit_add() {
        $this->menu_current = 'index';
        $this->cur_tab = 'benefits';
        $this->render('show', array('tab_content' => $this->renderPartial('benefits/add', array(), true)));
    }

    public function actionBenefit_show() {
        $this->menu_current = 'index';
        $this->cur_tab = 'benefits';
        $this->render('show', array('tab_content' => $this->renderPartial('benefits/show', array(), true)));
    }

    public function actionContracts() {
        $this->menu_current = 'index';
        $this->cur_tab = 'contract';
        $this->render('show', array('tab_content' => $this->renderPartial('contracts/list', array(), true)));
    }

    public function actionContract_add() {
        $this->menu_current = 'index';
        $this->cur_tab = 'contract';
        $this->render('show', array('tab_content' => $this->renderPartial('contracts/add', array(), true)));
    }

    public function actionContract_show() {
        $this->menu_current = 'index';
        $this->cur_tab = 'contract';
        $this->render('show', array('tab_content' => $this->renderPartial('contracts/show', array(), true)));
    }

    public function actionMy_events() {
        $this->menu_current = 'index';
        $this->cur_tab = 'my_events';
        $this->render('show', array('tab_content' => $this->renderPartial('my_events/list', array(), true)));
    }
}