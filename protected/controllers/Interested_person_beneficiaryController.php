<?php
/**
 * Управление заинтересованными лицами - Бенефициары.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class Interested_person_beneficiaryController extends Controller {
    public $layout = 'inner';
    public $menu_current = 'legal';
    public $current_tab = 'beneficiary';
	public $pageTitle = 'TWT Consult | Организации | Бенефициары';

    /**
     * @return array
     */
    public function actions()
    {
        return array(
            'index' => 'application.controllers.InterestedPersonBeneficiary.IndexAction',
            'view' => 'application.controllers.InterestedPersonBeneficiary.ViewAction',
            'add' => 'application.controllers.InterestedPersonBeneficiary.CreateAction',
            'edit' => 'application.controllers.InterestedPersonBeneficiary.UpdateAction',
            'delete' => 'application.controllers.InterestedPersonBeneficiary.DeleteAction',
        );
    }
}
