<?php
/**
 * Управление группами контрагентов.
 *
 * @author Skibardin A.A. <webprofi1983@gmail.com>
 */
class Contractor_groupController extends Controller{
    public $layout = 'inner';
    public $menu_current = 'contractors';
    public $pageTitle = 'TWT Consult | Контрагенты | Группы контрагентов';

    /**
     * Распределение экшенов.
     * @return array
     */
    public function actions()
    {
        return array(
            'index' => 'application.controllers.ContractorGroup.IndexAction',
            'update' => 'application.controllers.ContractorGroup.UpdateAction',
            'create' => 'application.controllers.ContractorGroup.CreateAction',
            'delete' => 'application.controllers.ContractorGroup.DeleteAction',
        );
    }

    /**
     * @param string $id    Идентификатор группы.
     * @return ContractorGroup   Получаем модель.
     * @throws CHttpException
     */
    public function loadModel($id)
    {
        $cache_id = get_class(ContractorGroup::model()).'_'.$id;
        $model = Yii::app()->cache->get($cache_id);
        if ($model === false){
            $model = ContractorGroup::model()->findByPk($id);
            if ($model === null) {
                throw new CHttpException(404, 'Не найдена группа контрагента.');
            }
            Yii::app()->cache->set($cache_id, $model);
        }
        return $model;
    }

    /**
     * @param integer $parent_id
     * @return ContractorGroup Возвращаем созданную модель.
     */
    public function createModel($parent_id)
    {
        $model = new ContractorGroup();
        $model->parent_id = $parent_id;
        return $model;
    }
}