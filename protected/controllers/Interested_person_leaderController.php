<?php
/**
 * Управление заинтересованными лицами - Руководители.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @see InterestedPersonLeader
 */
class Interested_person_leaderController extends Controller {
    public $layout = 'inner';
    public $menu_current = 'legal';
    public $current_tab = 'interested_person';
	public $pageTitle = 'TWT Consult | Организации | Заинтересованные лица | Руководители';

    public function actions()
    {
        return array(
            'view' => 'application.controllers.InterestedPersonLeader.ViewAction',
            'add' => 'application.controllers.InterestedPersonLeader.CreateAction',
            'edit' => 'application.controllers.InterestedPersonLeader.UpdateAction',
            'delete' => 'application.controllers.InterestedPersonLeader.DeleteAction',
        );
    }
}
