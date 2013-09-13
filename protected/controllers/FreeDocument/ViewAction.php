<?php
/**
 * Просмотр данных о свободном документе.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class ViewAction extends CAction
{
    /**
     * Просмотр данных о свободном документе.
     * @param string $id
     */
    public function run($id)
    {
        /**
         * @var Free_documentController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Просмотр документа';

        $model = FreeDocument::model()->loadModel($id, $controller->getForceCached());
        $org = Organization::model()->findByPk($model->id_yur, $controller->getForceCached());

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/free_document/view',
                array(
                    'model'         => $model,
                    'organization'  => $org
                ), true),
            'organization' => $org,
            'cur_tab' => 'documents',
        ));
    }
}