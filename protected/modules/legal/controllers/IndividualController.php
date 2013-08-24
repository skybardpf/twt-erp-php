<?php
/**
 * Физические лица.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @see Individual
 */
class IndividualController extends Controller
{
    public $layout       = 'inner';
    public $menu_current = 'individuals';
	public $cur_tab      = '';

    public $pageTitle = 'TWT Consult | Мои физические лица';

    /**
     * Распределение экшенов.
     * @return array
     */
    public function actions()
    {
        return array(
            'index' => 'application.modules.legal.controllers.Individual.IndexAction',
            'add' => 'application.modules.legal.controllers.Individual.CreateAction',
            'view' => 'application.modules.legal.controllers.Individual.ViewAction',
            'edit' => 'application.modules.legal.controllers.Individual.UpdateAction',
            'delete' => 'application.modules.legal.controllers.Individual.DeleteAction',
        );
    }

    /**
     * Создаем новое физичекское лицо.
     * @return Individual
     */
    public function createModel()
    {
        $model = new Individual();
        return $model;
    }
}
