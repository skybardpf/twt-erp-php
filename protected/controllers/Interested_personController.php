<?php
/**
 * Управление заинтересованными лицами.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @see InterestedPerson/Shareholder
 * @see InterestedPerson/Leader
 * @see InterestedPerson/Manager
 * @see InterestedPerson/Secretary
 */
class Interested_personController extends Controller {
    public $layout = 'inner';
    public $menu_current = 'legal';
    public $current_tab = 'interested_person';
	public $pageTitle = 'TWT Consult | Организации | Заинтересованные лица';

    public function actions()
    {
        return array(
            'index' => 'application.controllers.InterestedPerson.IndexAction',
            '_get_history_models' => 'application.controllers.InterestedPerson.GetHistoryModelsAction',
        );
    }
}
