<?php
/**
 * Физические лица.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 *
 * @see Individuals
 */
class IndividualsController extends Controller
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
            'index' => 'application.modules.legal.controllers.Individuals.IndexAction',
            'add' => 'application.modules.legal.controllers.Individuals.CreateAction',
            'view' => 'application.modules.legal.controllers.Individuals.ViewAction',
            'edit' => 'application.modules.legal.controllers.Individuals.UpdateAction',
            'delete' => 'application.modules.legal.controllers.Individuals.DeleteAction',
        );
    }

    /**
     * Список физичекских лиц.
     * @return Individuals[]
     */
    public function getDataProvider()
    {
        return Individuals::getFullValues();
    }

    /**
     * Создаем новое физичекское лицо.
     * @return Individuals
     */
    public function createModel()
    {
        $model = new Individuals();
        return $model;
    }
}
