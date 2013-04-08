<?php
class Template_exampleController extends Controller {

    public $layout='//layouts/template_example';
    public $menu_current = '';

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
        $this->render('events');
    }

    public function actionShow() {
        $this->menu_current = 'index';
        $this->render('show');
    }

    public function actionAdd() {
        $this->menu_current = 'index';
        $this->render('add');
    }
}