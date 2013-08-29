<?php
/**
 * Банковские счета -> Список для всех юр. лиц.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 *
 * @see SettlementAccount
 */
class Settlement_accountController extends Controller {
    public $layout = 'inner';
    public $menu_current = 'settlements';

    public $pageTitle = 'TWT Consult | Мои счета';

    /**
     * Распределение экшенов.
     * @return array
     */
    public function actions()
    {
        return array(
            'index' => 'application.controllers.Settlement_account.IndexAction',
            'list' => 'application.controllers.Settlement_account.ListAction',
            'add' => 'application.controllers.Settlement_account.CreateAction',
            'view' => 'application.controllers.Settlement_account.ViewAction',
            'edit' => 'application.controllers.Settlement_account.UpdateAction',
            'delete' => 'application.controllers.Settlement_account.DeleteAction',

            '_get_bank_name' => 'application.controllers.Settlement_account.GetBankNameAction',
            '_get_type_view' => 'application.controllers.Settlement_account.GetTypeViewAction',
            '_html_form_select_element' => 'application.controllers.Settlement_account.HtmlFormSelectElementAction',
            '_html_row_element' => 'application.controllers.Settlement_account.HtmlRowElementAction',
        );
    }
}
