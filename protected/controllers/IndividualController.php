<?php
/**
 * Физические лица.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @see Individual
 */
class IndividualController extends Controller
{
    public $layout       = 'inner';
    public $menu_current = 'individuals';
	public $cur_tab      = '';

    public $pageTitle = 'TWT Consult | Физические лица';

    /**
     * Распределение экшенов.
     * @return array
     */
    public function actions()
    {
        return array(
            'index' => 'application.controllers.Individual.IndexAction',
            'add' => 'application.controllers.Individual.CreateAction',
            'view' => 'application.controllers.Individual.ViewAction',
            'edit' => 'application.controllers.Individual.UpdateAction',
            'delete' => 'application.controllers.Individual.DeleteAction',
        );
    }
}
