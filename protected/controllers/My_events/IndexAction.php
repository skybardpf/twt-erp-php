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
        $model = new EventForm();
        $class = get_class($model);

        $for_yur = true;
        if ($_POST && !empty($_POST[$class])) {
            $model->setAttributes($_POST[$class]);
            $data = array();
            if ($model->validate()) {
                if ($model->for_organization == 1){
                    $data = Event::model()->listModelsAllOrganization($force_cache);
                } else {
                    $for_yur = false;
                    if (empty($model->country_id)){
                        $data = Event::model()->listModelsByAllCountries($force_cache);
                    } else {
                        $data = Event::model()->listModelsByCountry($model->country_id, $force_cache);
                    }
                }
            }
        } else {
            $data = Event::model()->listModelsAllOrganization($force_cache);
        }

        $controller->render(
            'index',
            array(
                'data' => $data,
                'model' => $model,
                'for_yur' => $for_yur,
                'force_cache' => $force_cache
            )
        );
    }
}