<?php
/**
 * Просмотр корзины акционирования физического лица.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class CartAction extends CAction
{
    /**
     * Просмотр корзины акционирования физического лица.
     * @param string $id
     */
    public function run($id)
    {
        /**
         * @var Settlement_accountsController $controller
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Корзина акционирования физического лица';
        $controller->cur_tab = 'cart';

        $model = $controller->loadModel($id);
        $controller->render(
            'show',
            array(
                'model' => $model,
                'tab_content' => "Заглушка" // Todo Корзина акционирования
            )
        );
    }
}