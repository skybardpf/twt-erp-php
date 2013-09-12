<?php
/**
 * Управление Корзиной акционирования
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class Cart_corporatizationController extends Controller
{
    public $layout = 'inner';
    public $menu_current = 'cart_corporatization';
    public $pageTitle = 'TWT Consult | Корзина акционирования';
    public $defaultAction = 'direct';

    /**
     * @return array
     */
    public function actions()
    {
        return array(
            'direct' => 'application.controllers.CartCorporatization.DirectAction',
            'indirect' => 'application.controllers.CartCorporatization.IndirectAction',
        );
    }
}