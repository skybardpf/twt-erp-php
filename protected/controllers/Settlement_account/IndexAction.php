<?php
/**
 * Список банковских счетов.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class IndexAction extends CAction
{
    /**
     * Список банковских счетов.
     */
    public function run()
    {
        /**
         * @var Settlement_accountController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Список счетов';

        $force_cache = (Yii::app()->request->getQuery('force_cache') == 1);
        $data = SettlementAccount::model()->listModels($force_cache);

        $controller->render(
            'index',
            array(
                'data' => $data
            )
        );
    }
}