<?php
/**
 * Просмотр данных об учредительном документе.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class ViewAction extends CAction
{
    /**
     * Просмотр данных об учредительном документе.
     * @param string $id
     * @throws CHttpException
     */
    public function run($id)
    {
        /**
         * @var Founding_documentController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Просмотр учредительного документа';

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;

        $model = FoundingDocument::model()->loadModel($id, $force_cache);
        if ($model->type_yur != 'Организации') {
            throw new CHttpException(404, 'У документа неверный тип для данной страницы');
        }
        $model->setForceCached($force_cache);
        $org = Organization::model()->findByPk($model->id_yur, $force_cache);

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/founding_document/view',
                array(
                    'model' => $model,
                    'organization' => $org
                ), true),
            'organization' => $org,
            'cur_tab' => 'documents',
        ));
    }
}