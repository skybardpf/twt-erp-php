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

        $forceCached = (Yii::app()->request->getQuery('force_cache') == 1);
        $data = SettlementAccount::model()->listModels($forceCached);

        $controller->render(
            'index',
            array(
                'data' => $data,
                'forceCached' => $forceCached
            )
        );
    }
}