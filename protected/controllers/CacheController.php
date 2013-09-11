<?php
/**
 * Глобальный сброс кеша.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class CacheController extends Controller
{
    public $layout = 'inner';
    public $menu_current = 'legal';
//    public $current_tab = 'calendar_events';
//    public $pageTitle = 'TWT Consult | Мои организации';

    /**
     * Глобальный сброс кеша.
     * @return array
     */
    public function actionClean()
    {
        Yii::app()->cache->flush();
        echo 'The cache was successfully cleared';
    }
}