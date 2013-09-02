<?php
/**
 * Управление заинтересованными лицами - Номинальные акционеры.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @see InterestedPersonShareholder
 */
class Interested_person_shareholderController extends Controller {
    public $layout = 'inner';
    public $menu_current = 'legal';
    public $current_tab = 'interested_person';
	public $pageTitle = 'TWT Consult | Организации | Заинтересованные лица | Номинальные акционеры';

    public function actions()
    {
        return array(
            'view' => 'application.controllers.InterestedPersonShareholder.ViewAction',
            'add' => 'application.controllers.InterestedPersonShareholder.CreateAction',
            'edit' => 'application.controllers.InterestedPersonShareholder.UpdateAction',
            'delete' => 'application.controllers.InterestedPersonShareholder.DeleteAction',
        );
    }
}
