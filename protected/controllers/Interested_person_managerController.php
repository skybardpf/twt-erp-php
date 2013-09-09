<?php
/**
 * Управление заинтересованными лицами - Руководители.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @see InterestedPersonManager
 */
class Interested_person_managerController extends Controller {
    public $layout = 'inner';
    public $menu_current = 'legal';
    public $current_tab = 'interested_person';
	public $pageTitle = 'TWT Consult | Организации | Заинтересованные лица | Менеджеры';

    public function actions()
    {
        return array(
            'view' => 'application.controllers.InterestedPersonManager.ViewAction',
            'add' => 'application.controllers.InterestedPersonManager.CreateAction',
            'edit' => 'application.controllers.InterestedPersonManager.UpdateAction',
            'delete' => 'application.controllers.InterestedPersonManager.DeleteAction',
        );
    }
}
