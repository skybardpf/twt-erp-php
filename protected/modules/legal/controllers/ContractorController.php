<?php
/**
 * Управление Контрагентами.
 *
 * @author Skibardin A.A. <skybardpf@artektiv.ru>
 */
class ContractorController extends Controller{
    public $layout = 'inner';
    public $menu_current = 'contractors';
    public $pageTitle = 'TWT Consult | Мои котрагенты | ';

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
     * @param string $id Идентификатор контрагента.
     * @return Contractor Получаем модель Контрагент.
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $model = Contractor::model()->findByPk($id);
        if ($model === null) {
            throw new CHttpException(404, 'Не найден контрагент.');
        }
        return $model;
    }

    /**
     * @return Contractor Возвращаем созданную модель Контрагент.
     * @throws CHttpException
     */
    public function createModel()
    {
        $model = new Contractor();
        return $model;
    }

    /**
     * @return Contractor[] Получаем список контрагентов.
     * @throws CHttpException
     */
    public function getDataProvider()
    {
        return Contractor::model()->findAll();
    }
}