<?php
/**
 * Управление Контрагентами.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class ContractorController extends Controller{
    public $layout = 'inner';
    public $menu_current = 'legal';

    /**
     *  @param string $id
     *
     *  @throws CHttpException
     */
    public function actionView($id)
    {
        echo 'Контрагенты будут здесь';
    }
}