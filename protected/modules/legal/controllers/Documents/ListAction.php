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

        $org = $controller->loadOrganization($org_id);

        // Учредительные документы
        $founding_docs = FoundingDocument::model()->getData($org);
        // Доверенности
        $power_attorneys_docs =  PowerAttorneyForOrganization::model()->listModels($org->primaryKey);
        // Свободные документы.
        $free_docs = FreeDocument::model()->getData($org);

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/documents/list',
                array(
                    'free_docs'         => $free_docs,
                    'founding_docs'     => $founding_docs,
                    'power_attorneys_docs' => $power_attorneys_docs,
                    'organization'      => $org
                ), true),
            'organization' => $org,
            'cur_tab' => 'documents',
        ));
    }
}