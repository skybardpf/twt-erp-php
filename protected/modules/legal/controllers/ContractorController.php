<?php
/**
 * Управление Контрагентами.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class ContractorController extends Controller{
    public $layout = 'inner';
    public $menu_current = 'contractors';
    public $pageTitle = 'TWT Consult | Мои котрагенты';

    /**
     * Распределение экшенов.
     * @return array
     */
    public function actions()
    {
        return array(
            'index' => 'application.modules.legal.controllers.Contractor.IndexAction',
            'view' => 'application.modules.legal.controllers.Contractor.ViewAction',
            'edit' => 'application.modules.legal.controllers.Contractor.UpdateAction',
            'add' => 'application.modules.legal.controllers.Contractor.CreateAction',
            'delete' => 'application.modules.legal.controllers.Contractor.DeleteAction',

            'get_activities_types' => 'application.modules.legal.controllers.Contractor.GetActivitiesTypesAction',
        );
    }

    /**
     * @param string $id    Идентификатор контрагента.
     * @return Contractor   Получаем модель Контрагент.
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $cache_id = get_class(Contractor::model()).'_'.$id;
        $model = Yii::app()->cache->get($cache_id);
        if ($model === false){
            $model = Contractor::model()->findByPk($id);
            if ($model === null) {
                throw new CHttpException(404, 'Не найден контрагент.');
            }
            Yii::app()->cache->set($cache_id, $model);
        }
        return $model;
    }

    /**
     * @return Contractor Возвращаем созданную модель Контрагент.
     */
    public function createModel()
    {
        $model = new Contractor();
        return $model;
    }

    /**
     * @return Contractor[] Получаем список контрагентов.
     */
    public function getDataProvider()
    {
        $cache_id = get_class(Contractor::model()).'_list';
        $data = Yii::app()->cache->get($cache_id);
        if ($data === false){
            $data = Contractor::model()->findAll();
            Yii::app()->cache->set($cache_id, $data);
        }
        return $data;
    }
}