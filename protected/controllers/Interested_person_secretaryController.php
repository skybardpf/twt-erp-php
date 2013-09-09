<?php
/**
 * Управление заинтересованными лицами - Руководители.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @see InterestedPersonSecretary
 */
class Interested_person_secretaryController extends Controller {
    public $layout = 'inner';
    public $menu_current = 'legal';
    public $current_tab = 'interested_person';
	public $pageTitle = 'TWT Consult | Организации | Заинтересованные лица | Секретари';

    public function actions()
    {
        return array(
            'view' => 'application.controllers.InterestedPersonSecretary.ViewAction',
            'add' => 'application.controllers.InterestedPersonSecretary.CreateAction',
            'edit' => 'application.controllers.InterestedPersonSecretary.UpdateAction',
            'delete' => 'application.controllers.InterestedPersonSecretary.DeleteAction',
        );
    }
}
