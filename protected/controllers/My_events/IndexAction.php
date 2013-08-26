<?php
/**
 * Список событий
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class IndexAction extends CAction
{
    /**
     *  Список событий.
     */
    public function run()
    {
        /**
         * @var $controller My_eventsController
         */
        $controller = $this->controller;
        $controller->pageTitle .= ' | Список событий';

        $force_cache = (isset($_GET['force_cache']) && $_GET['force_cache'] == 1) ? true : false;
        $for_yur = Yii::app()->request->getQuery('for_yur', 1);
        $country_id = Yii::app()->request->getQuery('country_id', '');
        if ($for_yur == 1){
            $data = Event::model()->listModelsAllOrganization($force_cache);
        } else {
            if (empty($country_id)){
                $data = Event::model()->listModelsByAllCountries($force_cache);
            } else {
                $data = Event::model()->listModelsByCountry($country_id, $force_cache);
            }
        }

        $controller->render(
            'index',
            array(
                'data' => $data,
                'for_yur' => $for_yur,
                'force_cache' => $force_cache,
                'country_id' => $country_id
            )
        );
    }
}