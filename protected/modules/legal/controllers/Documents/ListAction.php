<?php
/**
 * Список документов организации.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class ListAction extends CAction
{
    /**
     * Список документов организации.
     * @param string $org_id
     */
    public function run($org_id)
    {
        /**
         * @var DocumentsController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Список документов';

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;

        $org = Organization::loadModel($org_id, $force_cache);

        $founding_docs = FoundingDocument::model()->listModels($org, $force_cache);

        // Доверенности
//        $power_attorneys_docs =  PowerAttorneyForOrganization::model()->listModels($org->primaryKey);
        // Свободные документы.
//        $free_docs = FreeDocument::model()->getData($org);

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/documents/list',
                array(
//                    'free_docs'         => $free_docs,
                    'founding_docs'     => $founding_docs,
//                    'power_attorneys_docs' => $power_attorneys_docs,
                    'organization'      => $org
                ), true),
            'organization' => $org,
            'cur_tab' => 'documents',
        ));
    }
}