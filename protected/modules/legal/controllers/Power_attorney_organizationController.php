<?php
/**
 *  Управление довереностями организации.
 *
 *  @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class Power_attorney_organizationController extends Controller {
    public $layout = 'inner';
    public $menu_current = 'legal';
    public $pageTitle = 'TWT Consult | Организации | Доверенности';

    /**
     * Распределение экшенов.
     * @return array
     */
    public function actions()
    {
        return array(
//            'list' => 'application.modules.legal.controllers.PowerAttorneyOrganization.ListAction',
            'view' => 'application.modules.legal.controllers.PowerAttorneyOrganization.ViewAction',
            'edit' => 'application.modules.legal.controllers.PowerAttorneyOrganization.UpdateAction',
            'add' => 'application.modules.legal.controllers.PowerAttorneyOrganization.CreateAction',
            'delete' => 'application.modules.legal.controllers.PowerAttorneyOrganization.DeleteAction',

            '_html_form_select_element' => 'application.modules.legal.controllers.PowerAttorneyOrganization.HtmlFormSelectElementAction',
            '_html_row_element' => 'application.modules.legal.controllers.PowerAttorneyOrganization.HtmlRowElementAction',
        );
    }

    /**
     *  Добавление новой доверености к указанному в $org_id юридическому лицу.
     *
     *  @param  string $org_id
     *
     *  @throws CHttpException
     */
    public function actionAdd($org_id)
    {
        $org = Organization::model()->findByPk($org_id);
        if (!$org){
            throw new CHttpException(404, 'Не найдено юридическое лицо.');
        }

        $doc = new  PowerAttorneyForOrganization();
        $doc->id_yur = $org->primaryKey;

        $error = '';
        if ($_POST && !empty($_POST[' PowerAttorneyForOrganization'])) {
            $doc->setAttributes($_POST[' PowerAttorneyForOrganization']);

            $doc->upload_scans  = CUploadedFile::getInstancesByName('upload_scans');
            $doc->upload_files  = CUploadedFile::getInstancesByName('upload_files');

            if ($doc->validate()) {
                try {
                    $doc->save();
                    $this->redirect($this->createUrl('documents/list', array('org_id' => $org->primaryKey)));
                } catch (Exception $e) {
                    $error = $e->getMessage();
                }
            }
        }

        $this->render('/organization/show', array(
            'content' => $this->renderPartial(
                '/power_attorney_le/form',
                array(
                    'model'         => $doc,
                    'error'         => $error,
                    'organization'  => $org
                ),
                true
            ),
            'organization' => $org,
            'cur_tab' => 'documents',
        ));
    }
}
