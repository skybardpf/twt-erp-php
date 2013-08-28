<?php
/**
 * Просмотр банковского счета.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class ViewAction extends CAction
{
    /**
     * Просмотр банковского счета с идентификатором $id.
     * @param  string $id
     * @throws CHttpException
     */
    public function run($id)
    {
        /**
         * @var Settlement_accountController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Просмотр банковского счета';

        $forceCached = (Yii::app()->request->getQuery('force_cache') == 1);
        try {
            $model = SettlementAccount::model()->findByPk($id, $forceCached);
            $org = Organization::model()->findByPk($model->id_yur, $forceCached);
        } catch (CException $e){
            Yii::log($e->getMessage(), CLogger::LEVEL_ERROR, 'soap_parser');
            throw new CHttpException(500, 'Получены неправильные данные от SOAP сервера');
        }

        $controller->render('/organization/show', array(
            'content' => $controller->renderPartial('/settlement_account/view',
                array(
                    'model'         => $model,
                    'organization'  => $org
                ), true),
            'organization' => $org,
            'cur_tab' => $controller->menu_current,
        ));
    }
}