<?php
/**
 * Просмотр физического лица.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class ViewAction extends CAction
{
    /**
     * Просмотр физического лица.
     * @param string $id
     */
    public function run($id)
    {
        /**
         * @var Settlement_accountsController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Просмотр физического лица';
        $controller->cur_tab = 'view';

        $model = $controller->loadModel($id);
        $controller->render(
            'show',
            array(
                'model' => $model,
                'tab_content' => $controller->renderPartial(
                    'tab_view',
                    array(
                        'model' => $model
                    ),
                    1
                )
            )
        );
    }
}